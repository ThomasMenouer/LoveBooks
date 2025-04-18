<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418003959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_books (
              id INT AUTO_INCREMENT NOT NULL,
              user_id INT NOT NULL,
              book_id INT NOT NULL,
              pages_read INT DEFAULT 0 NOT NULL,
              status VARCHAR(20) DEFAULT 'Non lu' NOT NULL,
              user_rating INT DEFAULT NULL,
              INDEX IDX_A8D9D1CAA76ED395 (user_id),
              INDEX IDX_A8D9D1CA16A2B381 (book_id),
              UNIQUE INDEX unique_user_book (user_id, book_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_books
            ADD
              CONSTRAINT FK_A8D9D1CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_books
            ADD
              CONSTRAINT FK_A8D9D1CA16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A1B2A92A76ED395 ON books
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP user_id, DROP status, DROP pages_read, DROP user_rating
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_books DROP FOREIGN KEY FK_A8D9D1CAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_books DROP FOREIGN KEY FK_A8D9D1CA16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_books
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            ADD
              user_id INT DEFAULT NULL,
            ADD
              status VARCHAR(255) DEFAULT 'Non lu' NOT NULL,
            ADD
              pages_read INT DEFAULT 0 NOT NULL,
            ADD
              user_rating INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            ADD
              CONSTRAINT FK_4A1B2A92A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON
            UPDATE
              NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A1B2A92A76ED395 ON books (user_id)
        SQL);
    }
}
