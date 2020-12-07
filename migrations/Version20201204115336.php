<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204115336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD apprenant_id INT DEFAULT NULL, ADD livrable_attendu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E34C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E3475180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id)');
        $this->addSql('CREATE INDEX IDX_BDB84E34C5697D6D ON livrable_attendu_apprenant (apprenant_id)');
        $this->addSql('CREATE INDEX IDX_BDB84E3475180ACC ON livrable_attendu_apprenant (livrable_attendu_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E34C5697D6D');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E3475180ACC');
        $this->addSql('DROP INDEX IDX_BDB84E34C5697D6D ON livrable_attendu_apprenant');
        $this->addSql('DROP INDEX IDX_BDB84E3475180ACC ON livrable_attendu_apprenant');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP apprenant_id, DROP livrable_attendu_id');
    }
}
