<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190702130206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE custom_metric_datapoint (id INT AUTO_INCREMENT NOT NULL, metric_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, created INT NOT NULL, value INT NOT NULL, UNIQUE INDEX UNIQ_2A126E373F7D58D2 (hash_id), INDEX IDX_2A126E37A952D583 (metric_id), INDEX custommetricdatapoint_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, deleted_by_id INT DEFAULT NULL, created_by_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, purpose LONGTEXT NOT NULL, scheduled_for INT NOT NULL, visibility TINYINT(1) NOT NULL, created INT NOT NULL, updated INT NOT NULL, anticipated_end INT DEFAULT NULL, deleted_on INT DEFAULT NULL, UNIQUE INDEX UNIQ_2F84F8E93F7D58D2 (hash_id), INDEX IDX_2F84F8E96BF700BD (status_id), INDEX IDX_2F84F8E9C76F1F52 (deleted_by_id), INDEX IDX_2F84F8E9B03A8386 (created_by_id), INDEX maintenance_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(25) NOT NULL, email VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_A3C664D33F7D58D2 (hash_id), INDEX subscription_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_category (id INT AUTO_INCREMENT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, hint VARCHAR(255) DEFAULT NULL, created INT NOT NULL, updated INT NOT NULL, deletable TINYINT(1) NOT NULL, deleted_on INT DEFAULT NULL, editable TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FF3A42FC3F7D58D2 (hash_id), INDEX IDX_FF3A42FCC76F1F52 (deleted_by_id), INDEX servicecategory_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_update (id INT AUTO_INCREMENT NOT NULL, maintenance_id INT NOT NULL, status_id INT NOT NULL, created_by_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, message LONGTEXT NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_B2D803713F7D58D2 (hash_id), INDEX IDX_B2D80371F6C202BC (maintenance_id), INDEX IDX_B2D803716BF700BD (status_id), INDEX IDX_B2D80371B03A8386 (created_by_id), INDEX maintenanceupdate_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE google_calendar_event (id INT AUTO_INCREMENT NOT NULL, maintenance_id INT DEFAULT NULL, event_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CF125D6FF6C202BC (maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident_status (id INT AUTO_INCREMENT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, deletable TINYINT(1) NOT NULL, editable TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, deleted_on INT DEFAULT NULL, UNIQUE INDEX UNIQ_D63CD9F83F7D58D2 (hash_id), INDEX IDX_D63CD9F8C76F1F52 (deleted_by_id), INDEX incidentstatus_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, service_category_id INT DEFAULT NULL, status_id INT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created INT NOT NULL, updated INT NOT NULL, deleted_on INT DEFAULT NULL, UNIQUE INDEX UNIQ_E19D9AD23F7D58D2 (hash_id), INDEX IDX_E19D9AD2DEDCBB4E (service_category_id), INDEX IDX_E19D9AD26BF700BD (status_id), INDEX IDX_E19D9AD2C76F1F52 (deleted_by_id), INDEX service_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_metric (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created INT NOT NULL, updated INT NOT NULL, scale_start INT NOT NULL, scale_end INT NOT NULL, UNIQUE INDEX UNIQ_B37BFAE73F7D58D2 (hash_id), INDEX custommetric_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, status_id INT NOT NULL, maintenance_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_986BDF403F7D58D2 (hash_id), INDEX IDX_986BDF40ED5CA9E6 (service_id), INDEX IDX_986BDF406BF700BD (status_id), INDEX IDX_986BDF40F6C202BC (maintenance_id), INDEX maintenanceservice_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, status_id INT NOT NULL, incident_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_4657800F3F7D58D2 (hash_id), INDEX IDX_4657800FED5CA9E6 (service_id), INDEX IDX_4657800F6BF700BD (status_id), INDEX IDX_4657800F59E53FB9 (incident_id), INDEX incidentservice_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_service (id INT AUTO_INCREMENT NOT NULL, subscription_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_92887A499A1887DC (subscription_id), INDEX IDX_92887A49ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, deleted_on INT DEFAULT NULL, api_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6493F7D58D2 (hash_id), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649C76F1F52 (deleted_by_id), INDEX user_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_status_history (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, status_id INT NOT NULL, created INT NOT NULL, INDEX IDX_2C0C5A78ED5CA9E6 (service_id), INDEX IDX_2C0C5A786BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance_status (id INT AUTO_INCREMENT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, deletable TINYINT(1) NOT NULL, editable TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, deleted_on INT DEFAULT NULL, UNIQUE INDEX UNIQ_51FD53153F7D58D2 (hash_id), INDEX IDX_51FD5315C76F1F52 (deleted_by_id), INDEX maintenancestatus_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident_update (id INT AUTO_INCREMENT NOT NULL, incident_id INT NOT NULL, status_id INT NOT NULL, created_by_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, message LONGTEXT NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_3519899C3F7D58D2 (hash_id), INDEX IDX_3519899C59E53FB9 (incident_id), INDEX IDX_3519899C6BF700BD (status_id), INDEX IDX_3519899CB03A8386 (created_by_id), INDEX incidentupdate_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange_calendar_event (id INT AUTO_INCREMENT NOT NULL, maintenance_id INT DEFAULT NULL, event_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_189A8743F6C202BC (maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, type_id INT NOT NULL, deleted_by_id INT DEFAULT NULL, created_by_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, visibility TINYINT(1) NOT NULL, occurred INT NOT NULL, message LONGTEXT DEFAULT NULL, created INT NOT NULL, updated INT NOT NULL, anticipated_resolution INT DEFAULT NULL, deleted_on INT DEFAULT NULL, UNIQUE INDEX UNIQ_3D03A11A3F7D58D2 (hash_id), INDEX IDX_3D03A11A6BF700BD (status_id), INDEX IDX_3D03A11AC54C8C93 (type_id), INDEX IDX_3D03A11AC76F1F52 (deleted_by_id), INDEX IDX_3D03A11AB03A8386 (created_by_id), INDEX incident_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_status (id INT AUTO_INCREMENT NOT NULL, deleted_by_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, deletable TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, editable TINYINT(1) NOT NULL, created INT NOT NULL, updated INT NOT NULL, deleted_on INT DEFAULT NULL, metric_value INT NOT NULL, UNIQUE INDEX UNIQ_45C7602A3F7D58D2 (hash_id), INDEX IDX_45C7602AC76F1F52 (deleted_by_id), INDEX servicestatus_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(25) NOT NULL, type VARCHAR(255) NOT NULL, sortorder SMALLINT NOT NULL, attributes LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_85F91ED03F7D58D2 (hash_id), INDEX widget_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incident_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_metric_datapoint ADD CONSTRAINT FK_2A126E37A952D583 FOREIGN KEY (metric_id) REFERENCES custom_metric (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E96BF700BD FOREIGN KEY (status_id) REFERENCES maintenance_status (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service_category ADD CONSTRAINT FK_FF3A42FCC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE maintenance_update ADD CONSTRAINT FK_B2D80371F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE maintenance_update ADD CONSTRAINT FK_B2D803716BF700BD FOREIGN KEY (status_id) REFERENCES maintenance_status (id)');
        $this->addSql('ALTER TABLE maintenance_update ADD CONSTRAINT FK_B2D80371B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE google_calendar_event ADD CONSTRAINT FK_CF125D6FF6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE incident_status ADD CONSTRAINT FK_D63CD9F8C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2DEDCBB4E FOREIGN KEY (service_category_id) REFERENCES service_category (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD26BF700BD FOREIGN KEY (status_id) REFERENCES service_status (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE maintenance_service ADD CONSTRAINT FK_986BDF40ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE maintenance_service ADD CONSTRAINT FK_986BDF406BF700BD FOREIGN KEY (status_id) REFERENCES service_status (id)');
        $this->addSql('ALTER TABLE maintenance_service ADD CONSTRAINT FK_986BDF40F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE incident_service ADD CONSTRAINT FK_4657800FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE incident_service ADD CONSTRAINT FK_4657800F6BF700BD FOREIGN KEY (status_id) REFERENCES service_status (id)');
        $this->addSql('ALTER TABLE incident_service ADD CONSTRAINT FK_4657800F59E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id)');
        $this->addSql('ALTER TABLE subscription_service ADD CONSTRAINT FK_92887A499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE subscription_service ADD CONSTRAINT FK_92887A49ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service_status_history ADD CONSTRAINT FK_2C0C5A78ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE service_status_history ADD CONSTRAINT FK_2C0C5A786BF700BD FOREIGN KEY (status_id) REFERENCES service_status (id)');
        $this->addSql('ALTER TABLE maintenance_status ADD CONSTRAINT FK_51FD5315C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE incident_update ADD CONSTRAINT FK_3519899C59E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id)');
        $this->addSql('ALTER TABLE incident_update ADD CONSTRAINT FK_3519899C6BF700BD FOREIGN KEY (status_id) REFERENCES incident_status (id)');
        $this->addSql('ALTER TABLE incident_update ADD CONSTRAINT FK_3519899CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange_calendar_event ADD CONSTRAINT FK_189A8743F6C202BC FOREIGN KEY (maintenance_id) REFERENCES maintenance (id)');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A6BF700BD FOREIGN KEY (status_id) REFERENCES incident_status (id)');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AC54C8C93 FOREIGN KEY (type_id) REFERENCES incident_type (id)');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service_status ADD CONSTRAINT FK_45C7602AC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE maintenance_update DROP FOREIGN KEY FK_B2D80371F6C202BC');
        $this->addSql('ALTER TABLE google_calendar_event DROP FOREIGN KEY FK_CF125D6FF6C202BC');
        $this->addSql('ALTER TABLE maintenance_service DROP FOREIGN KEY FK_986BDF40F6C202BC');
        $this->addSql('ALTER TABLE exchange_calendar_event DROP FOREIGN KEY FK_189A8743F6C202BC');
        $this->addSql('ALTER TABLE subscription_service DROP FOREIGN KEY FK_92887A499A1887DC');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2DEDCBB4E');
        $this->addSql('ALTER TABLE incident_update DROP FOREIGN KEY FK_3519899C6BF700BD');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A6BF700BD');
        $this->addSql('ALTER TABLE maintenance_service DROP FOREIGN KEY FK_986BDF40ED5CA9E6');
        $this->addSql('ALTER TABLE incident_service DROP FOREIGN KEY FK_4657800FED5CA9E6');
        $this->addSql('ALTER TABLE subscription_service DROP FOREIGN KEY FK_92887A49ED5CA9E6');
        $this->addSql('ALTER TABLE service_status_history DROP FOREIGN KEY FK_2C0C5A78ED5CA9E6');
        $this->addSql('ALTER TABLE custom_metric_datapoint DROP FOREIGN KEY FK_2A126E37A952D583');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9C76F1F52');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9B03A8386');
        $this->addSql('ALTER TABLE service_category DROP FOREIGN KEY FK_FF3A42FCC76F1F52');
        $this->addSql('ALTER TABLE maintenance_update DROP FOREIGN KEY FK_B2D80371B03A8386');
        $this->addSql('ALTER TABLE incident_status DROP FOREIGN KEY FK_D63CD9F8C76F1F52');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2C76F1F52');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C76F1F52');
        $this->addSql('ALTER TABLE maintenance_status DROP FOREIGN KEY FK_51FD5315C76F1F52');
        $this->addSql('ALTER TABLE incident_update DROP FOREIGN KEY FK_3519899CB03A8386');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AC76F1F52');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AB03A8386');
        $this->addSql('ALTER TABLE service_status DROP FOREIGN KEY FK_45C7602AC76F1F52');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E96BF700BD');
        $this->addSql('ALTER TABLE maintenance_update DROP FOREIGN KEY FK_B2D803716BF700BD');
        $this->addSql('ALTER TABLE incident_service DROP FOREIGN KEY FK_4657800F59E53FB9');
        $this->addSql('ALTER TABLE incident_update DROP FOREIGN KEY FK_3519899C59E53FB9');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD26BF700BD');
        $this->addSql('ALTER TABLE maintenance_service DROP FOREIGN KEY FK_986BDF406BF700BD');
        $this->addSql('ALTER TABLE incident_service DROP FOREIGN KEY FK_4657800F6BF700BD');
        $this->addSql('ALTER TABLE service_status_history DROP FOREIGN KEY FK_2C0C5A786BF700BD');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AC54C8C93');
        $this->addSql('DROP TABLE custom_metric_datapoint');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP TABLE maintenance_update');
        $this->addSql('DROP TABLE google_calendar_event');
        $this->addSql('DROP TABLE incident_status');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE custom_metric');
        $this->addSql('DROP TABLE maintenance_service');
        $this->addSql('DROP TABLE incident_service');
        $this->addSql('DROP TABLE subscription_service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE service_status_history');
        $this->addSql('DROP TABLE maintenance_status');
        $this->addSql('DROP TABLE incident_update');
        $this->addSql('DROP TABLE exchange_calendar_event');
        $this->addSql('DROP TABLE incident');
        $this->addSql('DROP TABLE service_status');
        $this->addSql('DROP TABLE widget');
        $this->addSql('DROP TABLE incident_type');
        $this->addSql('DROP TABLE setting');
    }
}
