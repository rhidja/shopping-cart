<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004185659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE app_cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, cart_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_58917A124584665A FOREIGN KEY (product_id) REFERENCES app_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_58917A121AD5CDBF FOREIGN KEY (cart_id) REFERENCES app_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_58917A124584665A ON app_cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_58917A121AD5CDBF ON app_cart_item (cart_id)');
        $this->addSql('CREATE TABLE app_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price INTEGER NOT NULL)');
        $this->addSql('DROP TABLE app_element');
        $this->addSql('DROP TABLE app_panier');
        $this->addSql('DROP TABLE app_produit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit_id INTEGER NOT NULL, panier_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_AC1DDE74F347EFB FOREIGN KEY (produit_id) REFERENCES app_produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC1DDE74F77D927C FOREIGN KEY (panier_id) REFERENCES app_panier (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AC1DDE74F77D927C ON app_element (panier_id)');
        $this->addSql('CREATE INDEX IDX_AC1DDE74F347EFB ON app_element (produit_id)');
        $this->addSql('CREATE TABLE app_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE app_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL COLLATE "BINARY", description CLOB DEFAULT NULL COLLATE "BINARY", prix INTEGER NOT NULL)');
        $this->addSql('DROP TABLE app_cart');
        $this->addSql('DROP TABLE app_cart_item');
        $this->addSql('DROP TABLE app_product');
    }
}
