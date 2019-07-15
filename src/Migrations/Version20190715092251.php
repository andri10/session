<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190715092251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE duree (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, formation_id INT DEFAULT NULL, nb_jour INT NOT NULL, INDEX IDX_8456C035AFC2B591 (module_id), INDEX IDX_8456C0355200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_naissance DATETIME NOT NULL, sexe VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, telephone VARCHAR(50) NOT NULL, avatar VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur_categorie (formateur_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_5B796C83155D8F51 (formateur_id), INDEX IDX_5B796C83BCF5E72D (categorie_id), PRIMARY KEY(formateur_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, nb_place INT NOT NULL, date_debut DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, intitule VARCHAR(100) NOT NULL, INDEX IDX_C242628BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(100) NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource_formation (ressource_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_2608ABCCFC6CD52A (ressource_id), INDEX IDX_2608ABCC5200282E (formation_id), PRIMARY KEY(ressource_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_naissance DATETIME NOT NULL, sexe VARCHAR(50) NOT NULL, ville VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, telephone VARCHAR(50) NOT NULL, avatar VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaire_formation (stagiaire_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_58F765A1BBA93DD6 (stagiaire_id), INDEX IDX_58F765A15200282E (formation_id), PRIMARY KEY(stagiaire_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE duree ADD CONSTRAINT FK_8456C035AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE duree ADD CONSTRAINT FK_8456C0355200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE formateur_categorie ADD CONSTRAINT FK_5B796C83155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_categorie ADD CONSTRAINT FK_5B796C83BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ressource_formation ADD CONSTRAINT FK_2608ABCCFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_formation ADD CONSTRAINT FK_2608ABCC5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_formation ADD CONSTRAINT FK_58F765A1BBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_formation ADD CONSTRAINT FK_58F765A15200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE formateur_categorie DROP FOREIGN KEY FK_5B796C83BCF5E72D');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628BCF5E72D');
        $this->addSql('ALTER TABLE formateur_categorie DROP FOREIGN KEY FK_5B796C83155D8F51');
        $this->addSql('ALTER TABLE duree DROP FOREIGN KEY FK_8456C0355200282E');
        $this->addSql('ALTER TABLE ressource_formation DROP FOREIGN KEY FK_2608ABCC5200282E');
        $this->addSql('ALTER TABLE stagiaire_formation DROP FOREIGN KEY FK_58F765A15200282E');
        $this->addSql('ALTER TABLE duree DROP FOREIGN KEY FK_8456C035AFC2B591');
        $this->addSql('ALTER TABLE ressource_formation DROP FOREIGN KEY FK_2608ABCCFC6CD52A');
        $this->addSql('ALTER TABLE stagiaire_formation DROP FOREIGN KEY FK_58F765A1BBA93DD6');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE duree');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formateur_categorie');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE ressource_formation');
        $this->addSql('DROP TABLE stagiaire');
        $this->addSql('DROP TABLE stagiaire_formation');
    }
}
