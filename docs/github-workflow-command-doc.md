# GitHub Workflow Command Documentation

## Introduction

The `github:workflow` command generates basic GitHub workflow templates for various actions such as PHPUnit testing, deployment to Amazon ECS, deployment to Google Kubernetes Engine, and Terraform deployment.

## Usage

`vendor/bin/whitecat github:workflow`

## Command Options

The command does not accept any additional options or arguments. It provides an interactive interface to choose the desired workflow actions.

## Command Execution

To execute the `github:workflow` command, run the following:

`vendor/bin/whitecat github:workflow`

## Generated GitHub Workflow Setup

The command performs the following tasks:

1.  Checks for the existence of the GitHub workflow directory. If it doesn't exist, it creates the directory.
2.  Allows the user to choose specific workflow actions or select "All" to generate all available workflows.
3.  Copies the selected workflow files to the specified distribution directory.

## Available Workflow Options

The user can choose from the following workflow options:

*   PHPUnit & Coverage (CodeCov)
*   Deploy to Amazon ECS
*   Deploy to Google Kubernetes Engine
*   Terraform Deploy
*   All

## Confirmation Prompts

The command prompts the user for confirmation in the following scenarios:

*   If the GitHub workflow directory already exists, the user can choose to continue and override it.
*   If any of the workflow templates already exist, the user can choose to override them.

## Command Output

Upon successful execution, the command outputs a success message.

## Exceptions

If any errors occur during file or directory creation, appropriate error messages are displayed.
