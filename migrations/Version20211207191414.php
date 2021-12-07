<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207191414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER DEFAULT NULL, created_by INTEGER DEFAULT NULL, updated_by INTEGER DEFAULT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CDE12AB56 ON comment (created_by)');
        $this->addSql('CREATE INDEX IDX_9474526C16FE72E1 ON comment (updated_by)');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_by INTEGER DEFAULT NULL, updated_by INTEGER DEFAULT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(156) NOT NULL, description CLOB DEFAULT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DDE12AB56 ON post (created_by)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D16FE72E1 ON post (updated_by)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
    }
}
