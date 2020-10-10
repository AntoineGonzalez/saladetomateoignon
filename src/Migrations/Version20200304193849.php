<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304193849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_products (id INT AUTO_INCREMENT NOT NULL, purshase_id INT DEFAULT NULL, INDEX IDX_FCCA756429348F92 (purshase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purshase_products ADD CONSTRAINT FK_FCCA756429348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL, CHANGE picture picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase_menus CHANGE formule_id formule_id INT DEFAULT NULL, CHANGE purshase_id purshase_id INT DEFAULT NULL, CHANGE customer_comment customer_comment VARCHAR(1000) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE purshase_products');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL, CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase_menus CHANGE purshase_id purshase_id INT DEFAULT NULL, CHANGE formule_id formule_id INT DEFAULT NULL, CHANGE customer_comment customer_comment VARCHAR(1000) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
