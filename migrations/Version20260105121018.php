<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105121018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea la tabla de categorías y productos con relación ManyToOne';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id char(36) NOT NULL, name VARCHAR(100) NOT NULL, created_on DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD category_id char(36) NOT NULL, CHANGE id id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_product_category_id FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_product_category_id ON product (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_product_category_id');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_product_category_id ON product');
        $this->addSql('ALTER TABLE product DROP category_id');
    }
}
