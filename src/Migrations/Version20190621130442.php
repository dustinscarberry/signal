<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190621130442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE custom_metric_datapoint (id INT AUTO_INCREMENT NOT NULL, metric_id INT NOT NULL, created INT NOT NULL, value INT NOT NULL, INDEX IDX_2A126E37A952D583 (metric_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_metric_datapoint ADD CONSTRAINT FK_2A126E37A952D583 FOREIGN KEY (metric_id) REFERENCES custom_metric (id)');
        $this->addSql('ALTER TABLE custom_metric ADD scale_start INT NOT NULL, ADD scale_end INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE custom_metric_datapoint');
        $this->addSql('ALTER TABLE custom_metric DROP scale_start, DROP scale_end');
    }
}
