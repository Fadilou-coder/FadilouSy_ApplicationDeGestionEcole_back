<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204123010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA46BF700BD');
        $this->addSql('DROP INDEX IDX_4C4C1AA46BF700BD ON etat_brief_groupe');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD status VARCHAR(255) NOT NULL, DROP status_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_brief_groupe ADD status_id INT DEFAULT NULL, DROP status');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA46BF700BD FOREIGN KEY (status_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_4C4C1AA46BF700BD ON etat_brief_groupe (status_id)');
    }
}
