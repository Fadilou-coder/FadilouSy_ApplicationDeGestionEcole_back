<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130150907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief (id INT AUTO_INCREMENT NOT NULL, langue VARCHAR(255) NOT NULL, nom_brief VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, modalite_pedagogique VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, modalite_evaluation VARCHAR(255) NOT NULL, image_promo LONGBLOB NOT NULL, archiver TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, etat_brouillons_assigne_valide VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE brief');
    }
}
