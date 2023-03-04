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
    protected CopyHelper $copyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->dockerComposePath   = Path::normalize(DirectoryPath::DOCKER_COMPOSE->value);
        $this->dockerDirectoryPath = Path::normalize(DirectoryPath::DOCKER->value);
        $this->copyHelper          = new CopyHelper($this->io, $this->fs);
    }

    public function run(): int
    {
        $this->io->title('Github workflow');

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
                        'An error occurred while executing github:workflow command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of docker directory');
        }
    }

    #[CodeCoverageIgnore]
    private function addDockerCompose(): void
    {
        $this->copyHelper->setupAndCopyAction(
            fileName: 'docker-compose.yml',
            questionMessage: 'It seems that a docker-compose.yml already exists, do you want to override?',
            overrideCommentMessage: 'Adding docker-compose.yml',
            skippedMessage: 'Skipped creation of docker-compose.yml',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DOCKER->value
        );
    }
}
