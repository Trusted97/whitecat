<?php

namespace Whitecat\Test\Support;

use Symfony\Component\Filesystem\Filesystem;

trait TempProject
{
    /** @var list<string> */
    private array $temporaryDirectories = [];

    protected function createTempProject(): string
    {
        $directory = \sys_get_temp_dir() . '/whitecat-test-' . \bin2hex(\random_bytes(8));
        (new Filesystem())->mkdir($directory);
        $this->temporaryDirectories[] = $directory;

        return $directory;
    }

    protected function writeComposer(string $projectDirectory, array|string $contents): void
    {
        $json = \is_array($contents)
            ? \json_encode($contents, \JSON_PRETTY_PRINT | \JSON_THROW_ON_ERROR)
            : $contents;

        \file_put_contents($projectDirectory . '/composer.json', $json);
    }

    protected function tearDownTempProject(): void
    {
        $filesystem = new Filesystem();

        foreach ($this->temporaryDirectories as $directory) {
            $filesystem->remove($directory);
        }

        $this->temporaryDirectories = [];
    }
}
