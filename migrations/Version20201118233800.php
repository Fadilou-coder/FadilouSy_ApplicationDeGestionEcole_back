<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118233800 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences ADD archiver TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE grpe_competences ADD archiver TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD image LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences DROP archiver');
        $this->addSql('ALTER TABLE grpe_competences DROP archiver');
        $this->addSql('ALTER TABLE user DROP image');
    }
}
