<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109111449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circuit_log ADD workout_log_id INT NOT NULL');
        $this->addSql('ALTER TABLE circuit_log ADD CONSTRAINT FK_719B6B56F0E44248 FOREIGN KEY (workout_log_id) REFERENCES workout_log (id)');
        $this->addSql('CREATE INDEX IDX_719B6B56F0E44248 ON circuit_log (workout_log_id)');
        $this->addSql('ALTER TABLE exercise CHANGE device_id device_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_log ADD circuit_log_id INT NOT NULL');
        $this->addSql('ALTER TABLE exercise_log ADD CONSTRAINT FK_1960CDB9EFA46FE3 FOREIGN KEY (circuit_log_id) REFERENCES circuit_log (id)');
        $this->addSql('CREATE INDEX IDX_1960CDB9EFA46FE3 ON exercise_log (circuit_log_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circuit_log DROP FOREIGN KEY FK_719B6B56F0E44248');
        $this->addSql('DROP INDEX IDX_719B6B56F0E44248 ON circuit_log');
        $this->addSql('ALTER TABLE circuit_log DROP workout_log_id');
        $this->addSql('ALTER TABLE exercise CHANGE device_id device_id INT NOT NULL');
        $this->addSql('ALTER TABLE exercise_log DROP FOREIGN KEY FK_1960CDB9EFA46FE3');
        $this->addSql('DROP INDEX IDX_1960CDB9EFA46FE3 ON exercise_log');
        $this->addSql('ALTER TABLE exercise_log DROP circuit_log_id');
    }
}
