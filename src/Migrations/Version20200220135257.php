<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220135257 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE menu_product (menu_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_5B911913CCD7E912 (menu_id), INDEX IDX_5B9119134584665A (product_id), PRIMARY KEY(menu_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B911913CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B9119134584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE menu_product');
        $this->addSql('ALTER TABLE menu CHANGE sandwich_id sandwich_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
