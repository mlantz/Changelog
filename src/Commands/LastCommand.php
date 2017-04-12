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

        if (file_exists(getcwd().'/changelog.md')) {
            $changelog = file_get_contents(getcwd().'/changelog.md');

            $changelogParts = explode('----', $changelog);

            $changes = explode('## [', trim($changelogParts[1]));

            foreach ($changes as $index => $change) {
                if ($index == 1) {
                    $output->writeln("\n[$change");
                }
            }
        } else {
            throw new \Exception("Please run this first: clg log:create {name}", 1);
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $output->writeln("\nCompleted in: ".$time." seconds");
    }

}
