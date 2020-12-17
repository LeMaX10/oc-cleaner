<?php namespace LeMaX10\Cleaner\Classes\Strategy;


use LeMaX10\Cleaner\Classes\Contracts\CleanerStrategy;
use October\Rain\Database\Collection;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Console\OutputStyle as Output;
use System\Models\File;

/**
 * Class OptimizeImages
 * @package LeMaX10\Cleaner\Classes\Strategy
 */
class OptimizeImages implements CleanerStrategy
{
    /**
     * @var OptimizerChain
     */
    protected $optimizer;

    /**
     * @var null|Output
     */
    protected $output;

    /**
     * OptimizeImages constructor.
     */
    public function __construct()
    {
        $this->optimizer = OptimizerChainFactory::create();
    }

    /**
     * @inheritDoc
     */
    public function handle(?Output $output): void
    {
        if (!$output) {
            $output = optional($output);
        }

        $imageExtensions = array_map(static function(string $extensions): string {
            return 'image/'. $extensions;
        }, File::$imageExtensions);

        $imagesInSystemStorage = File::whereIn('content_type', $imageExtensions)->all();
        $imageToOptimize = $imagesInSystemStorage->filter(static function(File $file): bool {
           return $file->getDisk()->exists($file->getDiskPath());
        });

        $this->optimizeImages($imageToOptimize);

        unset($imagesInSystemStorage, $imageToOptimize);
    }

    /**
     * @param Collection $imageFiles
     */
    protected function optimizeImages(Collection $imageFiles): void
    {
        $imageFiles->each(function(File $imageFile): void {
            $this->optimizer->optimize($imageFile->getDiskPath());
        });
    }
}
