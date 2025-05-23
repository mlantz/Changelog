<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LastCommand extends Command
{
    /**
     * Set the name and description of the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('log:last')
             ->setDescription('Get the most recent changelog');
    }

    /**
     * Display the last change in the CHANGELOG
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
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
        $time = round($time_end - $time_start, 5);

        $output->writeln("Completed in: {$time} seconds");

        return Command::SUCCESS;
    }

    /**
     * Get the current contents of the CHANGELOG
     *
     * @return string
     */
    protected function getChangeLog()
    {
        $changelogCase = $this->getChangelogCase();

        $contents = file_get_contents(getcwd().'/'.$changelogCase.'.md');

        if ($contents === false) {
            throw new \Exception("Please run this first: clg log:create {name}", 1);
        }
        return $contents;
    }

    /**
     * Get the latest changes to the CHANGELOG
     *
     * @param  string $changelog
     * @return array
     */
    protected function getChangeLogChanges($changelog)
    {
        $changelogParts = explode('----', $changelog);

        if (!isset($changelogParts[1])) {
            throw new \Exception("Your CHANGELOG is empty. Please add some lines first with the clg log:add command.", 1);
        }

        $changes = explode('## [', trim($changelogParts[1]));

        return $changes;
    }

    private function getChangelogCase()
    {
        foreach (scandir(getcwd().'/') as $file) {
            if ($file === 'CHANGELOG.md') {
                return 'CHANGELOG';
            } elseif ($file === 'changelog.md') {
                return 'changelog';
            }
        }
    }
}
