<?php

namespace Mlantz\Changelog\Commands;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('log:add')
            ->setDescription('Add a record to the CHANGELOG.md')
            ->addArgument(
                'level',
                InputArgument::REQUIRED,
                'Version level (major|minor|patch)'
            )
            ->addOption(
                'added',
                null,
                InputOption::VALUE_REQUIRED,
                'Code added'
            )
            ->addOption(
                'changed',
                null,
                InputOption::VALUE_REQUIRED,
                'Code changed'
            )
            ->addOption(
                'fixed',
                null,
                InputOption::VALUE_REQUIRED,
                'Code fixed'
            )
            ->addOption(
                'removed',
                null,
                InputOption::VALUE_REQUIRED,
                'Code removed'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time_start = microtime(true);

        $level = $input->getArgument('level');

        if (! in_array($level, ['major', 'minor', 'patch'])) {
            throw new \InvalidArgumentException("Your version level must be one of the following: major, minor, patch", 1);
        }

        $addedMarkdown = '';
        $summary = '';

        foreach (['added', 'changed', 'fixed', 'removed'] as $action) {
            if ($input->getOption($action)) {
                $addedMarkdown .= "\n\n### ".ucfirst($action);
                foreach (explode(',', $input->getOption($action)) as $point) {
                    $addedMarkdown .= "\n- $point";
                    $summary .= " - $point";
                }
            }
        }

        $currentVersion = $this->getCurrentVersion();

        switch ($level) {
            case 'major':
                $currentVersion[0]++;
                $currentVersion[1] = 0;
                $currentVersion[2] = 0;
                break;

            case 'minor':
                $currentVersion[1]++;
                $currentVersion[2] = 0;
                break;

            case 'patch':
                $currentVersion[2]++;
                break;

            default:
                $currentVersion[2]++;
                break;
        }

        $currentVersion[0] = (string) $currentVersion[0];
        $currentVersion[1] = (string) $currentVersion[1];
        $currentVersion[2] = (string) $currentVersion[2];

        $newVersion = 'v'.implode('.', $currentVersion);

        $addedMarkdown = "\n## [$newVersion] - ".date('Y-m-d').$addedMarkdown;

        $changelogCase = $this->getChangelogCase();

        if (file_exists(getcwd().'/'.$changelogCase.'.md')) {
            $changelog = file_get_contents(getcwd().'/'.$changelogCase.'.md');
            $changelogArray = explode('----', $changelog);
            $markdown = $changelogArray[0]."----\n".$addedMarkdown.$changelogArray[1];

            if (file_put_contents(getcwd().'/'.$changelogCase.'.md', $markdown)) {
                $output->writeln('Changelog updated');
                $this->tagGIT($newVersion, $summary, $changelogCase);
                $output->writeln('Commit updated');
                $output->writeln('Repository Tagged with: '.$newVersion);
            }
        } else {
            throw new \Exception("Please run this first: clg log:create {name}", 1);
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $output->writeln("\nCompleted in: ".$time." seconds");

        return Command::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */

    private function getCurrentVersion()
    {
        $version = [0,0,0];

        exec("git describe --tags $(git rev-list --tags --max-count=1)", $gitVersion);

        if (! empty($gitVersion[0])) {
            $versionAsString = str_replace('v', '', $gitVersion[0]);
            $version = explode('.', $versionAsString);
        }

        return $version;
    }

    private function getCommitHash()
    {
        exec("git log --name-status HEAD^..HEAD", $gitStatus);

        $commitHash = str_replace("commit ", "", $gitStatus[0]);

        return $commitHash;
    }

    private function tagGIT($version, $summary, $changelogCase)
    {
        exec("git commit '".getcwd().'/'.$changelogCase.'.md'."' -m 'Changelog.md update' && git tag -a ".$version." -m '".$summary."'");
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
