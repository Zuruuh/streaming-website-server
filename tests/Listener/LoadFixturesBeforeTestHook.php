<?php

declare(strict_types=1);

namespace App\Tests\Listener;

use PHPUnit\Runner\BeforeFirstTestHook;
use Symfony\Component\Process\Process;

final class LoadFixturesBeforeTestHook implements BeforeFirstTestHook
{
    private const COMMANDS = [
        'doctrine:migrations:up-to-date' => 'doctrine:migrations:migrate --no-interaction',
        'doctrine:fixtures:load --no-interaction --purge-with-truncate',
    ];

    public function executeBeforeFirstTest(): void
    {
        foreach (self::COMMANDS as $checker => $command) {
            if (is_string($checker)) {
                $checkerProcess = $this->prepareProcess($checker);
                $checkerProcess->run();

                if ($checkerProcess->isSuccessful()) {
                    continue;
                }
            }

            $commandProcess = $this->prepareProcess($command);
            $commandProcess->run();

            if (!$commandProcess->isSuccessful()) {
                fwrite(STDERR, $commandProcess->getErrorOutput());
                // @phpstan-ignore-next-line
                exit($commandProcess->getExitCode());
            }

            fwrite(STDOUT, "Ran command $command" . PHP_EOL);
        }
    }

    private function prepareProcess(string $command): Process
    {
        $root = realpath(__DIR__ . "/../../");
        $cli = realpath($root . '/bin/console');

        $command .= ' --env=test';
        $command = "$cli $command";
        $command = explode(' ', $command);

        return new Process($command, (string) $root);
    }
}
