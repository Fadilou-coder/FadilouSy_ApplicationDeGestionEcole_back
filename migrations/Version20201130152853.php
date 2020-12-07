<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130152853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief_tag (brief_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_452A4F36757FABFF (brief_id), INDEX IDX_452A4F36BAD26311 (tag_id), PRIMARY KEY(brief_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fil_de_discution_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_67F068BC8FB20E9C (fil_de_discution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_de_discution (id INT AUTO_INCREMENT NOT NULL, apprenant_livrale_partiel_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3FF0FEA4D33E9744 (apprenant_livrale_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC8FB20E9C FOREIGN KEY (fil_de_discution_id) REFERENCES fil_de_discution (id)');
        $this->addSql('ALTER TABLE fil_de_discution ADD CONSTRAINT FK_3FF0FEA4D33E9744 FOREIGN KEY (apprenant_livrale_partiel_id) REFERENCES apprenant_livrable_partiel (id)');
        $this->addSql('ALTER TABLE brief ADD formateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1FBB1007155D8F51 ON brief (formateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8FB20E9C');
        $this->addSql('DROP TABLE brief_tag');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE fil_de_discution');
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007155D8F51');
        $this->addSql('DROP INDEX IDX_1FBB1007155D8F51 ON brief');
        $this->addSql('ALTER TABLE brief DROP formateur_id');
    }
}
