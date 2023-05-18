<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpCsFixerService;

#[AsCommand(
    name: 'php-cs-fixer:init',
    description: 'Setup basic php-cs-fixer for PHP library',
    hidden: false
)]
class PhpCsFixerCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();

        return (new PhpCsFixerService($io, $fs))->run();
    }
}
