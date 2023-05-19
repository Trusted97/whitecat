<?php

namespace Whitecat\Helper;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use PHPUnit\Framework\Attributes\IgnoreFunctionForCodeCoverage;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class CopyHelper
{
    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
    }

    #[CodeCoverageIgnore]
    public function setupAndCopyFile(
        string $fileName,
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage,
        string $sourceFileDirectory,
        string $distFileDirectory
    ): void {
        $checkExists = $this->fs->exists($sourceFileDirectory . $fileName);
        $override    = true;

        if ($checkExists) {
            $override = $this->io->confirm(
                question: $questionMessage,
                default: false
            );
        }

        if ($override) {
            $this->io->comment($overrideCommentMessage);
            $this->fs->copy(
                originFile: Path::normalize(__DIR__ . '/../../' . $distFileDirectory . $fileName),
                targetFile: $sourceFileDirectory . $fileName,
                overwriteNewerFiles: true
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }

    #[CodeCoverageIgnore]
    public function setupAndCopyDirectory(
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage,
        string $sourceDirectory,
        string $distDirectory
    ): void {
        $checkExists = $this->fs->exists($sourceDirectory);
        $override    = true;

        if ($checkExists) {
            $override = $this->io->confirm(
                question: $questionMessage,
                default: false
            );
        }

        if ($override) {
            $this->io->comment($overrideCommentMessage);
            $this->fs->mirror(
                originDir: Path::normalize(__DIR__ . '/../../' . $distDirectory),
                targetDir: $sourceDirectory
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }
}
