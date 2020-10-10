<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220141825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_menu_product (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menu_product_purshase (purshase_menu_product_id INT NOT NULL, purshase_id INT NOT NULL, INDEX IDX_F0D17919502A13C0 (purshase_menu_product_id), INDEX IDX_F0D1791929348F92 (purshase_id), PRIMARY KEY(purshase_menu_product_id, purshase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menu_product_menu (purshase_menu_product_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_5B2CEABF502A13C0 (purshase_menu_product_id), INDEX IDX_5B2CEABFCCD7E912 (menu_id), PRIMARY KEY(purshase_menu_product_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menu_product_product (purshase_menu_product_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2197E67C502A13C0 (purshase_menu_product_id), INDEX IDX_2197E67C4584665A (product_id), PRIMARY KEY(purshase_menu_product_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purshase_menu_product_purshase ADD CONSTRAINT FK_F0D17919502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_purshase ADD CONSTRAINT FK_F0D1791929348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_menu ADD CONSTRAINT FK_5B2CEABF502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_menu ADD CONSTRAINT FK_5B2CEABFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_product ADD CONSTRAINT FK_2197E67C502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_product_product ADD CONSTRAINT FK_2197E67C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase_menu_product_purshase DROP FOREIGN KEY FK_F0D17919502A13C0');
        $this->addSql('ALTER TABLE purshase_menu_product_menu DROP FOREIGN KEY FK_5B2CEABF502A13C0');
        $this->addSql('ALTER TABLE purshase_menu_product_product DROP FOREIGN KEY FK_2197E67C502A13C0');
        $this->addSql('DROP TABLE purshase_menu_product');
        $this->addSql('DROP TABLE purshase_menu_product_purshase');
        $this->addSql('DROP TABLE purshase_menu_product_menu');
        $this->addSql('DROP TABLE purshase_menu_product_product');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
