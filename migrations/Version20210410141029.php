<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210410141029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domaine DROP FOREIGN KEY FK_78AF0ACC4CC8505A');
        $this->addSql('DROP INDEX IDX_78AF0ACC4CC8505A ON domaine');
        $this->addSql('ALTER TABLE domaine DROP offre_id');
        $this->addSql('ALTER TABLE offre ADD domaines_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F8783410C FOREIGN KEY (domaines_id) REFERENCES domaine (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F8783410C ON offre (domaines_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domaine ADD offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE domaine ADD CONSTRAINT FK_78AF0ACC4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_78AF0ACC4CC8505A ON domaine (offre_id)');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F8783410C');
        $this->addSql('DROP INDEX IDX_AF86866F8783410C ON offre');
        $this->addSql('ALTER TABLE offre DROP domaines_id');
    }
}
