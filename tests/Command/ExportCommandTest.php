<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ExportCommandTest extends KernelTestCase
{
    public function testExecuteSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:export');
        $commandTester = new CommandTester($command);

        $format = 'csv';
        $commandTester->execute([
            'format' => $format,
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        static::assertStringContainsString("Exporting the product list…\n", $output);
        static::assertStringContainsString("Product list export completed.\n", $output);
    }

    public function testExecuteFormatNotSupported(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:export');
        $commandTester = new CommandTester($command);

        $format = 'php';

        static::assertSame(Command::INVALID, $commandTester->execute([
            'format' => $format,
        ]));

        $output = $commandTester->getDisplay();
        static::assertStringContainsString("Oops! The format '$format' is not supported by the command!\n", $output);
    }
}
