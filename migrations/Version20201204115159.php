<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204115159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendu DROP FOREIGN KEY FK_BA983CC9EE63D1F4');
        $this->addSql('DROP INDEX IDX_BA983CC9EE63D1F4 ON livrable_attendu');
        $this->addSql('ALTER TABLE livrable_attendu DROP livrable_attendu_apprenant_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EE63D1F4');
        $this->addSql('DROP INDEX IDX_8D93D649EE63D1F4 ON user');
        $this->addSql('ALTER TABLE user DROP livrable_attendu_apprenant_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendu ADD livrable_attendu_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendu ADD CONSTRAINT FK_BA983CC9EE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_BA983CC9EE63D1F4 ON livrable_attendu (livrable_attendu_apprenant_id)');
        $this->addSql('ALTER TABLE user ADD livrable_attendu_apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EE63D1F4 FOREIGN KEY (livrable_attendu_apprenant_id) REFERENCES livrable_attendu_apprenant (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EE63D1F4 ON user (livrable_attendu_apprenant_id)');
    }
}
