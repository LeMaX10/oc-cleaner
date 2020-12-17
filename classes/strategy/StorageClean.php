<?php namespace LeMaX10\Cleaner\Classes\Strategy;


use LeMaX10\Cleaner\Classes\Contracts\CleanerStrategy;
use Illuminate\Console\OutputStyle as Output;
use System\Models\File;

class StorageClean implements CleanerStrategy
{

    /**
     * @inheritDoc
     */
    public function handle(?Output $output): void
    {
        if (!$output) {
            $output = optional($output);
        }

        $filesInSystemStorage = File::all();
        $itemsBefore          = $filesInSystemStorage->count();

        $filesForDelete = $filesInSystemStorage->filter(function(File $file): bool {
           return !$file->getDisk()->exists($file->getDiskPath());
        })->lists('id');

        $itemsAfter  = $itemsBefore - $filesForDelete->count();
        if ($filesForDelete->isNotEmpty()) {
            File::whereIn('id', $filesForDelete)->delete();
            $output->writeln(
                'Table system_files is clear. Before clean items ' . $itemsBefore . ', after clean ' . $itemsAfter
            );
        } else {
            $output->writeln('The system_files table does not need cleaning.');
        }

        unset($filesInSystemStorage, $filesForDelete);
    }
}
