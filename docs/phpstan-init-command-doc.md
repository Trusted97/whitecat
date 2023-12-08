# PHPStan Initialization Command Documentation

## Introduction

The `phpstan:init` command sets up basic configuration for PHPStan in a PHP library.

## Usage

`vendor/bin/whitecat phpstan:init`

## Command Options

The command does not accept any additional options or arguments. It automatically detects the presence of PHPStan in the `require-dev` section of the `composer.json` file.

## Command Execution

To execute the `phpstan:init` command, run the following:

`vendor/bin/whitecat phpstan:init`

## Generated PHPStan Setup

The command performs the following tasks:

1.  Checks if PHPStan is installed as a development dependency.
2.  If PHPStan is not installed, it displays a warning message with instructions on how to install it using Composer.
3.  Copies the `phpstan.neon` configuration file to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenario:

*   If the `phpstan.neon` file already exists, the user can choose to override it.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during the validation or file copying process, appropriate error messages are displayed.
