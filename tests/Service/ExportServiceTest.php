<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\ProductRepository;
use App\Service\ExportService;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExportServiceTest extends WebTestCase
{
    const string FORMAT_CSV = 'csv';
    const string FORMAT_TXT = 'txt';

    private ProductRepository $productRepository;
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
                                                   ->getRepository(Product::class);
        $this->exportDir = $container->getParameter('export_dir');
    }

    public function testProduitsExporter(): void
    {
        $products = $this->produitRepository->findAllOrderByName();
        $exporterService = new ExportService($this->produitRepository, $this->exportDir);

        foreach ([self::FORMAT_CSV, self::FORMAT_TXT] as $format) {
            $exporterService->exportProducts($format);

            $method = 'export'.ucfirst($format);
            if (method_exists($this, $method)) {
                $this->openFile($format);
                $this->$method($products);
                fclose($this->file);
            }
        }
    }

    public function exportCsv($products): void
    {
        $rows = [];
        while(!feof($this->file))
        {
            $rows[] = fgetcsv($this->file);
        }

        $rows = json_encode($rows);

        /** @var Product $product */
        foreach ($products as $product) {
            static::assertStringContainsString($product->getName(), $rows);
        }    }

    public function exportTxt($products): void
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

        /** @var Product $product */
        foreach ($products as $product) {
            static::assertStringContainsString($product->getName(), $rows);
        }
    }

    public function openFile($format): void
    {
        $this->file = fopen($this->exportDir."products.$format", 'r');;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
