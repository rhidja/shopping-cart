<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * ExporterService.
 */
class ExporterService
{
    private $file;

    public function __construct(
        private readonly ProduitRepository $produitRepository,
        #[Autowire('%export_dir%')]
        private readonly string $exportDir
    )
    {}

    public function exporterProduits($format)
    {
        $this->openFile($format);

        $produits = $this->produitRepository->findAllOrderByNom();

        $method = 'export'.ucfirst((string) $format);
        if (method_exists($this, $method)) {
            $this->$method($produits);
        }

        fclose($this->file);
    }

    public function exportCsv($produits)
    {
        $data = [];
        foreach ($produits as $produit) {

            $data = [
                $produit->getId(),
                $produit->getNom(),
                $produit->getDescription(),
            ];

            fputcsv($this->file, $data);
        }
    }

    public function exportTxt($produits)
    {
        $data = [];
        foreach ($produits as $produit) {

            $data = [
                $produit->getId(),
                $produit->getNom(),
                $produit->getDescription(),
            ];

            $line = implode("\t", $data);

            fputs($this->file, $line."\n");
        }
    }

    public function openFile($format)
    {
        $this->file = fopen($this->exportDir."produits.$format", 'w+');;
    }
}
