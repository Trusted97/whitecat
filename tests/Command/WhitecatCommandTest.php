<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Console\WhitecatApplicationFactory;
use Whitecat\Test\Support\TempProject;

class WhitecatCommandTest extends TestCase
{
    use TempProject;

    protected function tearDown(): void
    {
        $this->tearDownTempProject();
    }

    /** @return iterable<string, array{string, list<string>, list<string>}> */
    public static function generatorCommands(): iterable
    {
        yield 'docker setup' => [
            'docker:setup',
            [],
            ['docker-compose.yml', 'docker/php-8.1/Dockerfile'],
        ];

        yield 'github init' => [
            'github:init',
            [],
            ['README.md', '.editorconfig', '.gitattributes', '.gitignore'],
        ];

        yield 'github issue templates' => [
            'github:issue',
            [],
            ['.github/ISSUE_TEMPLATE/BUG-REPORT.yml', '.github/ISSUE_TEMPLATE/FEATURE-REQUEST.yml', '.github/ISSUE_TEMPLATE/config.yml'],
        ];

        yield 'github pull request templates' => [
            'github:pull',
            [],
            ['.github/PULL_REQUEST_TEMPLATE/FIX.md', '.github/PULL_REQUEST_TEMPLATE/IMPROVEMENT.md', '.github/PULL_REQUEST_TEMPLATE/NEW_FEATURE.md'],
        ];
    }

    /** @param list<string> $interactiveInput @param list<string> $expectedFiles */
    #[DataProvider('generatorCommands')]
    public function testGeneratorCommandsCreateExpectedFiles(string $commandName, array $interactiveInput, array $expectedFiles): void
    {
        $project = $this->createTempProject();
        $tester  = $this->commandTester($commandName);
        $tester->setInputs($interactiveInput);

        $statusCode = $tester->execute(['--working-dir' => $project], ['capture_stderr_separately' => true]);

        $this->assertSame(Command::SUCCESS, $statusCode);
        $this->assertSame('', $tester->getErrorOutput());
        $this->assertStringContainsString('Done. Your project files are ready.', $tester->getDisplay());

        foreach ($expectedFiles as $expectedFile) {
            $this->assertFileExists($project . '/' . $expectedFile, \sprintf('Expected %s to be generated.', $expectedFile));
        }
    }

    public function testGithubWorkflowCommandCanGenerateSelectedWorkflow(): void
    {
        $project = $this->createTempProject();
        $tester  = $this->commandTester('github:workflow');
        $tester->setInputs(['0']);

        $statusCode = $tester->execute(['--working-dir' => $project], ['capture_stderr_separately' => true]);

        $this->assertSame(Command::SUCCESS, $statusCode);
        $this->assertSame('', $tester->getErrorOutput());
        $this->assertStringContainsString('Remember to add your Codecov Token in GitHub Secrets', $tester->getDisplay());
        $this->assertFileExists($project . '/.github/workflows/test.yaml');
    }

    /** @return iterable<string, array{string, array<string, mixed>, string}> */
    public static function composerAwareCommands(): iterable
    {
        yield 'php-cs-fixer' => ['php-cs-fixer:init', ['require-dev' => ['friendsofphp/php-cs-fixer' => '^3.65']], '.php-cs-fixer.dist.php'];
        yield 'phpstan' => ['phpstan:init', ['require-dev' => ['phpstan/phpstan' => '^2.0']], 'phpstan.neon'];
        yield 'phpunit' => ['phpunit:init', ['require-dev' => ['phpunit/phpunit' => '^11.0']], 'phpunit.xml'];
    }

    /** @param array<string, mixed> $composer */
    #[DataProvider('composerAwareCommands')]
    public function testComposerAwareCommandsValidateDependenciesAndCreateFiles(string $commandName, array $composer, string $expectedFile): void
    {
        $project = $this->createTempProject();
        $this->writeComposer($project, $composer);
        $tester = $this->commandTester($commandName);

        $statusCode = $tester->execute(['--working-dir' => $project], ['capture_stderr_separately' => true]);

        $this->assertSame(Command::SUCCESS, $statusCode);
        $this->assertSame('', $tester->getErrorOutput());
        $this->assertFileExists($project . '/' . $expectedFile);
    }

    public function testComposerAwareCommandFailsWithActionableMessageWhenDependencyIsMissing(): void
    {
        $project = $this->createTempProject();
        $this->writeComposer($project, ['require-dev' => []]);
        $tester = $this->commandTester('phpstan:init');

        $statusCode = $tester->execute(['--working-dir' => $project], ['capture_stderr_separately' => true]);

        $this->assertSame(Command::FAILURE, $statusCode);
        $this->assertStringContainsString('phpstan/phpstan is not installed in require-dev.', $tester->getDisplay());
        $this->assertStringContainsString('composer require --dev phpstan/phpstan', $tester->getDisplay());
        $this->assertFileDoesNotExist($project . '/phpstan.neon');
    }

    public function testComposerAwareCommandFailsWithInvalidJsonMessage(): void
    {
        $project = $this->createTempProject();
        $this->writeComposer($project, '{"require-dev":');
        $tester = $this->commandTester('phpunit:init');

        $statusCode = $tester->execute(['--working-dir' => $project], ['capture_stderr_separately' => true]);

        $this->assertSame(Command::FAILURE, $statusCode);
        $this->assertStringContainsString('composer.json is not valid JSON', $tester->getDisplay());
        $this->assertSame('', $tester->getErrorOutput());
    }

    public function testCommandHelpIncludesWorkingDirectoryOption(): void
    {
        $application = WhitecatApplicationFactory::create();
        $definition  = $application->find('github:init')->getDefinition();

        $this->assertTrue($definition->hasOption('working-dir'));
        $this->assertSame(
            'Run the command as if it was started in the given project directory.',
            $definition->getOption('working-dir')->getDescription()
        );
    }

    private function commandTester(string $commandName): CommandTester
    {
        $application = WhitecatApplicationFactory::create();
        $application->setAutoExit(false);

        return new CommandTester($application->find($commandName));
    }
}
