<?php namespace LeMaX10\Cleaner\Classes\Strategy;


use LeMaX10\Cleaner\Classes\Contracts\CleanerStrategy;
use Illuminate\Console\OutputStyle as Output;
use System\Models\File;

class OptimizeStorage implements CleanerStrategy
{

    /**
     * @inheritDoc
     */
    public function handle(?Output $output): void
    {
        if (!$output) {
            $output = optional($output);
        }

        $filesInSystemStorage = File::with('attachment')->get();

        $storageBefore = $filesInSystemStorage->sum('file_size');
        $storageAfter  = $storageBefore;

        $filesForDelete = $filesInSystemStorage->filter(static function(File $file): bool {
            return !$file->attachment;
        })->each(static function(File $file) use(&$storageAfter): void {
            $storageAfter -= $file->file_size;
            $file->delete();
        });

        if ($filesForDelete->isNotEmpty()) {
            $output->writeln(
                'Table system_files is optimize. Before deleting not used items ' . $storageBefore . ' bytes, after clean ' . $storageAfter .' bytes'
            );
        } else {
            $output->writeln('The system_files table does not need optimized.');
        }

        unset($filesInSystemStorage, $filesForDelete, $storageBefore, $storageAfter);
    }
}
