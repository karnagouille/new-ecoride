<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125090845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, trajet_id INT DEFAULT NULL, statut VARCHAR(20) NOT NULL, comment VARCHAR(255) DEFAULT NULL, note VARCHAR(255) NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CC3423909 (driver_id), INDEX IDX_9474526CD12A823 (trajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD12A823 FOREIGN KEY (trajet_id) REFERENCES carpooling (id)');
        $this->addSql('ALTER TABLE credit_transaction DROP FOREIGN KEY FK_5E1DE3E1AFB2200A');
        $this->addSql('ALTER TABLE credit_transaction DROP FOREIGN KEY FK_5E1DE3E1CD53EDB6');
        $this->addSql('ALTER TABLE credit_transaction DROP FOREIGN KEY FK_5E1DE3E1F624B39D');
        $this->addSql('DROP TABLE credit_transaction');
        $this->addSql('ALTER TABLE carpooling ADD price VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP platform_credit, DROP credit, DROP is_active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit_transaction (id INT AUTO_INCREMENT NOT NULL, carpooling_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_5E1DE3E1AFB2200A (carpooling_id), INDEX IDX_5E1DE3E1F624B39D (sender_id), INDEX IDX_5E1DE3E1CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE credit_transaction ADD CONSTRAINT FK_5E1DE3E1AFB2200A FOREIGN KEY (carpooling_id) REFERENCES carpooling (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE credit_transaction ADD CONSTRAINT FK_5E1DE3E1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE credit_transaction ADD CONSTRAINT FK_5E1DE3E1F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC3423909');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD12A823');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE carpooling DROP price');
        $this->addSql('ALTER TABLE `user` ADD platform_credit DOUBLE PRECISION NOT NULL, ADD credit DOUBLE PRECISION NOT NULL, ADD is_active TINYINT(1) NOT NULL');
    }
}
