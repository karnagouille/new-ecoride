<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251024144333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carpooling CHANGE start_town start_town VARCHAR(255) DEFAULT NULL, CHANGE end_town end_town VARCHAR(255) DEFAULT NULL, CHANGE start_at start_at DATETIME DEFAULT NULL, CHANGE hour hour TIME DEFAULT NULL, CHANGE price price VARCHAR(255) DEFAULT NULL, CHANGE traveltime traveltime VARCHAR(255) DEFAULT NULL, CHANGE note note VARCHAR(255) DEFAULT NULL, CHANGE statut statut VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD trajet_id INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD12A823 FOREIGN KEY (trajet_id) REFERENCES carpooling (id)');
        $this->addSql('CREATE INDEX IDX_9474526CD12A823 ON comment (trajet_id)');
        $this->addSql('ALTER TABLE user CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE date_birth date_birth VARCHAR(255) DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carpooling CHANGE start_town start_town VARCHAR(255) DEFAULT \'NULL\', CHANGE end_town end_town VARCHAR(255) DEFAULT \'NULL\', CHANGE start_at start_at DATETIME DEFAULT \'NULL\', CHANGE hour hour TIME DEFAULT \'NULL\', CHANGE price price VARCHAR(255) DEFAULT \'NULL\', CHANGE traveltime traveltime VARCHAR(255) DEFAULT \'NULL\', CHANGE note note VARCHAR(255) DEFAULT \'NULL\', CHANGE statut statut VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD12A823');
        $this->addSql('DROP INDEX IDX_9474526CD12A823 ON comment');
        $this->addSql('ALTER TABLE comment DROP trajet_id, CHANGE comment comment VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE `user` CHANGE phonenumber phonenumber VARCHAR(255) DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(255) DEFAULT \'NULL\', CHANGE date_birth date_birth VARCHAR(255) DEFAULT \'NULL\', CHANGE photo photo VARCHAR(255) DEFAULT \'NULL\', CHANGE pseudo pseudo VARCHAR(255) DEFAULT \'NULL\', CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
