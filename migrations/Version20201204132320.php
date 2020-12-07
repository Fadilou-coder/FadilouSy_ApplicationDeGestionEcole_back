<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204132320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveaux_brief (niveaux_id INT NOT NULL, brief_id INT NOT NULL, INDEX IDX_3BAD5816AAC4B70E (niveaux_id), INDEX IDX_3BAD5816757FABFF (brief_id), PRIMARY KEY(niveaux_id, brief_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE niveaux_brief ADD CONSTRAINT FK_3BAD5816AAC4B70E FOREIGN KEY (niveaux_id) REFERENCES niveaux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveaux_brief ADD CONSTRAINT FK_3BAD5816757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE niveaux_brief');
    }
}
