<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224164518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE stock_movement (id INT AUTO_INCREMENT NOT NULL, product_id CHAR(36) NOT NULL, quantity INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BB1BC1B54584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B54584665A');
        $this->addSql('DROP TABLE stock_movement');
    }
}
