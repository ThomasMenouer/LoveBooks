<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331114238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9267B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A1B2A9267B3B43D ON books
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            CHANGE
              title title VARCHAR(255) NOT NULL,
            CHANGE
              authors authors VARCHAR(255) NOT NULL,
            CHANGE
              publisher publisher VARCHAR(255) NOT NULL,
            CHANGE
              status status VARCHAR(255) DEFAULT NULL,
            CHANGE
              users_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            ADD
              CONSTRAINT FK_4A1B2A92A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A1B2A92A76ED395 ON books (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users CHANGE name name VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users RENAME INDEX uniq_1483a5e9e7927c74 TO UNIQ_IDENTIFIER_EMAIL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              messenger_messages
            CHANGE
              created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            CHANGE
              available_at available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            CHANGE
              delivered_at delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4A1B2A92A76ED395 ON books
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            CHANGE
              title title VARCHAR(100) NOT NULL,
            CHANGE
              authors authors VARCHAR(50) NOT NULL,
            CHANGE
              publisher publisher VARCHAR(50) NOT NULL,
            CHANGE
              status status VARCHAR(15) DEFAULT NULL,
            CHANGE
              user_id users_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              books
            ADD
              CONSTRAINT FK_4A1B2A9267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON
            UPDATE
              NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4A1B2A9267B3B43D ON books (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              messenger_messages
            CHANGE
              created_at created_at DATETIME NOT NULL,
            CHANGE
              available_at available_at DATETIME NOT NULL,
            CHANGE
              delivered_at delivered_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users CHANGE name name VARCHAR(25) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users RENAME INDEX uniq_identifier_email TO UNIQ_1483A5E9E7927C74
        SQL);
    }
}
