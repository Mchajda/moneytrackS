<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128145302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD category_color VARCHAR(255) DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE img_path img_path VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX expenses_user_id_index ON expense');
        $this->addSql('ALTER TABLE expense CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE date date VARCHAR(255) NOT NULL, CHANGE direction direction VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP category_color, CHANGE id id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE img_path img_path TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE expense CHANGE id id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE user_id user_id BIGINT UNSIGNED NOT NULL, CHANGE direction direction VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'expense\' NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX expenses_user_id_index ON expense (user_id)');
    }
}
