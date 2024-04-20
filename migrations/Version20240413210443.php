<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413210443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE device_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE teacher_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE worker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE device (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, teacher_id INT NOT NULL, type_request_id INT DEFAULT NULL, time_start VARCHAR(255) NOT NULL, time_close VARCHAR(255) DEFAULT NULL, decription TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, cabinet VARCHAR(255) DEFAULT NULL, number_building VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9F41807E1D ON request (teacher_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F5F7D6E80 ON request (type_request_id)');
        $this->addSql('CREATE TABLE request_device (request_id INT NOT NULL, device_id INT NOT NULL, PRIMARY KEY(request_id, device_id))');
        $this->addSql('CREATE INDEX IDX_42767575427EB8A5 ON request_device (request_id)');
        $this->addSql('CREATE INDEX IDX_4276757594A4C7D4 ON request_device (device_id)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, phone VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, patronymic VARCHAR(255) DEFAULT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, department VARCHAR(255) DEFAULT NULL, faculty VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE type_request (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE worker (id INT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, post VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, patronymic VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE worker_request (worker_id INT NOT NULL, request_id INT NOT NULL, PRIMARY KEY(worker_id, request_id))');
        $this->addSql('CREATE INDEX IDX_F78F4D26B20BA36 ON worker_request (worker_id)');
        $this->addSql('CREATE INDEX IDX_F78F4D2427EB8A5 ON worker_request (request_id)');
        $this->addSql('CREATE TABLE worker_role (worker_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(worker_id, role_id))');
        $this->addSql('CREATE INDEX IDX_9CAC3C3B6B20BA36 ON worker_role (worker_id)');
        $this->addSql('CREATE INDEX IDX_9CAC3C3BD60322AC ON worker_role (role_id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F5F7D6E80 FOREIGN KEY (type_request_id) REFERENCES type_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_device ADD CONSTRAINT FK_42767575427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_device ADD CONSTRAINT FK_4276757594A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_request ADD CONSTRAINT FK_F78F4D26B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_request ADD CONSTRAINT FK_F78F4D2427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_role ADD CONSTRAINT FK_9CAC3C3B6B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE worker_role ADD CONSTRAINT FK_9CAC3C3BD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE device_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE teacher_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE worker_id_seq CASCADE');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F41807E1D');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F5F7D6E80');
        $this->addSql('ALTER TABLE request_device DROP CONSTRAINT FK_42767575427EB8A5');
        $this->addSql('ALTER TABLE request_device DROP CONSTRAINT FK_4276757594A4C7D4');
        $this->addSql('ALTER TABLE worker_request DROP CONSTRAINT FK_F78F4D26B20BA36');
        $this->addSql('ALTER TABLE worker_request DROP CONSTRAINT FK_F78F4D2427EB8A5');
        $this->addSql('ALTER TABLE worker_role DROP CONSTRAINT FK_9CAC3C3B6B20BA36');
        $this->addSql('ALTER TABLE worker_role DROP CONSTRAINT FK_9CAC3C3BD60322AC');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE request_device');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE type_request');
        $this->addSql('DROP TABLE worker');
        $this->addSql('DROP TABLE worker_request');
        $this->addSql('DROP TABLE worker_role');
    }
}
