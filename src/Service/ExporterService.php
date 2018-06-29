<?php
namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ExporterService.
 */
class ExporterService
{
    private $file;
    private $exportDir;

    private $produitRepository;

    public function __construct(ProduitRepository $produitRepository, ContainerInterface $container)
    {
        $this->exportDir = $container->getParameter('export_dir');
        $this->produitRepository = $produitRepository;
    }

    public function exporterProduits($format)
    {
        $this->openFile($format);

        $produits = $this->produitRepository->findAllOrderByNom();

        $method = 'export'.ucfirst($format);
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

            $line = implode($data, "\t");

            fputs($this->file, $line."\n");
        }
    }

    public function openFile($format)
    {
        $this->file = fopen($this->exportDir."produits.$format", 'w+');;
    }
}
