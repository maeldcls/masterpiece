<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124004605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer CHANGE company name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game ADD metacritics INT NOT NULL, ADD background_image VARCHAR(255) NOT NULL, ADD parent_platform LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD game_id INT NOT NULL, CHANGE screenshots screenshots JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE game_user DROP FOREIGN KEY FK_6686BA654D77E7D8');
        $this->addSql('ALTER TABLE game_user DROP FOREIGN KEY FK_6686BA659D86650F');
        $this->addSql('DROP INDEX IDX_6686BA654D77E7D8 ON game_user');
        $this->addSql('DROP INDEX IDX_6686BA659D86650F ON game_user');
        $this->addSql('ALTER TABLE game_user ADD user_id INT NOT NULL, ADD game_id INT NOT NULL, DROP user_id_id, DROP game_id_id');
        $this->addSql('ALTER TABLE game_user ADD CONSTRAINT FK_6686BA65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_user ADD CONSTRAINT FK_6686BA65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_6686BA65A76ED395 ON game_user (user_id)');
        $this->addSql('CREATE INDEX IDX_6686BA65E48FD905 ON game_user (game_id)');
        $this->addSql('ALTER TABLE genre CHANGE genre name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE platform ADD api_id INT NOT NULL, CHANGE platform name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE publisher CHANGE company name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tag CHANGE tag name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE tag CHANGE name tag VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE developer CHANGE name company VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game_user DROP FOREIGN KEY FK_6686BA65A76ED395');
        $this->addSql('ALTER TABLE game_user DROP FOREIGN KEY FK_6686BA65E48FD905');
        $this->addSql('DROP INDEX IDX_6686BA65A76ED395 ON game_user');
        $this->addSql('DROP INDEX IDX_6686BA65E48FD905 ON game_user');
        $this->addSql('ALTER TABLE game_user ADD user_id_id INT NOT NULL, ADD game_id_id INT NOT NULL, DROP user_id, DROP game_id');
        $this->addSql('ALTER TABLE game_user ADD CONSTRAINT FK_6686BA654D77E7D8 FOREIGN KEY (game_id_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE game_user ADD CONSTRAINT FK_6686BA659D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6686BA654D77E7D8 ON game_user (game_id_id)');
        $this->addSql('CREATE INDEX IDX_6686BA659D86650F ON game_user (user_id_id)');
        $this->addSql('ALTER TABLE publisher CHANGE name company VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game DROP metacritics, DROP background_image, DROP parent_platform, DROP game_id, CHANGE screenshots screenshots VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE platform DROP api_id, CHANGE name platform VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name genre VARCHAR(255) NOT NULL');
    }
}
