<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products =['Iphone X', 'Ipad', 'Iwatch', 'Sony Z5', 'Samsung Edge', 'Samsung Galaxy','HTC One','MacBook Pro', 'HP ZBook x2', 'AZUS ZenBook Pro', 'Lenovo Yoga 3', 'DELL XPS 13 2-en-1'];

        foreach ($products as $prod) {
            $product = new Product();
            $product->setName($prod);
            $product->setPrice(mt_rand(10, 1000));
            $product->setDescription("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.");
            $manager->persist($product);
        }

        $manager->flush();
    }
}
