<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127093529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_4B98C21D0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_formateur (groupe_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_BDE2AD787A45358C (groupe_id), INDEX IDX_BDE2AD78155D8F51 (formateur_id), PRIMARY KEY(groupe_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C430CACFD1EC9F2B (groupe_tag_id), INDEX IDX_C430CACFBAD26311 (tag_id), PRIMARY KEY(groupe_tag_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, archiver TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences_competences (grpe_competences_id INT NOT NULL, competences_id INT NOT NULL, INDEX IDX_CEB7C77BD605601 (grpe_competences_id), INDEX IDX_CEB7C77A660B158 (competences_id), PRIMARY KEY(grpe_competences_id, competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveaux (id INT AUTO_INCREMENT NOT NULL, competences_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, groupe_action VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_56F771A0A660B158 (competences_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, reference_agate VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin_provisoire DATE NOT NULL, data_fin_reelle DATE NOT NULL, etat VARCHAR(255) NOT NULL, fabrique VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_B0139AFB805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel_grpe_competences (referentiel_id INT NOT NULL, grpe_competences_id INT NOT NULL, INDEX IDX_3A6AD9B2805DB139 (referentiel_id), INDEX IDX_3A6AD9B2BD605601 (grpe_competences_id), PRIMARY KEY(referentiel_id, grpe_competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, image LONGBLOB DEFAULT NULL, archiver TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD787A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD78155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77BD605601 FOREIGN KEY (grpe_competences_id) REFERENCES grpe_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveaux ADD CONSTRAINT FK_56F771A0A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE referentiel_grpe_competences ADD CONSTRAINT FK_3A6AD9B2805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_grpe_competences ADD CONSTRAINT FK_3A6AD9B2BD605601 FOREIGN KEY (grpe_competences_id) REFERENCES grpe_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77A660B158');
        $this->addSql('ALTER TABLE niveaux DROP FOREIGN KEY FK_56F771A0A660B158');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F95197A45358C');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD787A45358C');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77BD605601');
        $this->addSql('ALTER TABLE referentiel_grpe_competences DROP FOREIGN KEY FK_3A6AD9B2BD605601');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21D0C07AFF');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFB805DB139');
        $this->addSql('ALTER TABLE referentiel_grpe_competences DROP FOREIGN KEY FK_3A6AD9B2805DB139');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F9519C5697D6D');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD78155D8F51');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('DROP TABLE groupe_formateur');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE grpe_competences');
        $this->addSql('DROP TABLE grpe_competences_competences');
        $this->addSql('DROP TABLE niveaux');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_grpe_competences');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
