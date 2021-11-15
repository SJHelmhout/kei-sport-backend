<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102130857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD selected_workout_id INT DEFAULT NULL, ADD two_factor_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649914C8B2A FOREIGN KEY (selected_workout_id) REFERENCES workout (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649914C8B2A ON user (selected_workout_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP is_active');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649914C8B2A');
        $this->addSql('DROP INDEX IDX_8D93D649914C8B2A ON user');
        $this->addSql('ALTER TABLE user DROP selected_workout_id, DROP two_factor_code');
    }
}
