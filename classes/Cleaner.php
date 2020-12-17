<?php namespace LeMaX10\Cleaner\Classes;


use LeMaX10\Cleaner\Classes\Contracts\CleanerStrategy;
use Illuminate\Console\OutputStyle as Output;

/**
 * Class Cleaner
 * @package LeMaX10\Cleaner\Classes
 */
class Cleaner
{
    /**
     * @var array
     */
    protected $strategies = [];

    /**
     * @return array
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * @param $strategy
     * @return $this
     */
    public function addStrategy($strategy): self
    {
        $this->strategies[] = $strategy;
        return $this;
    }

    /**
     * @param Output|null $output
     */
    public function run(?Output $output = null): void
    {
        $strategies = array_map(static function(string $strategy): CleanerStrategy {
            $strategyInstance = app($strategy);
            if (!$strategyInstance instanceof CleanerStrategy) {
                throw new \RuntimeException(sprintf('%s strategy not is implement %s', $strategy, CleanerStrategy::class));
            }

            return $strategyInstance;
        }, $this->getStrategies());

        foreach($strategies as $strategy) {
            $strategy->handle($output);
        }
    }
}
