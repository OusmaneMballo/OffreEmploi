<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402001609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT NOT NULL, offre_id INT NOT NULL, date VARCHAR(20) NOT NULL, text_motivation VARCHAR(500) DEFAULT NULL, cv VARCHAR(255) NOT NULL, INDEX IDX_2694D7A595A6EE59 (demandeur_id), INDEX IDX_2694D7A54CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demandeur (id INT AUTO_INCREMENT NOT NULL, domaine_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom_prenom VARCHAR(100) NOT NULL, age NUMERIC(10, 0) DEFAULT NULL, sexe VARCHAR(20) NOT NULL, nbr_annee_experience NUMERIC(10, 0) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, INDEX IDX_665DA6134272FC9F (domaine_id), UNIQUE INDEX UNIQ_665DA613A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_78AF0ACC4CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine_profile (domaine_id INT NOT NULL, profile_id INT NOT NULL, INDEX IDX_5D8E4E714272FC9F (domaine_id), INDEX IDX_5D8E4E71CCFA12B8 (profile_id), PRIMARY KEY(domaine_id, profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(500) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_D19FA60A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_secteur_activite (entreprise_id INT NOT NULL, secteur_activite_id INT NOT NULL, INDEX IDX_743F81E1A4AEAFEA (entreprise_id), INDEX IDX_743F81E15233A7FC (secteur_activite_id), PRIMARY KEY(entreprise_id, secteur_activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT NOT NULL, poste VARCHAR(50) NOT NULL, date_debut VARCHAR(30) NOT NULL, date_fin VARCHAR(30) DEFAULT NULL, mission VARCHAR(500) NOT NULL, organisation VARCHAR(50) NOT NULL, INDEX IDX_590C10395A6EE59 (demandeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT NOT NULL, domaine_id INT NOT NULL, nbr_annee_experience NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_EF85A2CC95A6EE59 (demandeur_id), INDEX IDX_EF85A2CC4272FC9F (domaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT NOT NULL, date_debut VARCHAR(30) NOT NULL, date_fin VARCHAR(30) NOT NULL, etablissement VARCHAR(30) NOT NULL, intitule VARCHAR(100) NOT NULL, description VARCHAR(300) DEFAULT NULL, INDEX IDX_404021BF95A6EE59 (demandeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(500) NOT NULL, niveau_etude NUMERIC(10, 0) DEFAULT NULL, nbr_annee_experience NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_AF86866FA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secteur_activite (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A54CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE demandeur ADD CONSTRAINT FK_665DA6134272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE demandeur ADD CONSTRAINT FK_665DA613A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE domaine ADD CONSTRAINT FK_78AF0ACC4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE domaine_profile ADD CONSTRAINT FK_5D8E4E714272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domaine_profile ADD CONSTRAINT FK_5D8E4E71CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entreprise_secteur_activite ADD CONSTRAINT FK_743F81E1A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_secteur_activite ADD CONSTRAINT FK_743F81E15233A7FC FOREIGN KEY (secteur_activite_id) REFERENCES secteur_activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10395A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC95A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF95A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A595A6EE59');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10395A6EE59');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC95A6EE59');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF95A6EE59');
        $this->addSql('ALTER TABLE demandeur DROP FOREIGN KEY FK_665DA6134272FC9F');
        $this->addSql('ALTER TABLE domaine_profile DROP FOREIGN KEY FK_5D8E4E714272FC9F');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC4272FC9F');
        $this->addSql('ALTER TABLE entreprise_secteur_activite DROP FOREIGN KEY FK_743F81E1A4AEAFEA');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA4AEAFEA');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A54CC8505A');
        $this->addSql('ALTER TABLE domaine DROP FOREIGN KEY FK_78AF0ACC4CC8505A');
        $this->addSql('ALTER TABLE domaine_profile DROP FOREIGN KEY FK_5D8E4E71CCFA12B8');
        $this->addSql('ALTER TABLE entreprise_secteur_activite DROP FOREIGN KEY FK_743F81E15233A7FC');
        $this->addSql('ALTER TABLE demandeur DROP FOREIGN KEY FK_665DA613A76ED395');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60A76ED395');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE demandeur');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE domaine_profile');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE entreprise_secteur_activite');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE secteur_activite');
        $this->addSql('DROP TABLE user');
    }
}
