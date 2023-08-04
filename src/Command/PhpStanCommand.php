<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpStanService;

#[AsCommand(
    name: 'phpstan:init',
    description: 'Setup basic phpstan for PHP library',
    hidden: false
)]
class PhpStanCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();

        return (new PhpStanService($io, $fs))->run();
    }
}
