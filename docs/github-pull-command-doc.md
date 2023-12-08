# GitHub Pull Command Documentation

## Introduction

The `github:pull` command generates basic templates for GitHub pull requests, including fix, improvement, and new feature templates.

## Usage

`vendor/bin/whitecat github:pull`

## Command Options

The command does not accept any additional options or arguments. It is a self-contained setup process.

## Command Execution

To execute the `github:pull` command, run the following:

`vendor/bin/whitecat github:pull`

## Generated GitHub Pull Request Setup

The command performs the following tasks:

1.  Checks for the existence of the GitHub pull request directory. If it doesn't exist, it creates the directory.
2.  Copies `FIX.md`, `IMPROVEMENT.md`, and `NEW_FEATURE.md` files to the specified distribution directory.

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the GitHub pull request directory already exists, the user can choose to continue and override it.
*   If any of the pull request templates already exist, the user can choose to override them.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during file or directory creation, appropriate error messages are displayed.
