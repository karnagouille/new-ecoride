<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250909114930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_773DE69DA76ED395 ON car (user_id)');
        $this->addSql('ALTER TABLE carpooling DROP end_at, DROP price, DROP traveltime, DROP electric, DROP note, CHANGE start_town start_town VARCHAR(255) DEFAULT NULL, CHANGE end_town end_town VARCHAR(255) DEFAULT NULL, CHANGE start_at start_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('DROP INDEX IDX_773DE69DA76ED395 ON car');
        $this->addSql('ALTER TABLE car DROP user_id');
        $this->addSql('ALTER TABLE carpooling ADD end_at DATETIME NOT NULL, ADD price NUMERIC(8, 2) NOT NULL, ADD traveltime VARCHAR(255) NOT NULL, ADD electric VARCHAR(255) NOT NULL, ADD note VARCHAR(255) NOT NULL, CHANGE start_town start_town VARCHAR(255) NOT NULL, CHANGE end_town end_town VARCHAR(255) NOT NULL, CHANGE start_at start_at DATETIME NOT NULL');
    }
}
