<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220123802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purshase_menu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menu_purshase (purshase_menu_id INT NOT NULL, purshase_id INT NOT NULL, INDEX IDX_E8F6813A630A85B1 (purshase_menu_id), INDEX IDX_E8F6813A29348F92 (purshase_id), PRIMARY KEY(purshase_menu_id, purshase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purshase_menu_menu (purshase_menu_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_7464A759630A85B1 (purshase_menu_id), INDEX IDX_7464A759CCD7E912 (menu_id), PRIMARY KEY(purshase_menu_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purshase_menu_purshase ADD CONSTRAINT FK_E8F6813A630A85B1 FOREIGN KEY (purshase_menu_id) REFERENCES purshase_menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_purshase ADD CONSTRAINT FK_E8F6813A29348F92 FOREIGN KEY (purshase_id) REFERENCES purshase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_menu ADD CONSTRAINT FK_7464A759630A85B1 FOREIGN KEY (purshase_menu_id) REFERENCES purshase_menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purshase_menu_menu ADD CONSTRAINT FK_7464A759CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purshase_menu_purshase DROP FOREIGN KEY FK_E8F6813A630A85B1');
        $this->addSql('ALTER TABLE purshase_menu_menu DROP FOREIGN KEY FK_7464A759630A85B1');
        $this->addSql('DROP TABLE purshase_menu');
        $this->addSql('DROP TABLE purshase_menu_purshase');
        $this->addSql('DROP TABLE purshase_menu_menu');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purshase CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
