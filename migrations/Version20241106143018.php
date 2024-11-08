<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106143018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collection (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(500) NOT NULL, is_public TINYINT(1) NOT NULL, date DATETIME NOT NULL, cover VARCHAR(255) DEFAULT NULL, tags JSON NOT NULL, categorie VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL, CHANGE description description VARCHAR(500) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE emploi emploi VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE site_url site_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE collection');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE description description VARCHAR(500) DEFAULT \'NULL\', CHANGE avatar avatar VARCHAR(255) DEFAULT \'NULL\', CHANGE emploi emploi VARCHAR(255) DEFAULT \'NULL\', CHANGE telephone telephone VARCHAR(255) DEFAULT \'NULL\', CHANGE site_url site_url VARCHAR(255) DEFAULT \'NULL\'');
    }
}
