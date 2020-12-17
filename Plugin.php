<?php namespace LeMaX10\Cleaner;

use LeMaX10\Cleaner\Console\OptimizationCommand;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package LeMaX10\Cleaner
 */
class Plugin extends PluginBase
{
    /**
     * @return array|string[]
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Cleaner',
            'description' => 'System Files cleaner and optimize images',
            'author'      => 'Vladimir Pyankov',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register(): void
    {
        parent::register();

        $this->registerConsoleCommand('lemax10.cleaner.optimization', OptimizationCommand::class);
    }

    public function boot(): void
    {
        parent::boot();
    }

    /**
     * @return array
     */
    public function registerComponents(): array
    {
        return [

        ];
    }
}
