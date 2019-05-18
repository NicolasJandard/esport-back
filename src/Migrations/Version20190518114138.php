<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518114138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_google_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pokemon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE user_google');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE team');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_google_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pokemon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(255) NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, participants INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_google (id INT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, name VARCHAR(20) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, passive VARCHAR(20) DEFAULT NULL, ability_one VARCHAR(20) DEFAULT NULL, ability_two VARCHAR(20) DEFAULT NULL, ability_three VARCHAR(20) DEFAULT NULL, ability_four VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE team (id INT NOT NULL, pokemon_one INT DEFAULT NULL, pokemon_two INT DEFAULT NULL, pokemon_three INT DEFAULT NULL, pokemon_four INT DEFAULT NULL, pokemon_five INT DEFAULT NULL, pokemon_six INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, tier VARCHAR(20) DEFAULT NULL, creator INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_google_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pokemon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE user_google');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE team');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_google_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pokemon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(255) NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, participants INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_google (id INT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, name VARCHAR(20) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, passive VARCHAR(20) DEFAULT NULL, ability_one VARCHAR(20) DEFAULT NULL, ability_two VARCHAR(20) DEFAULT NULL, ability_three VARCHAR(20) DEFAULT NULL, ability_four VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE team (id INT NOT NULL, pokemon_one INT DEFAULT NULL, pokemon_two INT DEFAULT NULL, pokemon_three INT DEFAULT NULL, pokemon_four INT DEFAULT NULL, pokemon_five INT DEFAULT NULL, pokemon_six INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, tier VARCHAR(20) DEFAULT NULL, creator INT NOT NULL, PRIMARY KEY(id))');
    }
}
