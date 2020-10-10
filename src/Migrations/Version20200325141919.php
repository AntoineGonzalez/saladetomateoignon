<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325141919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_products (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, purshase_id INT DEFAULT NULL, qty INT NOT NULL, INDEX IDX_FCCA75644584665A (product_id), INDEX IDX_FCCA756429348F92 (purshase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE push_tokens (id INT AUTO_INCREMENT NOT NULL, token_str VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, cp VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(10) NOT NULL, fidelity_point INT NOT NULL, discord_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, delivery_hour DATETIME DEFAULT NULL, trust_score INT DEFAULT NULL, paid TINYINT(1) DEFAULT NULL, INDEX IDX_1F746B9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, stars INT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(1000) NOT NULL, date DATETIME NOT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, pic_path VARCHAR(255) NOT NULL, stock_qty INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, picture VARCHAR(255) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_ingredients (product_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_E74F8F504584665A (product_id), INDEX IDX_E74F8F503EC4DCE (ingredients_id), PRIMARY KEY(product_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, sandwich_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, picture VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description VARCHAR(1000) DEFAULT NULL, INDEX IDX_7D053A934D566043 (sandwich_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menus (id INT AUTO_INCREMENT NOT NULL, purshase_id INT DEFAULT NULL, formule_id INT DEFAULT NULL, customer_comment VARCHAR(1000) DEFAULT NULL, ingredients VARCHAR(1000) DEFAULT NULL, INDEX IDX_8484F2E29348F92 (purshase_id), INDEX IDX_8484F2E2A68F4D1 (formule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menus_product (purshase_menus_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2AC653E45B7434BA (purshase_menus_id), INDEX IDX_2AC653E44584665A (product_id), PRIMARY KEY(purshase_menus_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purshase_products ADD CONSTRAINT FK_FCCA75644584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purshase_products ADD CONSTRAINT FK_FCCA756429348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id)');
        $this->addSql('ALTER TABLE purshase ADD CONSTRAINT FK_1F746B9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_categories (id)');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F504584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F503EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A934D566043 FOREIGN KEY (sandwich_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purshase_menus ADD CONSTRAINT FK_8484F2E29348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id)');
        $this->addSql('ALTER TABLE purshase_menus ADD CONSTRAINT FK_8484F2E2A68F4D1 FOREIGN KEY (formule_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE purshase_menus_product ADD CONSTRAINT FK_2AC653E45B7434BA FOREIGN KEY (purshase_menus_id) REFERENCES purshase_menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menus_product ADD CONSTRAINT FK_2AC653E44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase DROP FOREIGN KEY FK_1F746B9A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE purshase_products DROP FOREIGN KEY FK_FCCA756429348F92');
        $this->addSql('ALTER TABLE purshase_menus DROP FOREIGN KEY FK_8484F2E29348F92');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY FK_E74F8F503EC4DCE');
        $this->addSql('ALTER TABLE purshase_products DROP FOREIGN KEY FK_FCCA75644584665A');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY FK_E74F8F504584665A');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A934D566043');
        $this->addSql('ALTER TABLE purshase_menus_product DROP FOREIGN KEY FK_2AC653E44584665A');
        $this->addSql('ALTER TABLE purshase_menus DROP FOREIGN KEY FK_8484F2E2A68F4D1');
        $this->addSql('ALTER TABLE purshase_menus_product DROP FOREIGN KEY FK_2AC653E45B7434BA');
        $this->addSql('DROP TABLE purshase_products');
        $this->addSql('DROP TABLE push_tokens');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE purshase');
        $this->addSql('DROP TABLE product_categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_ingredients');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE purshase_menus');
        $this->addSql('DROP TABLE purshase_menus_product');
    }
}
