<?php

namespace Whitecat\Enums;

enum DirectoryPath: string
{
    case DIST_DOCKER    = 'dist/docker/';
    case DIST_ISSUE     = 'dist/ISSUE_TEMPLATE/';
    case DIST_WORKFLOW  = 'dist/workflows/';
    case DOCKER         = 'docker/';
    case DOCKER_COMPOSE = 'docker-compose.yml';
    case ISSUE          = '.github/ISSUE_TEMPLATE/';
    case WORKFLOW       = '.github/workflows/';
}
