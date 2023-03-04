<?php

namespace Whitecat\Enums;

enum DirectoryPath: string
{
    case WORKFLOW            = '.github/workflows/';
    case DIST_WORKFLOW       = 'dist/workflows/';
    case DOCKER              = 'docker/';
    case DOCKER_COMPOSE      = 'docker-compose.yml';
    case DIST_DOCKER         = 'dist/docker/';
}
