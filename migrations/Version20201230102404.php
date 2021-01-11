<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230102404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_partiel (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_partiel_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, delai DATE NOT NULL, INDEX IDX_8572D6ADC5697D6D (apprenant_id), INDEX IDX_8572D6AD519178C4 (livrable_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief (id INT AUTO_INCREMENT NOT NULL, formateur_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, nom_brief VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, modalite_pedagogique VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, modalite_evaluation VARCHAR(255) NOT NULL, image_promo LONGBLOB NOT NULL, archiver TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, etat_brouillons_assigne_valide VARCHAR(255) NOT NULL, INDEX IDX_1FBB1007155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_tag (brief_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_452A4F36757FABFF (brief_id), INDEX IDX_452A4F36BAD26311 (tag_id), PRIMARY KEY(brief_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_niveaux (brief_id INT NOT NULL, niveaux_id INT NOT NULL, INDEX IDX_DD19E6EF757FABFF (brief_id), INDEX IDX_DD19E6EFAAC4B70E (niveaux_id), PRIMARY KEY(brief_id, niveaux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, brief_ma_promo_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_DD6198EDC5697D6D (apprenant_id), INDEX IDX_DD6198ED57574C78 (brief_ma_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_ma_promo (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, brief_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6E0C4800D0C07AFF (promo_id), INDEX IDX_6E0C4800757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, piece_jointe LONGBLOB NOT NULL, INDEX IDX_659DF2AAA76ED395 (user_id), INDEX IDX_659DF2AAD0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fil_de_discution_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_67F068BC8FB20E9C (fil_de_discution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_valides (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, competences_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, referentiel_id INT DEFAULT NULL, niveau1 TINYINT(1) NOT NULL, niveau2 TINYINT(1) NOT NULL, niveau3 TINYINT(1) NOT NULL, INDEX IDX_9EEA096EC5697D6D (apprenant_id), INDEX IDX_9EEA096EA660B158 (competences_id), INDEX IDX_9EEA096ED0C07AFF (promo_id), INDEX IDX_9EEA096E805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_brief_groupe (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_4C4C1AA4757FABFF (brief_id), INDEX IDX_4C4C1AA47A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_de_discution (id INT AUTO_INCREMENT NOT NULL, apprenant_livrable_partiel_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3FF0FEA4DE88790F (apprenant_livrable_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_4B98C21D0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_formateur (groupe_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_BDE2AD787A45358C (groupe_id), INDEX IDX_BDE2AD78155D8F51 (formateur_id), PRIMARY KEY(groupe_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C430CACFD1EC9F2B (groupe_tag_id), INDEX IDX_C430CACFBAD26311 (tag_id), PRIMARY KEY(groupe_tag_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, archiver TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grpe_competences_competences (grpe_competences_id INT NOT NULL, competences_id INT NOT NULL, INDEX IDX_CEB7C77BD605601 (grpe_competences_id), INDEX IDX_CEB7C77A660B158 (competences_id), PRIMARY KEY(grpe_competences_id, competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_brief (livrable_attendu_id INT NOT NULL, brief_id INT NOT NULL, INDEX IDX_778854ED75180ACC (livrable_attendu_id), INDEX IDX_778854ED757FABFF (brief_id), PRIMARY KEY(livrable_attendu_id, brief_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_apprenant (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_attendu_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_BDB84E34C5697D6D (apprenant_id), INDEX IDX_BDB84E3475180ACC (livrable_attendu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, brief_ma_promo_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, delai DATE NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, nbre_rendu INT NOT NULL, nbre_corriger INT NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_37F072C557574C78 (brief_ma_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveaux (id INT AUTO_INCREMENT NOT NULL, competences_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, groupe_action VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_56F771A0A660B158 (competences_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profils_de_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profils_de_sortie_promo (profils_de_sortie_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_B6FE59A83ABE8DFD (profils_de_sortie_id), INDEX IDX_B6FE59A8D0C07AFF (promo_id), PRIMARY KEY(profils_de_sortie_id, promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, reference_agate VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin_provisoire DATE NOT NULL, data_fin_reelle DATE NOT NULL, etat VARCHAR(255) NOT NULL, fabrique VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, INDEX IDX_B0139AFB805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_formateur (promo_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_C5BC19F4D0C07AFF (promo_id), INDEX IDX_C5BC19F4155D8F51 (formateur_id), PRIMARY KEY(promo_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel_grpe_competences (referentiel_id INT NOT NULL, grpe_competences_id INT NOT NULL, INDEX IDX_3A6AD9B2805DB139 (referentiel_id), INDEX IDX_3A6AD9B2BD605601 (grpe_competences_id), PRIMARY KEY(referentiel_id, grpe_competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, brief_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, piece_joint LONGBLOB NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_939F4544757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, archiver TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, profils_de_sortie_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, image LONGBLOB DEFAULT NULL, archiver TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, attente TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649275ED078 (profil_id), INDEX IDX_8D93D649D0C07AFF (promo_id), INDEX IDX_8D93D6493ABE8DFD (profils_de_sortie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6ADC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6AD519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_niveaux ADD CONSTRAINT FK_DD19E6EF757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_niveaux ADD CONSTRAINT FK_DD19E6EFAAC4B70E FOREIGN KEY (niveaux_id) REFERENCES niveaux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198EDC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198ED57574C78 FOREIGN KEY (brief_ma_promo_id) REFERENCES brief_ma_promo (id)');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC8FB20E9C FOREIGN KEY (fil_de_discution_id) REFERENCES fil_de_discution (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EA660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096ED0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096E805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA4757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA47A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE fil_de_discution ADD CONSTRAINT FK_3FF0FEA4DE88790F FOREIGN KEY (apprenant_livrable_partiel_id) REFERENCES apprenant_livrable_partiel (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD787A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD78155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77BD605601 FOREIGN KEY (grpe_competences_id) REFERENCES grpe_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grpe_competences_competences ADD CONSTRAINT FK_CEB7C77A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E34C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant ADD CONSTRAINT FK_BDB84E3475180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id)');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C557574C78 FOREIGN KEY (brief_ma_promo_id) REFERENCES brief_ma_promo (id)');
        $this->addSql('ALTER TABLE niveaux ADD CONSTRAINT FK_56F771A0A660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE profils_de_sortie_promo ADD CONSTRAINT FK_B6FE59A83ABE8DFD FOREIGN KEY (profils_de_sortie_id) REFERENCES profils_de_sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profils_de_sortie_promo ADD CONSTRAINT FK_B6FE59A8D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_formateur ADD CONSTRAINT FK_C5BC19F4155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_grpe_competences ADD CONSTRAINT FK_3A6AD9B2805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_grpe_competences ADD CONSTRAINT FK_3A6AD9B2BD605601 FOREIGN KEY (grpe_competences_id) REFERENCES grpe_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493ABE8DFD FOREIGN KEY (profils_de_sortie_id) REFERENCES profils_de_sortie (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fil_de_discution DROP FOREIGN KEY FK_3FF0FEA4DE88790F');
        $this->addSql('ALTER TABLE brief_tag DROP FOREIGN KEY FK_452A4F36757FABFF');
        $this->addSql('ALTER TABLE brief_niveaux DROP FOREIGN KEY FK_DD19E6EF757FABFF');
        $this->addSql('ALTER TABLE brief_ma_promo DROP FOREIGN KEY FK_6E0C4800757FABFF');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA4757FABFF');
        $this->addSql('ALTER TABLE livrable_attendu_brief DROP FOREIGN KEY FK_778854ED757FABFF');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544757FABFF');
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198ED57574C78');
        $this->addSql('ALTER TABLE livrable_partiel DROP FOREIGN KEY FK_37F072C557574C78');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096EA660B158');
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77A660B158');
        $this->addSql('ALTER TABLE niveaux DROP FOREIGN KEY FK_56F771A0A660B158');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8FB20E9C');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA47A45358C');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F95197A45358C');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD787A45358C');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE grpe_competences_competences DROP FOREIGN KEY FK_CEB7C77BD605601');
        $this->addSql('ALTER TABLE referentiel_grpe_competences DROP FOREIGN KEY FK_3A6AD9B2BD605601');
        $this->addSql('ALTER TABLE livrable_attendu_brief DROP FOREIGN KEY FK_778854ED75180ACC');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E3475180ACC');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6AD519178C4');
        $this->addSql('ALTER TABLE brief_niveaux DROP FOREIGN KEY FK_DD19E6EFAAC4B70E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE profils_de_sortie_promo DROP FOREIGN KEY FK_B6FE59A83ABE8DFD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493ABE8DFD');
        $this->addSql('ALTER TABLE brief_ma_promo DROP FOREIGN KEY FK_6E0C4800D0C07AFF');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAD0C07AFF');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096ED0C07AFF');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21D0C07AFF');
        $this->addSql('ALTER TABLE profils_de_sortie_promo DROP FOREIGN KEY FK_B6FE59A8D0C07AFF');
        $this->addSql('ALTER TABLE promo_formateur DROP FOREIGN KEY FK_C5BC19F4D0C07AFF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D0C07AFF');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096E805DB139');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFB805DB139');
        $this->addSql('ALTER TABLE referentiel_grpe_competences DROP FOREIGN KEY FK_3A6AD9B2805DB139');
        $this->addSql('ALTER TABLE brief_tag DROP FOREIGN KEY FK_452A4F36BAD26311');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6ADC5697D6D');
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007155D8F51');
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198EDC5697D6D');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA76ED395');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096EC5697D6D');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F9519C5697D6D');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD78155D8F51');
        $this->addSql('ALTER TABLE livrable_attendu_apprenant DROP FOREIGN KEY FK_BDB84E34C5697D6D');
        $this->addSql('ALTER TABLE promo_formateur DROP FOREIGN KEY FK_C5BC19F4155D8F51');
        $this->addSql('DROP TABLE apprenant_livrable_partiel');
        $this->addSql('DROP TABLE brief');
        $this->addSql('DROP TABLE brief_tag');
        $this->addSql('DROP TABLE brief_niveaux');
        $this->addSql('DROP TABLE brief_apprenant');
        $this->addSql('DROP TABLE brief_ma_promo');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competences');
        $this->addSql('DROP TABLE competences_valides');
        $this->addSql('DROP TABLE etat_brief_groupe');
        $this->addSql('DROP TABLE fil_de_discution');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('DROP TABLE groupe_formateur');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE grpe_competences');
        $this->addSql('DROP TABLE grpe_competences_competences');
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('DROP TABLE livrable_attendu_brief');
        $this->addSql('DROP TABLE livrable_attendu_apprenant');
        $this->addSql('DROP TABLE livrable_partiel');
        $this->addSql('DROP TABLE niveaux');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profils_de_sortie');
        $this->addSql('DROP TABLE profils_de_sortie_promo');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE promo_formateur');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_grpe_competences');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
