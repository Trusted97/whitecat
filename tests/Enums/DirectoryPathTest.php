<?php

namespace Whitecat\Test\Enums;

use PHPUnit\Framework\TestCase;
use Whitecat\Enums\DirectoryPath;

class DirectoryPathTest extends TestCase
{
    public function testValues(): void
    {
        $this->assertSame('dist/docker/', DirectoryPath::DIST_DOCKER->value);
        $this->assertSame('dist/ISSUE_TEMPLATE/', DirectoryPath::DIST_ISSUE->value);
        $this->assertSame('dist/.github/', DirectoryPath::DIST_GITHUB->value);
        $this->assertSame('dist/PULL_REQUEST_TEMPLATE/', DirectoryPath::DIST_PULL->value);
        $this->assertSame('dist/workflows/', DirectoryPath::DIST_WORKFLOW->value);
        $this->assertSame('docker/', DirectoryPath::DOCKER->value);
        $this->assertSame('docker-compose.yml', DirectoryPath::DOCKER_COMPOSE->value);
        $this->assertSame('.github/', DirectoryPath::GITHUB->value);
        $this->assertSame('.github/ISSUE_TEMPLATE/', DirectoryPath::ISSUE->value);
        $this->assertSame('.github/PULL_REQUEST_TEMPLATE/', DirectoryPath::PULL->value);
        $this->assertSame('.github/workflows/', DirectoryPath::WORKFLOW->value);
    }
}
