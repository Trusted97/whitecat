<?php

namespace Whitecat\Helper;

use Whitecat\Exception\InvalidComposerException;

class ComposerHelper
{
    /**
     * @throws \JsonException
     * @throws InvalidComposerException
     *
     * @return string[][]
     */
    public function getComposerContent(string $composerPath): array
    {
        $composerContent = \file_get_contents($composerPath);

        if (!$composerContent) {
            throw new InvalidComposerException();
        }

        $composer = \json_decode(
            json: $composerContent,
            associative: true,
            depth: 512,
            flags: \JSON_THROW_ON_ERROR
        );

        if (!\is_array($composer)) {
            throw new InvalidComposerException();
        }

        return $composer;
    }
}
