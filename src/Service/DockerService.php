<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\CopyHelper;

class DockerService
{
    protected readonly string $dockerComposePath;
    protected readonly string $dockerDirectoryPath;
    protected readonly string $dockerDistDirectoryPath;
    protected CopyHelper $copyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->dockerComposePath       = Path::normalize(DirectoryPath::DOCKER_COMPOSE->value);
        $this->dockerDirectoryPath     = Path::normalize(DirectoryPath::DOCKER->value);
        $this->dockerDistDirectoryPath = Path::normalize(DirectoryPath::DIST_DOCKER->value);
        $this->copyHelper              = new CopyHelper($this->io, $this->fs);
    }

    public function run(): int
    {
        $this->io->title('Docker setup');

        $this->addDockerDirectory();
        $this->addDockerCompose();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CodeCoverageIgnore]
    private function addDockerDirectory(): void
    {
        $dockerDirectoryExists   = $this->fs->exists($this->dockerDirectoryPath);
        $override                = true;

        if ($dockerDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that docker directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->dockerDirectoryPath);
                $this->io->comment('Docker directory created');
            } catch (IOExceptionInterface $IOException) {
                $this->io->error(
                    \sprintf(
                        'An error occurred while creating your directory at %s',
                        $IOException->getPath()
                    )
                );
            } catch (\Exception $commandException) {
                $this->io->error(
                    \sprintf(
                        'An error occurred while executing docker:setup command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of docker directory');
        }

        $this->copyHelper->setupAndCopyDirectory(
            questionMessage: 'It seems that config for PHP 8.1 already exists, do you want to override?',
            overrideCommentMessage: 'Adding PHP 8.1 config',
            skippedMessage: 'Skipped creation of PHP 8.1 config',
            sourceDirectory: $this->dockerDirectoryPath . 'php-8.1',
            distDirectory: $this->dockerDistDirectoryPath . 'php-8.1'
        );
    }

    #[CodeCoverageIgnore]
    private function addDockerCompose(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: 'docker-compose.yml',
            questionMessage: 'It seems that a docker-compose.yml already exists, do you want to override?',
            overrideCommentMessage: 'Adding docker-compose.yml',
            skippedMessage: 'Skipped creation of docker-compose.yml',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DOCKER->value
        );
    }
}
