<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\ProduitRepository;
use App\Service\ExporterService;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExporterServiceTest extends WebTestCase
{
    const string FORMAT_CSV = 'csv';
    const string FORMAT_TXT = 'txt';

    private ProduitRepository $produitRepository;
    private $file;
    private string $exportDir;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->produitRepository = $container->get('doctrine')
                                                   ->getManager()
                                                   ->getRepository(Produit::class);
        $this->exportDir = $container->getParameter('export_dir');
    }

    public function testProduitsExporter(): void
    {
        $produits = $this->produitRepository->findAllOrderByNom();
        $exporterService = new ExporterService($this->produitRepository, $this->exportDir);

        foreach ([self::FORMAT_CSV, self::FORMAT_TXT] as $format) {
            $exporterService->exporterProduits($format);

            $method = 'export'.ucfirst($format);
            if (method_exists($this, $method)) {
                $this->openFile($format);
                $this->$method($produits);
                fclose($this->file);
            }
        }
    }

    public function exportCsv($produits): void
    {
        $rows = [];
        while(!feof($this->file))
        {
            $rows[] = fgetcsv($this->file);
        }

        $rows = json_encode($rows);

        /** @var Produit $produit */
        foreach ($produits as $produit) {
//            static::assertStringContainsString($produit->getNom(), $rows);
//            static::assertStringContainsString($produit->getDescription(), $rows);
        }    }

    public function exportTxt($produits): void
    {
        $rows = [];
        while(!feof($this->file))
        {
            $line = fgets($this->file);
            if ($line === false) {
                continue;
            }

            $rows[] = explode("\t", str_replace("\n", "", $line));
        }

        $rows = json_encode($rows);

        /** @var Produit $produit */
        foreach ($produits as $produit) {
            static::assertStringContainsString($produit->getNom(), $rows);
        }
    }

    public function openFile($format): void
    {
        $this->file = fopen($this->exportDir."produits.$format", 'r');;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
