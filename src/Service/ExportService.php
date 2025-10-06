<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * ExportService.
 */
class ExportService
{
    private mixed $file = null;

    public function __construct(
        private readonly ProductRepository $productRepository,
        #[Autowire('%export_dir%')]
        private readonly string $exportDir
    )
    {}

    public function exportProducts(string $format): void
    {
        $this->openFile($format);

        $products = $this->productRepository->findAllOrderByName();

        $method = 'export'.ucfirst($format);
        if (method_exists($this, $method)) {
            $this->$method($products);
        }

        fclose($this->file);
    }

    /**
     * @param Product[] $products
     */
    public function exportCsv($products): void
    {
        foreach ($products as $product) {
            $data = [
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
            ];

            fputcsv($this->file, $data);
        }
    }

    /**
     * @param Product[] $products
     */
    public function exportTxt(array $products): void
    {
        foreach ($products as $product) {

            $data = [
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
            ];

            $line = implode("\t", $data);

            fputs($this->file, $line."\n");
        }
    }

    public function openFile(string $format): void
    {
        $this->file = fopen($this->exportDir."products.$format", 'w+');;
    }
}
