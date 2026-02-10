<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210130251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_supplier (product_id CHAR(36) NOT NULL, supplier_id INT NOT NULL, INDEX IDX_509A06E94584665A (product_id), INDEX IDX_509A06E92ADD6D8C (supplier_id), PRIMARY KEY(product_id, supplier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_supplier ADD CONSTRAINT FK_509A06E94584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_supplier ADD CONSTRAINT FK_509A06E92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE product CHANGE id id CHAR(36) NOT NULL, CHANGE category_id category_id char(36) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_product_category_id TO IDX_D34A04AD12469DE2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_supplier DROP FOREIGN KEY FK_509A06E94584665A');
        $this->addSql('ALTER TABLE product_supplier DROP FOREIGN KEY FK_509A06E92ADD6D8C');
        $this->addSql('DROP TABLE product_supplier');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('ALTER TABLE product CHANGE id id CHAR(36) NOT NULL, CHANGE category_id category_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad12469de2 TO IDX_product_category_id');

    }
}
