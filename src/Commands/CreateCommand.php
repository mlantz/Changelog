<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
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
        $this->setName('log:create')
            ->setDescription('Create CHANGELOG.md')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the project'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = '';
        $time_start = microtime(true);

        $name = $input->getArgument('name');

        $markdown = "# Change Log - ".ucfirst($name)."\nAll notable changes to this project will be documented in this file.\nThis project adheres to [Semantic Versioning](http://semver.org/).\n----\n";

        if (! file_exists(getcwd() . '/CHANGELOG.md')) {
            file_put_contents(getcwd() . '/CHANGELOG.md', $markdown);
        }

        $output->writeln('Created ' . getcwd() . '/CHANGELOG.md');

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $output->writeln("\nCompleted in: ".$time." seconds");
    }
}
