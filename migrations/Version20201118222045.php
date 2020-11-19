<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118222045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences_competences (grpe_competences_id INT NOT NULL, competences_id INT NOT NULL, INDEX IDX_CEB7C77BD605601 (grpe_competences_id), INDEX IDX_CEB7C77A660B158 (competences_id), PRIMARY KEY(grpe_competences_id, competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77BD605601 FOREIGN KEY (grpe_competences_id) REFERENCES grpe_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77A660B158');
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77BD605601');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE grpe_competences');
        $this->addSql('DROP TABLE grpe_competences_competences');
    }
}
