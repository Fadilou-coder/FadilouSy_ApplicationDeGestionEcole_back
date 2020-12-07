<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130152112 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, brief_ma_promo_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_DD6198EDC5697D6D (apprenant_id), INDEX IDX_DD6198ED57574C78 (brief_ma_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_ma_promo (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, brief_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6E0C4800D0C07AFF (promo_id), INDEX IDX_6E0C4800757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198EDC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198ED57574C78 FOREIGN KEY (brief_ma_promo_id) REFERENCES brief_ma_promo (id)');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE livrable_partiel ADD brief_ma_promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C557574C78 FOREIGN KEY (brief_ma_promo_id) REFERENCES brief_ma_promo (id)');
        $this->addSql('CREATE INDEX IDX_37F072C557574C78 ON livrable_partiel (brief_ma_promo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198ED57574C78');
        $this->addSql('ALTER TABLE livrable_partiel DROP FOREIGN KEY FK_37F072C557574C78');
        $this->addSql('DROP TABLE brief_apprenant');
        $this->addSql('DROP TABLE brief_ma_promo');
        $this->addSql('DROP INDEX IDX_37F072C557574C78 ON livrable_partiel');
        $this->addSql('ALTER TABLE livrable_partiel DROP brief_ma_promo_id');
    }
}
