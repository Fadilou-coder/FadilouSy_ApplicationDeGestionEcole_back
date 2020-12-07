<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130145244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_partiel (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_partiel_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, delai DATE NOT NULL, INDEX IDX_8572D6ADC5697D6D (apprenant_id), INDEX IDX_8572D6AD519178C4 (livrable_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, delai DATE NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, nbre_rendu INT NOT NULL, nbre_corriger INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6ADC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6AD519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6AD519178C4');
        $this->addSql('DROP TABLE apprenant_livrable_partiel');
        $this->addSql('DROP TABLE livrable_partiel');
    }
}
