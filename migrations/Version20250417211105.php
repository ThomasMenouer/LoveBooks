<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417211105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            ADD
              user_rating INT DEFAULT NULL,
            ADD
              global_rating DOUBLE PRECISION DEFAULT NULL,
            CHANGE
              status status VARCHAR(255) DEFAULT 'Non lu' NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            DROP
              user_rating,
            DROP
              global_rating,
            CHANGE
              status status VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
