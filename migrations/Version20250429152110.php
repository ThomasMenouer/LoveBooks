<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429152110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE review_comments (id INT AUTO_INCREMENT NOT NULL, review_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CDC1F213E2E969B (review_id), INDEX IDX_7CDC1F21A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review_comments ADD CONSTRAINT FK_7CDC1F213E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review_comments ADD CONSTRAINT FK_7CDC1F21A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE review_comments DROP FOREIGN KEY FK_7CDC1F213E2E969B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE review_comments DROP FOREIGN KEY FK_7CDC1F21A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE review_comments
        SQL);
    }
}
