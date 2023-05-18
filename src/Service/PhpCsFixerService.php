<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\CopyHelper;

class PhpCsFixerService
{
    protected readonly string $workflowDirectoryPath;
    protected CopyHelper $copyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->workflowDirectoryPath = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->copyHelper            = new CopyHelper($this->io, $this->fs);
    }

    /**
     * @throws \JsonException
     */
    public function run(): int
    {
        $this->io->title('Setup basic php-cs-fixer');

        $composerContent = \file_get_contents('./composer.json');

        if (!$composerContent) {
            $this->io->error('No composer.json found!');

            return Command::FAILURE;
        }

        $composer = \json_decode(
            json: $composerContent,
            associative: true,
            depth: 512,
            flags: \JSON_THROW_ON_ERROR
        );

        if (!\is_array($composer)) {
            $this->io->error('Invalid composer.json file!');

            return Command::FAILURE;
        }

        $requireDev = $composer['require-dev'];

        $isInstalledPhpCsFixer = \array_key_exists('friendsofphp/php-cs-fixer', $requireDev);

        if (!$isInstalledPhpCsFixer) {
            $this->io->warning('It seems that php-cs-fixer is not installed');
            $this->io->warning('Launch in terminal \'composer require --dev friendsofphp/php-cs-fixer\'');

            return Command::FAILURE;
        }

        $this->addPhpCsFixer();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CodeCoverageIgnore]
    private function addPhpCsFixer(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: '.php-cs-fixer.dist.php',
            questionMessage: 'It seems that a .php-cs-fixer.dist.php already exists, do you want to override?',
            overrideCommentMessage: 'Adding .php-cs-fixer.dist.php',
            skippedMessage: 'Skipped creation of .php-cs-fixer.dist.php',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DIRECTORY->value
        );
    }
}
