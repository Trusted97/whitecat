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
    ) {
    }

    public function copyDirectory(
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage,
        string $sourceDirectory,
        string $distDirectory,
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
