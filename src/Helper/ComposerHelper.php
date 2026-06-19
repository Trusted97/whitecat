<?php

namespace Whitecat\Helper;

use Whitecat\Exception\InvalidComposerException;

class ComposerHelper
{
    /**
     * @throws \JsonException
     * @throws InvalidComposerException
     *
     * @return array<mixed, mixed>
     */
    public function getComposerContent(string $composerPath): array
    {
        $composerContent = \file_get_contents($composerPath);

        if (false === $composerContent || 1 !== \preg_match('/\S/u', $composerContent)) {
            throw new InvalidComposerException(\sprintf('Unable to read a valid composer.json file at "%s".', $composerPath));
        }

        $composer = \json_decode(
            json: $composerContent,
            associative: true,
            depth: 512,
            flags: \JSON_THROW_ON_ERROR
        );

        if (!\is_array($composer) || \array_is_list($composer)) {
            throw new InvalidComposerException(\sprintf('The composer.json file at "%s" must decode to a JSON object.', $composerPath));
        }

        return $composer;
    }
}
