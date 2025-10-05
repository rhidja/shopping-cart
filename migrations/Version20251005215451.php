<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251005215451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_cart (id SERIAL NOT NULL, owner_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E8DAD177E3C61F9 ON app_cart (owner_id)');
        $this->addSql('CREATE TABLE app_cart_item (id SERIAL NOT NULL, product_id INT NOT NULL, cart_id INT NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_58917A124584665A ON app_cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_58917A121AD5CDBF ON app_cart_item (cart_id)');
        $this->addSql('CREATE TABLE app_product (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_user (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, deleted BOOLEAN DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE app_cart ADD CONSTRAINT FK_E8DAD177E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_cart_item ADD CONSTRAINT FK_58917A124584665A FOREIGN KEY (product_id) REFERENCES app_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_cart_item ADD CONSTRAINT FK_58917A121AD5CDBF FOREIGN KEY (cart_id) REFERENCES app_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_cart DROP CONSTRAINT FK_E8DAD177E3C61F9');
        $this->addSql('ALTER TABLE app_cart_item DROP CONSTRAINT FK_58917A124584665A');
        $this->addSql('ALTER TABLE app_cart_item DROP CONSTRAINT FK_58917A121AD5CDBF');
        $this->addSql('DROP TABLE app_cart');
        $this->addSql('DROP TABLE app_cart_item');
        $this->addSql('DROP TABLE app_product');
        $this->addSql('DROP TABLE app_user');
    }
}
