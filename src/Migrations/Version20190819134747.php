<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819134747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exchange_calendar_event');
        $this->addSql('DROP TABLE google_calendar_event');
        $this->addSql('ALTER TABLE user ADD deletable TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exchange_calendar_event (id INT AUTO_INCREMENT NOT NULL, maintenance_id INT DEFAULT NULL, event_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_189A8743F6C202BC (maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE google_calendar_event (id INT AUTO_INCREMENT NOT NULL, maintenance_id INT DEFAULT NULL, event_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_CF125D6FF6C202BC (maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exchange_calendar_event ADD CONSTRAINT FK_189A8743F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE google_calendar_event ADD CONSTRAINT FK_CF125D6FF6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE user DROP deletable');
    }
}
