<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ExporterService;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExporterServiceTest extends WebTestCase
{
    const FORMAT_CSV = 'csv';
    const FORMAT_TXT = 'txt';

    /**
     * @var \App\Repository\ProduitRepository
     */
    private $container;
    private $produitRepository;
    private $file;
    private $exportDir;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->produitRepository = $this->container->get('doctrine')
                                                   ->getManager()
                                                   ->getRepository(Produit::class);
        $this->exportDir = $this->container->getParameter('export_dir');
    }

    public function testProduitsExporter()
    {
        $produits = $this->produitRepository->findAllOrderByNom();
        $exporterService = new ExporterService($this->produitRepository, $this->container);

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

    public function exportCsv($produits)
    {
        $rows = [];
        while(!feof($this->file))
        {
            $rows[] = fgetcsv($this->file);
        }

        foreach ($produits as $produit) {
            $prod = [
                $produit->getId(),
                $produit->getNom(),
                $produit->getDescription(),
            ];
            $this->assertContains($prod, $rows);
        }
    }

    public function exportTxt($produits)
    {
        $rows = [];
        while(!feof($this->file))
        {
            $rows[] = explode("\t", str_replace("\n", "", fgets($this->file)));
        }

        foreach ($produits as $produit) {
            $prod = [
                $produit->getId(),
                $produit->getNom(),
                $produit->getDescription(),
            ];

            $this->assertContains($prod, $rows);
        }
    }

    public function openFile($format)
    {
        $this->file = fopen($this->exportDir."produits.$format", 'r');;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
