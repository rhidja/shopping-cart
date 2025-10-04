<?php
declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\ExporterCommand;
use App\Entity\Produit;
use App\Service\ExporterService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ExporterCommandTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $produitRepository = $container->get('doctrine')
                                       ->getManager()
                                       ->getRepository(Produit::class);
        $this->exporterService = new ExporterService($produitRepository, '');
    }

    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:exporter');
        $commandTester = new CommandTester($command);

        $format = 'php';

        if(!in_array($format, ExporterCommand::getFormats())){
            $commandTester->execute([
                'format' => $format,
            ]);

            $output = $commandTester->getDisplay();
            static::assertStringContainsString("Oops! : le format '{$format}' n'est pas supporté par la commande!\n", $output);
        }
    }
}
