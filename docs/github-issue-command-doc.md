# GitHub Issue Command Documentation

## Introduction

The `github:issue` command generates a basic template for GitHub issues, including bug reports and feature requests.

## Usage

`vendor/bin/whitecat github:issue`

## Command Options

The command does not accept any additional options or arguments. It is a self-contained setup process.

## Command Execution

To execute the `github:issue` command, run the following:

`vendor/bin/whitecat github:issue`

## Generated GitHub Issue Setup

The command performs the following tasks:

1.  Checks for the existence of the GitHub issue directory. If it doesn't exist, it creates the directory.
2.  Copies `BUG-REPORT.yml`, `FEATURE-REQUEST.yml`, and `config.yml` files to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the GitHub issue directory already exists, the user can choose to continue and override it.
*   If any of the issue templates or the configuration file already exist, the user can choose to override them.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during file or directory creation, appropriate error messages are displayed.
