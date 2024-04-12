<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412142741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD quantity INT NOT NULL, ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14B78418989D9B62 ON photo (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP quantity, DROP price');
        $this->addSql('DROP INDEX UNIQ_14B78418989D9B62 ON photo');
        $this->addSql('ALTER TABLE photo CHANGE created_at created_at DATETIME DEFAULT \'2024-04-09 00:00:00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
