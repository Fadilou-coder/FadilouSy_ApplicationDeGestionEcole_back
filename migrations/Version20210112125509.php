<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112125509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_referentiel (promo_id INT NOT NULL, referentiel_id INT NOT NULL, INDEX IDX_638B8B6BD0C07AFF (promo_id), INDEX IDX_638B8B6B805DB139 (referentiel_id), PRIMARY KEY(promo_id, referentiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6BD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFB805DB139');
        $this->addSql('DROP INDEX IDX_B0139AFB805DB139 ON promo');
        $this->addSql('ALTER TABLE promo DROP referentiel_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promo_referentiel');
        $this->addSql('ALTER TABLE promo ADD referentiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_B0139AFB805DB139 ON promo (referentiel_id)');
    }
}
