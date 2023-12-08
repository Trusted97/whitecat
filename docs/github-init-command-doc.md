# GitHub Init Command Documentation

## Introduction

The `github:init` command generates a basic Github directory with starter files including `.editorconfig`, `.gitattributes`, `.gitignore`, and `README.md`.

## Usage

`vendor/bin/whitecat github:init`

## Command Options

The command does not accept any additional options or arguments. It is a self-contained setup process.

## Command Execution

To execute the `github:init` command, run the following:

`vendor/bin/whitecat github:init`

Generated GitHub Setup

The command performs the following tasks:

1.  Checks for the existence of the GitHub directory. If it doesn't exist, it creates the directory.
2.  Copies `.editorconfig`, `.gitattributes`, `.gitignore`, and `README.md` files to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the GitHub directory already exists, the user can choose to continue and override it.
*   If any of the starter files already exist, the user can choose to override them.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during file or directory creation, appropriate error messages are displayed.
