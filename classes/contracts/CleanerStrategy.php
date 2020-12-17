<?php namespace LeMaX10\Cleaner\Classes\Contracts;


use Illuminate\Console\OutputStyle as Output;

/**
 * Interface CleanerStrategy
 * @package LeMaX10\Cleaner\Classes\Contracts
 */
interface CleanerStrategy
{
    /**
     * @param Output|null $output
     * @return void
     */
    public function handle(?Output $output): void;
}
