<?php

namespace Whitecat\Test\Helper;

use PHPUnit\Framework\TestCase;
use Whitecat\Exception\InvalidComposerException;
use Whitecat\Helper\ComposerHelper;

class ComposerHelperTest extends TestCase
{
    public function testConstruct(): void
    {
        $composerHelper = new ComposerHelper();
        $this->assertNotNull($composerHelper);
        $this->assertInstanceOf(ComposerHelper::class, $composerHelper);
    }

    public function testGetComposerContent(): void
    {
        $validComposerPath   = __DIR__ . '/../fixtures/valid-composer.json';
        $invalidComposerPath = __DIR__ . '/../fixtures/invalid-composer.json';
        $emptyComposerPath   = __DIR__ . '/../fixtures/empty.json';

        $validJson = [
            'name'        => 'your-vendor/your-package',
            'description' => 'A brief description of your package',
            'type'        => 'library',
            'license'     => 'MIT',
            'authors'     => [
                0 => [
                    'name'  => 'Your Name',
                    'email' => 'your-email@example.com',
                ],
            ],
            'require' => [
                'php' => '^7.4',
            ],
            'autoload' => [
                'psr-4' => [
                    'YourNamespace\\' => 'src/',
                ],
            ],
            'require-dev' => [
                'phpunit/phpunit' => '^9.5',
            ],
        ];

        $composerHelper  = new ComposerHelper();
        $composerContent = $composerHelper->getComposerContent($validComposerPath);
        $this->assertNotNull($composerContent);
        $this->assertIsArray($composerContent);
        $this->assertSame(
            expected: $validJson,
            actual: $composerContent
        );

        $this->expectException(\JsonException::class);
        $composerHelper->getComposerContent($invalidComposerPath);

        $this->expectException(InvalidComposerException::class);
        $composerHelper->getComposerContent($emptyComposerPath);
    }
}
