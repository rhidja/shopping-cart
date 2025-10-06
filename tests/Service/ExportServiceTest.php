<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExportServiceTest extends WebTestCase
{
    public const string FORMAT_CSV = 'csv';
    public const string FORMAT_TXT = 'txt';

    private ProductRepository $productRepository;
    private mixed $file = null;
    private string $exportDir;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->productRepository = $container->get('doctrine')
                                                   ->getManager()
                                                   ->getRepository(Product::class);
        $this->exportDir = $container->getParameter('export_dir');
    }

    public function testProductsExporter(): void
    {
        $products = $this->productRepository->findAllOrderByName();
        $exporterService = new ExportService($this->productRepository, $this->exportDir);

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

    /**
     * @param Product[] $products
     */
    public function exportCsv(array $products): void
    {
        $rows = [];
        while (!feof($this->file)) {
            $rows[] = fgetcsv($this->file);
        }

        $rows = json_encode($rows);

        /** @var Product $product */
        foreach ($products as $product) {
            static::assertStringContainsString($product->getName(), $rows);
        }
    }

    /**
     * @param Product[] $products
     */
    public function exportTxt(array $products): void
    {
        $rows = [];
        while (!feof($this->file)) {
            $line = fgets($this->file);
            if (false === $line) {
                continue;
            }

            $rows[] = explode("\t", str_replace("\n", '', $line));
        }

        $rows = json_encode($rows);

        /** @var Product $product */
        foreach ($products as $product) {
            static::assertStringContainsString($product->getName(), $rows);
        }
    }

    public function openFile(string $format): void
    {
        $this->file = fopen($this->exportDir."products.$format", 'r');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
