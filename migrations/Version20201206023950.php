<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206023950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profils_de_sortie_promo (profils_de_sortie_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_B6FE59A83ABE8DFD (profils_de_sortie_id), INDEX IDX_B6FE59A8D0C07AFF (promo_id), PRIMARY KEY(profils_de_sortie_id, promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profils_de_sortie_promo ADD CONSTRAINT FK_B6FE59A83ABE8DFD FOREIGN KEY (profils_de_sortie_id) REFERENCES profils_de_sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profils_de_sortie_promo ADD CONSTRAINT FK_B6FE59A8D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE profils_de_sortie_promo');
    }
}
