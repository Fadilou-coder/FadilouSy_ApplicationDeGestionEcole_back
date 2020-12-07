<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130154752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_formateur (promo_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_C5BC19F4D0C07AFF (promo_id), INDEX IDX_C5BC19F4155D8F51 (formateur_id), PRIMARY KEY(promo_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D0C07AFF ON user (promo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promo_formateur');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D0C07AFF');
        $this->addSql('DROP INDEX IDX_8D93D649D0C07AFF ON user');
        $this->addSql('ALTER TABLE user DROP promo_id');
    }
}
