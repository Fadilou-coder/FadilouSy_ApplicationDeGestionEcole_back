<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206023139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profils_de_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD profils_de_sortie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493ABE8DFD FOREIGN KEY (profils_de_sortie_id) REFERENCES profils_de_sortie (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6493ABE8DFD ON user (profils_de_sortie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493ABE8DFD');
        $this->addSql('DROP TABLE profils_de_sortie');
        $this->addSql('DROP INDEX IDX_8D93D6493ABE8DFD ON user');
        $this->addSql('ALTER TABLE user DROP profils_de_sortie_id');
    }
}
