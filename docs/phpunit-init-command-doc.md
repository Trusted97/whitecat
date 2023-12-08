# PHPUnit Initialization Command Documentation

## Introduction

The `phpunit:init` command sets up basic configuration for PHPUnit in a PHP library.

## Usage

`vendor/bin/whitecat phpunit:init`

## Command Options

The command does not accept any additional options or arguments. It automatically detects the presence of PHPUnit in the `require-dev` section of the `composer.json` file.

## Command Execution

To execute the `phpunit:init` command, run the following:

`vendor/bin/whitecat phpunit:init`

## Generated PHPUnit Setup

The command performs the following tasks:

1.  Checks if PHPUnit is installed as a development dependency.
2.  If PHPUnit is not installed, it displays a warning message with instructions on how to install it using Composer.
3.  Copies the `phpunit.xml` configuration file to the specified distribution directory.
4.  Copies the PHPUnit configuration directory to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the `phpunit.xml` file already exists, the user can choose to override it.
*   If the PHPUnit configuration directory already exists, the user can choose to override it.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during the validation or file copying process, appropriate error messages are displayed.
