<?php
declare(strict_types=1);

namespace App\Tests\Command;

use App\Entity\Produit;
use App\Service\ExporterService;
use App\Command\ExporterCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ExporterCommandTest extends KernelTestCase
{
    private $exporterService;

    const FORMAT_CSV = 'csv';
    const FORMAT_TXT = 'txt';

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $produitRepository = $container->get('doctrine')
                                       ->getManager()
                                       ->getRepository(Produit::class);
        $this->exporterService = new ExporterService($produitRepository, $container);
    }

    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new ExporterCommand($this->exporterService));

        $command = $application->find('app:exporter');
        $commandTester = new CommandTester($command);

        $format = 'php';

        if(!in_array($format, [self::FORMAT_CSV, self::FORMAT_TXT])){
            $commandTester->execute([
                'command'  => $command->getName(),
                'format' => $format,
            ]);

            $output = $commandTester->getDisplay();
            $this->assertContains("Oops! : le format '$format' n'est pas supporté par la commande!\n", $output);
        }
    }
}
