<?php namespace LeMaX10\Cleaner\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LeMaX10\Antivirus\Classes\Contracts\RepositoryItem;
use LeMaX10\Antivirus\Classes\Contracts\Scanner;
use LeMaX10\Cleaner\Classes\Cleaner;
use LeMaX10\Cleaner\Classes\Strategy\OptimizeImages;
use LeMaX10\Cleaner\Classes\Strategy\OptimizeStorage;
use LeMaX10\Cleaner\Classes\Strategy\StorageClean;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class OptimizationCommand
 * @package LeMaX10\Cleaner\Console
 */
class OptimizationCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'cleaner:run';

    /**
     * @var string The console command description.
     */
    protected $description = 'Run cleaner command';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(Cleaner $scanner)
    {
        $this->line('Run...');

        if ($this->option('optimize')) {
            $scanner->addStrategy(OptimizeStorage::class);
        }

        if ($this->option('clean')) {
            $scanner->addStrategy(StorageClean::class);
        }

        if ($this->option('optimizeImage')) {
            $scanner->addStrategy(OptimizeImages::class);
        }

        $scanner->run($this->getOutput());
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['optimize', null, InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'Optimize storage size with delete unused filed'],
            ['clean', null, InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'Clean system_files table with not exists files in storage'],
            ['optimizeImage',  null, InputOption::VALUE_OPTIONAL || InputOption::VALUE_NONE, 'Optimize size images']
        ];
    }

}
