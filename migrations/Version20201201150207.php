<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201150207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences_valides ADD apprenant_id INT DEFAULT NULL, ADD competences_id INT DEFAULT NULL, ADD promo_id INT DEFAULT NULL, ADD referentiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096EA660B158 FOREIGN KEY (competences_id) REFERENCES competences (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096ED0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE competences_valides ADD CONSTRAINT FK_9EEA096E805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_9EEA096EC5697D6D ON competences_valides (apprenant_id)');
        $this->addSql('CREATE INDEX IDX_9EEA096EA660B158 ON competences_valides (competences_id)');
        $this->addSql('CREATE INDEX IDX_9EEA096ED0C07AFF ON competences_valides (promo_id)');
        $this->addSql('CREATE INDEX IDX_9EEA096E805DB139 ON competences_valides (referentiel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096EC5697D6D');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096EA660B158');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096ED0C07AFF');
        $this->addSql('ALTER TABLE competences_valides DROP FOREIGN KEY FK_9EEA096E805DB139');
        $this->addSql('DROP INDEX IDX_9EEA096EC5697D6D ON competences_valides');
        $this->addSql('DROP INDEX IDX_9EEA096EA660B158 ON competences_valides');
        $this->addSql('DROP INDEX IDX_9EEA096ED0C07AFF ON competences_valides');
        $this->addSql('DROP INDEX IDX_9EEA096E805DB139 ON competences_valides');
        $this->addSql('ALTER TABLE competences_valides DROP apprenant_id, DROP competences_id, DROP promo_id, DROP referentiel_id');
    }
}
