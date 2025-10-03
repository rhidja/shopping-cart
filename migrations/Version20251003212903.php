<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003212903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_element (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit_id INTEGER NOT NULL, panier_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_AC1DDE74F347EFB FOREIGN KEY (produit_id) REFERENCES app_produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC1DDE74F77D927C FOREIGN KEY (panier_id) REFERENCES app_panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AC1DDE74F347EFB ON app_element (produit_id)');
        $this->addSql('CREATE INDEX IDX_AC1DDE74F77D927C ON app_element (panier_id)');
        $this->addSql('CREATE TABLE app_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE app_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, prix INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE app_element');
        $this->addSql('DROP TABLE app_panier');
        $this->addSql('DROP TABLE app_produit');
    }
}
