<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    /**
     * Settings for setting up CHANGELOG command
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('log:create')
            ->setDescription('Create CHANGELOG.md')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the project'
            );
    }

    /**
     * Create a CHANGELOG if one doesn't already exists
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $time_start = microtime(true);

        $name = $input->getArgument('name');

        $markdown = "# Change Log - " . ucfirst($name) . "\nAll notable changes to this project will be documented in this file.\nThis project adheres to [Semantic Versioning](http://semver.org/).\n----\n";

        $changelogCase = 'CHANGELOG';

        file_put_contents(getcwd().'/'.$changelogCase.'.md', $markdown);

        $output->writeln('Created '.getcwd().'/'.$changelogCase.'.md');

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $output->writeln("\nCompleted in: {$time} seconds");

        return Command::SUCCESS;
    }
}
