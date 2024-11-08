<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107151156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, collection_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(1000) DEFAULT NULL, date DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, is_public TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_5A8A6C8D514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D514956FD FOREIGN KEY (collection_id) REFERENCES collection (id)');
        $this->addSql('ALTER TABLE collection CHANGE cover cover VARCHAR(255) DEFAULT NULL, CHANGE tags tags JSON NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL, CHANGE description description VARCHAR(500) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE emploi emploi VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE site_url site_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D514956FD');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE collection CHANGE cover cover VARCHAR(255) DEFAULT \'NULL\', CHANGE tags tags LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE description description VARCHAR(500) DEFAULT \'NULL\', CHANGE avatar avatar VARCHAR(255) DEFAULT \'NULL\', CHANGE emploi emploi VARCHAR(255) DEFAULT \'NULL\', CHANGE telephone telephone VARCHAR(255) DEFAULT \'NULL\', CHANGE site_url site_url VARCHAR(255) DEFAULT \'NULL\'');
    }
}
