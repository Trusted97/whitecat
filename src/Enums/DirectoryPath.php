<?php

namespace Whitecat\Enums;

enum DirectoryPath: string
{
    case DIST_DIRECTORY  = 'dist/';
    case DIST_DOCKER     = 'dist/docker/';
    case DIST_ISSUE      = 'dist/ISSUE_TEMPLATE/';
    case DIST_GITHUB     = 'dist/.github/';
    case DIST_PULL       = 'dist/PULL_REQUEST_TEMPLATE/';
    case DIST_TEST       = 'dist/tests/';
    case DIST_WORKFLOW   = 'dist/workflows/';
    case DOCKER          = 'docker/';
    case DOCKER_COMPOSE  = 'docker-compose.yml';
    case GITHUB          = '.github/';
    case ISSUE           = '.github/ISSUE_TEMPLATE/';
    case PULL            = '.github/PULL_REQUEST_TEMPLATE/';
    case TEST            = 'tests';
    case WORKFLOW        = '.github/workflows/';
}
