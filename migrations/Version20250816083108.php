<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250816083108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_prive (id INT AUTO_INCREMENT NOT NULL, createur_id INT NOT NULL, nom VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_A8D00A9D73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant_groupe_prive (participant_id INT NOT NULL, groupe_prive_id INT NOT NULL, INDEX IDX_35F84ECF9D1C3019 (participant_id), INDEX IDX_35F84ECFEFB6D465 (groupe_prive_id), PRIMARY KEY(participant_id, groupe_prive_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_prive ADD CONSTRAINT FK_A8D00A9D73A201E5 FOREIGN KEY (createur_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE participant_groupe_prive ADD CONSTRAINT FK_35F84ECF9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_groupe_prive ADD CONSTRAINT FK_35F84ECFEFB6D465 FOREIGN KEY (groupe_prive_id) REFERENCES groupe_prive (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_prive DROP FOREIGN KEY FK_A8D00A9D73A201E5');
        $this->addSql('ALTER TABLE participant_groupe_prive DROP FOREIGN KEY FK_35F84ECF9D1C3019');
        $this->addSql('ALTER TABLE participant_groupe_prive DROP FOREIGN KEY FK_35F84ECFEFB6D465');
        $this->addSql('DROP TABLE groupe_prive');
        $this->addSql('DROP TABLE participant_groupe_prive');
    }
}
