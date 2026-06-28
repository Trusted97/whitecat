<?php

namespace Whitecat\Helper;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class FileCopyHelper
{
    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        private readonly string $workingDirectory = '.',
    ) {
    }

    public function copyFile(
        string $fileName,
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage,
        string $sourceFileDirectory,
        string $distFileDirectory,
    ): void {
        $targetFile  = Path::join($this->workingDirectory, $sourceFileDirectory, $fileName);
        $checkExists = $this->fs->exists($targetFile);
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
                targetFile: $targetFile,
                overwriteNewerFiles: true
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }
}
