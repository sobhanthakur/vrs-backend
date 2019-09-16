<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190924055643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mssql', 'Migration can only be executed safely on \'mssql\'.');

        $this->addSql('ALTER TABLE TaskOffers ALTER COLUMN ServicerNotes VARCHAR(MAX)');
        $this->addSql('ALTER TABLE Servicers ALTER COLUMN DefaultToOwnerNote VARCHAR(MAX)');
        $this->addSql('ALTER TABLE Images ALTER COLUMN ImageDescription VARCHAR(MAX)');
        $this->addSql('ALTER TABLE CustomerNotifications ALTER COLUMN CustomerNotification VARCHAR(MAX)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mssql', 'Migration can only be executed safely on \'mssql\'.');

        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE CustomerNotifications ALTER COLUMN CustomerNotification VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS');
        $this->addSql('ALTER TABLE Images ALTER COLUMN ImageDescription VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS');
        $this->addSql('ALTER TABLE Servicers ALTER COLUMN DefaultToOwnerNote VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS');
        $this->addSql('ALTER TABLE TaskOffers ALTER COLUMN ServicerNotes VARCHAR(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS');
    }
}
