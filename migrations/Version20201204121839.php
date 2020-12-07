<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204121839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_brief_groupe ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA47A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_4C4C1AA47A45358C ON etat_brief_groupe (groupe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA47A45358C');
        $this->addSql('DROP INDEX IDX_4C4C1AA47A45358C ON etat_brief_groupe');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP groupe_id');
    }
}