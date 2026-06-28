<?php

namespace Whitecat\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Path;
use Whitecat\Exception\InvalidComposerException;

final class ComposerPackageChecker
{
    public function __construct(
        private readonly ComposerHelper $composerHelper = new ComposerHelper(),
    ) {
    }

    public function ensureDevPackageInstalled(
        SymfonyStyle $io,
        string $workingDirectory,
        string $packageName,
    ): int {
        try {
            $composer = $this->composerHelper->getComposerContent(Path::join($workingDirectory, 'composer.json'));
        } catch (\JsonException $jsonException) {
            $io->error(\sprintf('composer.json is not valid JSON: %s', $jsonException->getMessage()));
            $io->note('Fix composer.json and run this command again.');

            return Command::FAILURE;
        } catch (InvalidComposerException $exception) {
            $io->error($exception->getMessage());
            $io->note('Run this command from a Composer project or pass --working-dir=/path/to/project.');

            return Command::FAILURE;
        }

        $requireDev = $composer['require-dev'] ?? null;

        if (!\is_array($requireDev)) {
            $io->error('composer.json must contain an object-shaped "require-dev" section.');
            $io->note(\sprintf('Install the dependency with: composer require --dev %s', $packageName));

            return Command::FAILURE;
        }

        if (!\array_key_exists($packageName, $requireDev)) {
            $io->warning(\sprintf('%s is not installed in require-dev.', $packageName));
            $io->note(\sprintf('Install it with: composer require --dev %s', $packageName));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
