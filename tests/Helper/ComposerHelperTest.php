<?php

namespace Whitecat\Test\Helper;

use PHPUnit\Framework\TestCase;
use Whitecat\Exception\InvalidComposerException;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Test\Support\TempProject;

class ComposerHelperTest extends TestCase
{
    use TempProject;

    protected function tearDown(): void
    {
        $this->tearDownTempProject();
    }

    public function testReadsComposerJsonAsAssociativeArray(): void
    {
        $project = $this->createTempProject();
        $this->writeComposer($project, ['name' => 'vendor/package', 'require-dev' => ['phpunit/phpunit' => '^11']]);

        $composer = (new ComposerHelper())->getComposerContent($project . '/composer.json');

        $this->assertSame('vendor/package', $composer['name']);
        $this->assertSame('^11', $composer['require-dev']['phpunit/phpunit']);
    }

    public function testEmptyComposerFileIsRejected(): void
    {
        $project = $this->createTempProject();
        \file_put_contents($project . '/composer.json', '');

        $this->expectException(InvalidComposerException::class);
        $this->expectExceptionMessage('Unable to read a valid composer.json file');

        (new ComposerHelper())->getComposerContent($project . '/composer.json');
    }

    public function testJsonArrayComposerFileIsRejected(): void
    {
        $project = $this->createTempProject();
        \file_put_contents($project . '/composer.json', '[]');

        $this->expectException(InvalidComposerException::class);

        (new ComposerHelper())->getComposerContent($project . '/composer.json');
    }
}
