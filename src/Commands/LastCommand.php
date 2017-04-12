<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LastCommand extends Command
{
    protected function configure()
    {
        $this->setName('log:last')
             ->setDescription('Get the most recent changelog');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time_start = microtime(true);

        $changelog = $this->getChangeLog();
        $changes = $this->getChangeLogChanges($changelog, $output);

        foreach ($changes as $index => $change) {
            if ($index == 1) {
                $output->writeln("\n[$change");
            }
        }

        $time_end = microtime(true);
        $time = round($time_end - $time_start,5);

        $output->writeln("Completed in: ${time} seconds");
    }

    /**
     * Get the current contents of the CHANGELOG
     *
     * @return string
     */
    protected function getChangeLog()
    {
        $contents = @file_get_contents(getcwd() . '/changelog.md');
        if($contents === false) {
            throw new \Exception("Please run this first: clg log:create {name}", 1);
        }
        return $contents;
    }

        $time_end = microtime(true);
        $time = $time_end - $time_start;
    /**
     * Get the latest changes to the CHANGELOG
     *
     * @param  string $changelog
     * @return array
     */
    protected function getChangeLogChanges($changelog)
    {
        $changelogParts = explode('----', $changelog);

        if(!isset($changelogParts[1])) {
            throw new \Exception("Your CHANGELOG is empty. Please add some lines first with the clg log:add command.", 1);
        }

        $changes = explode('## [', trim($changelogParts[1]));

        return $changes;
    }

}
