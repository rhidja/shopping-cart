<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProduitRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAllOrderByNom()
    {
        $products = $this->entityManager
            ->getRepository(Produit::class)
            ->findAllOrderByNom()
        ;

        $this->assertCount(12, $products);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
