<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251008121326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, brand_id INT NOT NULL, model VARCHAR(255) NOT NULL, registration VARCHAR(255) NOT NULL, energy VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, date_first_registration VARCHAR(255) NOT NULL, preference VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_773DE69D989D9B62 (slug), INDEX IDX_773DE69DA76ED395 (user_id), INDEX IDX_773DE69D44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpooling (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, car_id INT NOT NULL, start_town VARCHAR(255) DEFAULT NULL, end_town VARCHAR(255) DEFAULT NULL, start_at DATETIME DEFAULT NULL, passenger INT DEFAULT NULL, hour TIME DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, traveltime VARCHAR(255) DEFAULT NULL, electric TINYINT(1) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, INDEX IDX_6CC153F1A76ED395 (user_id), INDEX IDX_6CC153F1C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, carpooling_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D79F6B11A76ED395 (user_id), INDEX IDX_D79F6B11AFB2200A (carpooling_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, date_birth VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE carpooling ADD CONSTRAINT FK_6CC153F1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE carpooling ADD CONSTRAINT FK_6CC153F1C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11AFB2200A FOREIGN KEY (carpooling_id) REFERENCES carpooling (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D44F5D008');
        $this->addSql('ALTER TABLE carpooling DROP FOREIGN KEY FK_6CC153F1A76ED395');
        $this->addSql('ALTER TABLE carpooling DROP FOREIGN KEY FK_6CC153F1C3C6F69F');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11A76ED395');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11AFB2200A');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE carpooling');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
