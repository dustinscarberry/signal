<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701201544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE google_calendar_event DROP INDEX IDX_CF125D6FF6C202BC, ADD UNIQUE INDEX UNIQ_CF125D6FF6C202BC (maintenance_id)');
        $this->addSql('ALTER TABLE google_calendar_event CHANGE maintenance_id maintenance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exchange_calendar_event DROP INDEX IDX_189A8743F6C202BC, ADD UNIQUE INDEX UNIQ_189A8743F6C202BC (maintenance_id)');
        $this->addSql('ALTER TABLE exchange_calendar_event CHANGE maintenance_id maintenance_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exchange_calendar_event DROP INDEX UNIQ_189A8743F6C202BC, ADD INDEX IDX_189A8743F6C202BC (maintenance_id)');
        $this->addSql('ALTER TABLE exchange_calendar_event CHANGE maintenance_id maintenance_id INT NOT NULL');
        $this->addSql('ALTER TABLE google_calendar_event DROP INDEX UNIQ_CF125D6FF6C202BC, ADD INDEX IDX_CF125D6FF6C202BC (maintenance_id)');
        $this->addSql('ALTER TABLE google_calendar_event CHANGE maintenance_id maintenance_id INT NOT NULL');
    }
}
