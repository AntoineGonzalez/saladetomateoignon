<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200113181550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY FK_E74F8F504584665A');
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY FK_4B60114F4584665A');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_ingredients');
        $this->addSql('DROP TABLE products');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('DROP INDEX IDX_4B60114F4584665A ON ingredients');
        $this->addSql('ALTER TABLE ingredients DROP product_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_ingredients (product_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_E74F8F504584665A (product_id), INDEX IDX_E74F8F503EC4DCE (ingredients_id), PRIMARY KEY(product_id, ingredients_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F503EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F504584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT FK_4B60114F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_4B60114F4584665A ON ingredients (product_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
