# PHP CS Fixer Initialization Command Documentation

## Introduction

The `php-cs-fixer:init` command sets up basic configuration for PHP-CS-Fixer in a PHP library.

## Usage

`vendor/bin/whitecat php-cs-fixer:init`

## Command Options

The command does not accept any additional options or arguments. It automatically detects the presence of PHP-CS-Fixer in the `require-dev` section of the `composer.json` file.

## Command Execution

To execute the `php-cs-fixer:init` command, run the following:

`vendor/bin/whitecat php-cs-fixer:init`

## Generated PHP-CS-Fixer Setup

The command performs the following tasks:

1.  Checks if PHP-CS-Fixer is installed as a development dependency.
2.  If PHP-CS-Fixer is not installed, it displays a warning message with instructions on how to install it using Composer.
3.  Copies the `.php-cs-fixer.dist.php` configuration file to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenario:

*   If the `.php-cs-fixer.dist.php` file already exists, the user can choose to override it.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during the validation or file copying process, appropriate error messages are displayed.
