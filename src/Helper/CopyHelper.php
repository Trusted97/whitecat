<?php

namespace Whitecat\Helper;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
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
    public function setupAndCopyAction(
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
                originFile: Path::normalize($distFileDirectory . $fileName),
                targetFile: $sourceFileDirectory . $fileName,
                overwriteNewerFiles: true
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }
}
