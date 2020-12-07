<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204115457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendu_brief (livrable_attendu_id INT NOT NULL, brief_id INT NOT NULL, INDEX IDX_778854ED75180ACC (livrable_attendu_id), INDEX IDX_778854ED757FABFF (brief_id), PRIMARY KEY(livrable_attendu_id, brief_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendu_brief');
    }
}
