<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * ExportService.
 */
class ExportService
{
    private $file;

    public function __construct(
        private readonly ProductRepository $productRepository,
        #[Autowire('%export_dir%')]
        private readonly string $exportDir
    )
    {}

    public function exportProducts($format): void
    {
        $this->openFile($format);

        $products = $this->productRepository->findAllOrderByName();

        $method = 'export'.ucfirst((string) $format);
        if (method_exists($this, $method)) {
            $this->$method($products);
        }

        fclose($this->file);
    }

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

    public function exportTxt($products): void
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

    public function openFile($format): void
    {
        $this->file = fopen($this->exportDir."products.$format", 'w+');;
    }
}
