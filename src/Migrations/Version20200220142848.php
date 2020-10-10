<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220142848 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase ADD purshase_menu_product_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase ADD CONSTRAINT FK_1F746B9502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id)');
        $this->addSql('CREATE INDEX IDX_1F746B9502A13C0 ON purshase (purshase_menu_product_id)');
        $this->addSql('ALTER TABLE product ADD purshase_menu_product_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD502A13C0 ON product (purshase_menu_product_id)');
        $this->addSql('ALTER TABLE menu ADD purshase_menu_product_id INT DEFAULT NULL, CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93502A13C0 FOREIGN KEY (purshase_menu_product_id) REFERENCES purshase_menu_product (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93502A13C0 ON menu (purshase_menu_product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase DROP FOREIGN KEY FK_1F746B9502A13C0');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD502A13C0');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93502A13C0');
        $this->addSql('DROP TABLE purshase_menu_product');
        $this->addSql('DROP INDEX IDX_7D053A93502A13C0 ON menu');
        $this->addSql('ALTER TABLE menu DROP purshase_menu_product_id, CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_D34A04AD502A13C0 ON product');
        $this->addSql('ALTER TABLE product DROP purshase_menu_product_id, CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_1F746B9502A13C0 ON purshase');
        $this->addSql('ALTER TABLE purshase DROP purshase_menu_product_id, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
