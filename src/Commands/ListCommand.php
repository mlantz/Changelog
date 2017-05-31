<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('log:list')
            ->setDescription('Get the full changelog')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time_start = microtime(true);

        $changelogCase = $this->getChangelogCase();

        if (file_exists(getcwd().'/'.$changelogCase.'.md')) {
            $changelog = file_get_contents(getcwd().'/'.$changelogCase.'.md');

            $changelogParts = explode('----', $changelog);

            $changes = explode('## [', trim($changelogParts[1]));

            foreach ($changes as $index => $change) {
                if ($index != 0) {
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
