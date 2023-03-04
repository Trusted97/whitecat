# Whitecat 🐱
![CI](https://github.com/Trusted97/whitecat/workflows/test/badge.svg)
[![codecov](https://codecov.io/gh/Trusted97/whitecat/branch/master/graph/badge.svg?token=URCWOH9JFR)](https://codecov.io/gh/Trusted97/whitecat)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/whitecat/whitecat/php)
![GitHub](https://img.shields.io/github/license/Trusted97/whitecat)
![Packagist Version](https://img.shields.io/packagist/v/whitecat/whitecat)

> Developer toolbox for avoid boring setup during development

Whitecat is a developer toolbox of cli command. Each command is thought for avoid boring 
setup in developing of PHP: Packages, Library, Composer Plugin or everything you're building! 

## Table of Contents

- [Install](#install)
  - [Usage](#usage) 
    - [Docker setup](#docker-setup)
    - [GitHub Workflow](#github-workflows) 
    - [GitHub Issue](#github-issue)  
- [Contributing](#contributing)
- [License](#license)


## Install

In composer.json

``` json
"require-dev": {
    "whitecat/whitecat": "^1.0.0"
},
```

Or

In Shell

``` sh
composer require --dev whitecat/whitecat
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

## Compatibility

| Version | Status     | PHP compatibility | 
|---------|------------|-------------------|
| 1.x     | maintained | `^8.1`            |

## Contributing

Any questions, bug reports or suggestions for improvement are very welcome. See the [contributing](./CONTRIBUTING.md) file for details on how to contribute.

## License

Whitecat is licensed under the MIT license.  
See the [LICENSE](./LICENSE) file for more information.
