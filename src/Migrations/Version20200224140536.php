<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224140536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase_menus_content_product DROP FOREIGN KEY FK_F0B4356EEA1F11C3');
        $this->addSql('DROP TABLE purshase_menus_content');
        $this->addSql('DROP TABLE purshase_menus_content_product');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase_menus CHANGE purshase_id purshase_id INT DEFAULT NULL, CHANGE formule_id formule_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_menus_content (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE purshase_menus_content_product (purshase_menus_content_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_F0B4356E4584665A (product_id), INDEX IDX_F0B4356EEA1F11C3 (purshase_menus_content_id), PRIMARY KEY(purshase_menus_content_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE purshase_menus_content_product ADD CONSTRAINT FK_F0B4356E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menus_content_product ADD CONSTRAINT FK_F0B4356EEA1F11C3 FOREIGN KEY (purshase_menus_content_id) REFERENCES purshase_menus_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase_menus CHANGE purshase_id purshase_id INT DEFAULT NULL, CHANGE formule_id formule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
