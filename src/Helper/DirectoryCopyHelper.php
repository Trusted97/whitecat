<?php

namespace Whitecat\Helper;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class DirectoryCopyHelper
{
    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        private readonly string $workingDirectory = '.',
    ) {
    }

    public function copyDirectory(
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage,
        string $sourceDirectory,
        string $distDirectory,
    ): void {
        $targetDirectory = Path::join($this->workingDirectory, $sourceDirectory);
        $checkExists     = $this->fs->exists($targetDirectory);
        $override        = true;

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
                targetDir: $targetDirectory
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }
}
