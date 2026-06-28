<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

abstract class AbstractWhitecatCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            name: 'working-dir',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Run the command as if it was started in the given project directory.'
        );

        $this->setHelp($this->commandHelp());
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            return $this->runCommand($io, new Filesystem(), $this->resolveWorkingDirectory($input));
        } catch (\Throwable $throwable) {
            $io->error($throwable->getMessage());
            $io->note('Run the command again with -vvv for a stack trace, or check that the target project is writable.');

            return self::FAILURE;
        }
    }

    abstract protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int;

    protected function commandHelp(): string
    {
        return 'Generates project files in the current directory. Use <comment>--working-dir</comment> to target another project.';
    }

    private function resolveWorkingDirectory(InputInterface $input): string
    {
        $workingDirectory = $input->getOption('working-dir');

        if (!\is_string($workingDirectory) || 1 !== \preg_match('/\S/u', $workingDirectory)) {
            $workingDirectory = \getcwd() ?: '.';
        }

        return Path::canonicalize($workingDirectory);
    }
}
