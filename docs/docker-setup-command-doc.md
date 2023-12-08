# Docker Setup Command Documentation

## Introduction

The `docker:setup` command generates a basic Docker setup for a PHP library. It creates necessary directories and copies configuration files to enable Docker support for the library.

## Usage

`vendor/bin/whitecat docker:setup`

## Command Options

The command does not accept any additional options or arguments. It is a self-contained setup process.

## Command Execution

To execute the `docker:setup` command, run the following:

`vendor/bin/whitecat docker:setup`

## Generated Docker Setup

The command performs the following tasks:

1.  Checks for the existence of the Docker directory. If it doesn't exist, it creates the directory.
2.  Copies PHP 8.1 configuration files from the Docker directory to the specified distribution directory.
3.  Copies the `docker-compose.yml` file to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the Docker directory already exists, the user can choose to continue and override it.
*   If the PHP 8.1 configuration already exists, the user can choose to override it.
*   If the `docker-compose.yml` file already exists, the user can choose to override it.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during directory or file creation, appropriate error messages are displayed.
