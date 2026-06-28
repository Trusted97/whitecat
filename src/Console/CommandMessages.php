<?php

namespace Whitecat\Console;

final class CommandMessages
{
    public const SUCCESS = 'Done. Your project files are ready.';

    public static function skipped(string $target): string
    {
        return \sprintf('Skipped %s.', $target);
    }

    public static function adding(string $target): string
    {
        return \sprintf('Adding %s...', $target);
    }
}
