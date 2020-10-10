<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224135545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase_menu_product_product DROP FOREIGN KEY FK_2197E67C502A13C0');
        $this->addSql('DROP TABLE purshase_menu_product');
        $this->addSql('DROP TABLE purshase_menu_product_product');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_menu_product (id INT AUTO_INCREMENT NOT NULL, purshase_id INT DEFAULT NULL, formule_id INT DEFAULT NULL, INDEX IDX_B128B2AB29348F92 (purshase_id), INDEX IDX_B128B2AB2A68F4D1 (formule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE purshase_menu_product_product (purshase_menu_product_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2197E67C502A13C0 (purshase_menu_product_id), INDEX IDX_2197E67C4584665A (product_id), PRIMARY KEY(purshase_menu_product_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE purshase_menu_product ADD CONSTRAINT FK_B128B2AB29348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id)');
        $this->addSql('ALTER TABLE purshase_menu_product ADD CONSTRAINT FK_B128B2AB2A68F4D1 FOREIGN KEY (formule_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE purshase_menu_product_product ADD CONSTRAINT FK_2197E67C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_product ADD CONSTRAINT FK_2197E67C502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
