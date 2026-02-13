<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260203133637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teste (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carpooling CHANGE start_town start_town VARCHAR(255) DEFAULT NULL, CHANGE end_town end_town VARCHAR(255) DEFAULT NULL, CHANGE start_at start_at DATETIME DEFAULT NULL, CHANGE hour hour TIME DEFAULT NULL, CHANGE traveltime traveltime VARCHAR(255) DEFAULT NULL, CHANGE note note VARCHAR(255) DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE date_birth date_birth VARCHAR(255) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE teste');
        $this->addSql('ALTER TABLE carpooling CHANGE start_town start_town VARCHAR(255) DEFAULT \'NULL\', CHANGE end_town end_town VARCHAR(255) DEFAULT \'NULL\', CHANGE start_at start_at DATETIME DEFAULT \'NULL\', CHANGE hour hour TIME DEFAULT \'NULL\', CHANGE traveltime traveltime VARCHAR(255) DEFAULT \'NULL\', CHANGE note note VARCHAR(255) DEFAULT \'NULL\', CHANGE statut statut VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE comment CHANGE comment comment VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE `user` CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(255) DEFAULT \'NULL\', CHANGE date_birth date_birth VARCHAR(255) DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\', CHANGE pseudo pseudo VARCHAR(255) DEFAULT \'NULL\', CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
