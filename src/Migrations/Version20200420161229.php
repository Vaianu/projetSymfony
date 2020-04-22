<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200420161229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE achat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, pack_jetons_id INTEGER NOT NULL, date_achat DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_26A98456FB88E14F ON achat (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_26A98456FADC8D36 ON achat (pack_jetons_id)');
        $this->addSql('CREATE TABLE enchere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit_id INTEGER NOT NULL, numero INTEGER NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_38D1870FF347EFB ON enchere (produit_id)');
        $this->addSql('CREATE TABLE historique_encheres (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, enchere_id INTEGER NOT NULL, date_enchere DATE NOT NULL, prix INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_4B517BEFFB88E14F ON historique_encheres (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_4B517BEFE80B6EFB ON historique_encheres (enchere_id)');
        $this->addSql('CREATE TABLE pack_jetons (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nbjetons INTEGER NOT NULL, description VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, descriptif VARCHAR(100) NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, niveau INTEGER NOT NULL, description VARCHAR(25) NOT NULL)');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__utilisateur AS SELECT id, email, roles, password FROM utilisateur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, pseudo VARCHAR(20) NOT NULL, CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO utilisateur (id, email, roles, password) SELECT id, email, roles, password FROM __temp__utilisateur');
        $this->addSql('DROP TABLE __temp__utilisateur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3D60322AC ON utilisateur (role_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('DROP TABLE historique_encheres');
        $this->addSql('DROP TABLE pack_jetons');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74');
        $this->addSql('DROP INDEX IDX_1D1C63B3D60322AC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__utilisateur AS SELECT id, email, roles, password FROM utilisateur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO utilisateur (id, email, roles, password) SELECT id, email, roles, password FROM __temp__utilisateur');
        $this->addSql('DROP TABLE __temp__utilisateur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }
}
