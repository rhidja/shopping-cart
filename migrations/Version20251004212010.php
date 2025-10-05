<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004212010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_cart ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_cart ADD COLUMN updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_cart_item ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_cart_item ADD COLUMN updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_product ADD COLUMN slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app_product ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE app_product ADD COLUMN updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_cart AS SELECT id FROM app_cart');
        $this->addSql('DROP TABLE app_cart');
        $this->addSql('CREATE TABLE app_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO app_cart (id) SELECT id FROM __temp__app_cart');
        $this->addSql('DROP TABLE __temp__app_cart');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_cart_item AS SELECT id, product_id, cart_id, quantity FROM app_cart_item');
        $this->addSql('DROP TABLE app_cart_item');
        $this->addSql('CREATE TABLE app_cart_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, cart_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_58917A124584665A FOREIGN KEY (product_id) REFERENCES app_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_58917A121AD5CDBF FOREIGN KEY (cart_id) REFERENCES app_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO app_cart_item (id, product_id, cart_id, quantity) SELECT id, product_id, cart_id, quantity FROM __temp__app_cart_item');
        $this->addSql('DROP TABLE __temp__app_cart_item');
        $this->addSql('CREATE INDEX IDX_58917A124584665A ON app_cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_58917A121AD5CDBF ON app_cart_item (cart_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__app_product AS SELECT id, name, description, price FROM app_product');
        $this->addSql('DROP TABLE app_product');
        $this->addSql('CREATE TABLE app_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price INTEGER NOT NULL)');
        $this->addSql('INSERT INTO app_product (id, name, description, price) SELECT id, name, description, price FROM __temp__app_product');
        $this->addSql('DROP TABLE __temp__app_product');
    }
}
