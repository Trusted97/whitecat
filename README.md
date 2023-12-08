# Whitecat 🐱
![CI](https://github.com/Trusted97/whitecat/workflows/test/badge.svg)
[![codecov](https://codecov.io/gh/Trusted97/whitecat/branch/master/graph/badge.svg?token=URCWOH9JFR)](https://codecov.io/gh/Trusted97/whitecat)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/trusted97/whitecat/php)
![GitHub](https://img.shields.io/github/license/Trusted97/whitecat)
![Packagist Version](https://img.shields.io/packagist/v/trusted97/whitecat)
[![justforfunnoreally.dev badge](https://img.shields.io/badge/justforfunnoreally-dev-9ff)](https://justforfunnoreally.dev)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://phpstan.org/)
[![Maintainability](https://api.codeclimate.com/v1/badges/f3b5e0692e2d9b90efac/maintainability)](https://codeclimate.com/github/Trusted97/whitecat/maintainability)

> Developer toolbox for avoid boring setup during development

Whitecat is a developer toolbox of cli command. Each command is thought for avoid boring 
setup in developing of PHP: Packages, Library, Composer Plugin or everything you're building! 

## Table of Contents

- [Install](#install)
  - [Usage](#usage) 
    - [Docker setup](#docker-setup)
    - [Github init](#github-init)
    - [GitHub Workflow](#github-workflows) 
    - [GitHub Issue](#github-issue)  
    - [GitHub Pull](#github-pull)
    - [PHP CS Fixer](#php-cs-fixer)
    - [PHPStan](#phpstan)
    - [PHPUnit](#phpunit)
- [Contributing](#contributing)
- [License](#license)


## Install

In composer.json

``` json
"require-dev": {
    "trusted97/whitecat": "^1.0.0"
},
```

Or

In Shell

``` sh
composer require --dev trusted97/whitecat
```

### Usage

This command list all possible command available in whitecat

``` sh
vendor/bin/whitecat list
```

#### Docker setup

This command setup basic docker environment for your library

``` sh
vendor/bin/whitecat docker:setup
```

#### Github Init

This command setup basic .github directory and related files for your library

``` sh
vendor/bin/whitecat github:init
```

#### GitHub Workflows

This command setup basic workflows for GitHub through actions

``` sh
vendor/bin/whitecat github:workflow
```

#### GitHub Issue

This command setup basic issue template for your library

``` sh
vendor/bin/whitecat github:issue
```

#### GitHub Pull

This command setup basic pull request template for your library

``` sh
vendor/bin/whitecat github:pull
```

#### PHP CS Fixer

This command setup a basic PHP CS Fixer config file and check if in composer is installed

``` sh
vendor/bin/whitecat php-cs-fixer:init
```

#### PHPStan

This command setup a basic PHPStan config file with starting level of 6 and check if in composer is installed

``` sh
vendor/bin/whitecat phpstan:init
```

#### PHPUnit 

This command setup a basic PHPUnit config file and check if in composer is installed

``` sh
vendor/bin/whitecat phpunit:init
```

## Compatibility

| Repository Branch | PHP Compatibility | Status	                    | 
|-------------------|-------------------|----------------------------|
| `1.x`             | `^8.1`            | New features and bug fixes |

## Contributing

Any questions, bug reports or suggestions for improvement are very welcome. See the [contributing](./CONTRIBUTING.md) file for details on how to contribute.

## License

Whitecat is licensed under the MIT license.  
See the [LICENSE](./LICENSE) file for more information.
