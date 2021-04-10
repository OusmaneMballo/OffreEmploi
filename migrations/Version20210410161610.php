<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210410161610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandeur ADD profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demandeur ADD CONSTRAINT FK_665DA613CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE INDEX IDX_665DA613CCFA12B8 ON demandeur (profile_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandeur DROP FOREIGN KEY FK_665DA613CCFA12B8');
        $this->addSql('DROP INDEX IDX_665DA613CCFA12B8 ON demandeur');
        $this->addSql('ALTER TABLE demandeur DROP profile_id');
    }
}
