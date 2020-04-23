<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200423150758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_26A98456FADC8D36');
        $this->addSql('DROP INDEX IDX_26A98456FB88E14F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__achat AS SELECT id, utilisateur_id, pack_jetons_id, date_achat FROM achat');
        $this->addSql('DROP TABLE achat');
        $this->addSql('CREATE TABLE achat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, pack_jetons_id INTEGER NOT NULL, date_achat DATE NOT NULL, CONSTRAINT FK_26A98456FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_26A98456FADC8D36 FOREIGN KEY (pack_jetons_id) REFERENCES pack_jetons (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO achat (id, utilisateur_id, pack_jetons_id, date_achat) SELECT id, utilisateur_id, pack_jetons_id, date_achat FROM __temp__achat');
        $this->addSql('DROP TABLE __temp__achat');
        $this->addSql('CREATE INDEX IDX_26A98456FADC8D36 ON achat (pack_jetons_id)');
        $this->addSql('CREATE INDEX IDX_26A98456FB88E14F ON achat (utilisateur_id)');
        $this->addSql('DROP INDEX IDX_38D1870FF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__enchere AS SELECT id, produit_id, date_debut, date_fin FROM enchere');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('CREATE TABLE enchere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit_id INTEGER NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, CONSTRAINT FK_38D1870FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO enchere (id, produit_id, date_debut, date_fin) SELECT id, produit_id, date_debut, date_fin FROM __temp__enchere');
        $this->addSql('DROP TABLE __temp__enchere');
        $this->addSql('CREATE INDEX IDX_38D1870FF347EFB ON enchere (produit_id)');
        $this->addSql('DROP INDEX IDX_4B517BEFE80B6EFB');
        $this->addSql('DROP INDEX IDX_4B517BEFFB88E14F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__historique_encheres AS SELECT id, utilisateur_id, enchere_id, date_enchere, prix FROM historique_encheres');
        $this->addSql('DROP TABLE historique_encheres');
        $this->addSql('CREATE TABLE historique_encheres (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, enchere_id INTEGER NOT NULL, date_enchere DATETIME NOT NULL, prix INTEGER NOT NULL, CONSTRAINT FK_4B517BEFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B517BEFE80B6EFB FOREIGN KEY (enchere_id) REFERENCES enchere (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO historique_encheres (id, utilisateur_id, enchere_id, date_enchere, prix) SELECT id, utilisateur_id, enchere_id, date_enchere, prix FROM __temp__historique_encheres');
        $this->addSql('DROP TABLE __temp__historique_encheres');
        $this->addSql('CREATE INDEX IDX_4B517BEFE80B6EFB ON historique_encheres (enchere_id)');
        $this->addSql('CREATE INDEX IDX_4B517BEFFB88E14F ON historique_encheres (utilisateur_id)');
        $this->addSql('DROP INDEX IDX_1D1C63B3D60322AC');
        $this->addSql('DROP INDEX UNIQ_1D1C63B386CC499D');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__utilisateur AS SELECT id, role_id, email, roles, pseudo, password FROM utilisateur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , pseudo VARCHAR(20) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO utilisateur (id, role_id, email, roles, pseudo, password) SELECT id, role_id, email, roles, pseudo, password FROM __temp__utilisateur');
        $this->addSql('DROP TABLE __temp__utilisateur');
        $this->addSql('CREATE INDEX IDX_1D1C63B3D60322AC ON utilisateur (role_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B386CC499D ON utilisateur (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_26A98456FB88E14F');
        $this->addSql('DROP INDEX IDX_26A98456FADC8D36');
        $this->addSql('CREATE TEMPORARY TABLE __temp__achat AS SELECT id, utilisateur_id, pack_jetons_id, date_achat FROM achat');
        $this->addSql('DROP TABLE achat');
        $this->addSql('CREATE TABLE achat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, pack_jetons_id INTEGER NOT NULL, date_achat DATE NOT NULL)');
        $this->addSql('INSERT INTO achat (id, utilisateur_id, pack_jetons_id, date_achat) SELECT id, utilisateur_id, pack_jetons_id, date_achat FROM __temp__achat');
        $this->addSql('DROP TABLE __temp__achat');
        $this->addSql('CREATE INDEX IDX_26A98456FB88E14F ON achat (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_26A98456FADC8D36 ON achat (pack_jetons_id)');
        $this->addSql('DROP INDEX IDX_38D1870FF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__enchere AS SELECT id, produit_id, date_debut, date_fin FROM enchere');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('CREATE TABLE enchere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit_id INTEGER NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL)');
        $this->addSql('INSERT INTO enchere (id, produit_id, date_debut, date_fin) SELECT id, produit_id, date_debut, date_fin FROM __temp__enchere');
        $this->addSql('DROP TABLE __temp__enchere');
        $this->addSql('CREATE INDEX IDX_38D1870FF347EFB ON enchere (produit_id)');
        $this->addSql('DROP INDEX IDX_4B517BEFFB88E14F');
        $this->addSql('DROP INDEX IDX_4B517BEFE80B6EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__historique_encheres AS SELECT id, utilisateur_id, enchere_id, date_enchere, prix FROM historique_encheres');
        $this->addSql('DROP TABLE historique_encheres');
        $this->addSql('CREATE TABLE historique_encheres (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, enchere_id INTEGER NOT NULL, date_enchere DATETIME NOT NULL, prix INTEGER NOT NULL)');
        $this->addSql('INSERT INTO historique_encheres (id, utilisateur_id, enchere_id, date_enchere, prix) SELECT id, utilisateur_id, enchere_id, date_enchere, prix FROM __temp__historique_encheres');
        $this->addSql('DROP TABLE __temp__historique_encheres');
        $this->addSql('CREATE INDEX IDX_4B517BEFFB88E14F ON historique_encheres (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_4B517BEFE80B6EFB ON historique_encheres (enchere_id)');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74');
        $this->addSql('DROP INDEX UNIQ_1D1C63B386CC499D');
        $this->addSql('DROP INDEX IDX_1D1C63B3D60322AC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__utilisateur AS SELECT id, role_id, email, roles, pseudo, password FROM utilisateur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , pseudo VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO utilisateur (id, role_id, email, roles, pseudo, password) SELECT id, role_id, email, roles, pseudo, password FROM __temp__utilisateur');
        $this->addSql('DROP TABLE __temp__utilisateur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B386CC499D ON utilisateur (pseudo)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3D60322AC ON utilisateur (role_id)');
    }
}
