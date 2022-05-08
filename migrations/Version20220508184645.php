<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220508184645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, config_key VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX config_key_index ON config (config_key)');
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, server_id INTEGER NOT NULL, container_created DATETIME NOT NULL, container_removed DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE server (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, port INTEGER NOT NULL, container_name VARCHAR(255) NOT NULL, container_created DATETIME DEFAULT NULL, server_admin_token VARCHAR(255) DEFAULT NULL, api_key VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE server');
    }
}
