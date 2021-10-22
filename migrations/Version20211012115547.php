<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012115547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fixed relations between tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session_user (session_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4BE2D663613FECDF (session_id), INDEX IDX_4BE2D663A76ED395 (user_id), PRIMARY KEY(session_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session_user ADD CONSTRAINT FK_4BE2D663613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_user ADD CONSTRAINT FK_4BE2D663A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE circuit ADD workout_id INT NOT NULL, DROP exercises');
        $this->addSql('ALTER TABLE circuit ADD CONSTRAINT FK_1325F3A6A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('CREATE INDEX IDX_1325F3A6A6CCCFC9 ON circuit (workout_id)');
        $this->addSql('ALTER TABLE circuit_log ADD user_id INT NOT NULL, ADD circuit_id INT NOT NULL, DROP user, DROP circuit');
        $this->addSql('ALTER TABLE circuit_log ADD CONSTRAINT FK_719B6B56A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE circuit_log ADD CONSTRAINT FK_719B6B56CF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id)');
        $this->addSql('CREATE INDEX IDX_719B6B56A76ED395 ON circuit_log (user_id)');
        $this->addSql('CREATE INDEX IDX_719B6B56CF2182C8 ON circuit_log (circuit_id)');
        $this->addSql('ALTER TABLE exercise ADD circuit_id INT NOT NULL, ADD device_id INT NOT NULL, DROP device');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51CCF2182C8 FOREIGN KEY (circuit_id) REFERENCES circuit (id)');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('CREATE INDEX IDX_AEDAD51CCF2182C8 ON exercise (circuit_id)');
        $this->addSql('CREATE INDEX IDX_AEDAD51C94A4C7D4 ON exercise (device_id)');
        $this->addSql('ALTER TABLE exercise_log ADD user_id INT NOT NULL, ADD exercise_id INT NOT NULL, DROP user, DROP exercise');
        $this->addSql('ALTER TABLE exercise_log ADD CONSTRAINT FK_1960CDB9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise_log ADD CONSTRAINT FK_1960CDB9E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('CREATE INDEX IDX_1960CDB9A76ED395 ON exercise_log (user_id)');
        $this->addSql('CREATE INDEX IDX_1960CDB9E934951A ON exercise_log (exercise_id)');
        $this->addSql('ALTER TABLE session ADD workout_id INT NOT NULL, DROP workout, DROP users');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4A6CCCFC9 ON session (workout_id)');
        $this->addSql('ALTER TABLE workout DROP circuits');
        $this->addSql('ALTER TABLE workout_log ADD user_id INT NOT NULL, ADD workout_id INT NOT NULL, DROP user, DROP workout');
        $this->addSql('ALTER TABLE workout_log ADD CONSTRAINT FK_6F5B68DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workout_log ADD CONSTRAINT FK_6F5B68DA6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id)');
        $this->addSql('CREATE INDEX IDX_6F5B68DA76ED395 ON workout_log (user_id)');
        $this->addSql('CREATE INDEX IDX_6F5B68DA6CCCFC9 ON workout_log (workout_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE session_user');
        $this->addSql('ALTER TABLE circuit DROP FOREIGN KEY FK_1325F3A6A6CCCFC9');
        $this->addSql('DROP INDEX IDX_1325F3A6A6CCCFC9 ON circuit');
        $this->addSql('ALTER TABLE circuit ADD exercises LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', DROP workout_id');
        $this->addSql('ALTER TABLE circuit_log DROP FOREIGN KEY FK_719B6B56A76ED395');
        $this->addSql('ALTER TABLE circuit_log DROP FOREIGN KEY FK_719B6B56CF2182C8');
        $this->addSql('DROP INDEX IDX_719B6B56A76ED395 ON circuit_log');
        $this->addSql('DROP INDEX IDX_719B6B56CF2182C8 ON circuit_log');
        $this->addSql('ALTER TABLE circuit_log ADD user LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', ADD circuit LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', DROP user_id, DROP circuit_id');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51CCF2182C8');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C94A4C7D4');
        $this->addSql('DROP INDEX IDX_AEDAD51CCF2182C8 ON exercise');
        $this->addSql('DROP INDEX IDX_AEDAD51C94A4C7D4 ON exercise');
        $this->addSql('ALTER TABLE exercise ADD device LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', DROP circuit_id, DROP device_id');
        $this->addSql('ALTER TABLE exercise_log DROP FOREIGN KEY FK_1960CDB9A76ED395');
        $this->addSql('ALTER TABLE exercise_log DROP FOREIGN KEY FK_1960CDB9E934951A');
        $this->addSql('DROP INDEX IDX_1960CDB9A76ED395 ON exercise_log');
        $this->addSql('DROP INDEX IDX_1960CDB9E934951A ON exercise_log');
        $this->addSql('ALTER TABLE exercise_log ADD user LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', ADD exercise LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', DROP user_id, DROP exercise_id');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4A6CCCFC9');
        $this->addSql('DROP INDEX IDX_D044D5D4A6CCCFC9 ON session');
        $this->addSql('ALTER TABLE session ADD workout LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', ADD users LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', DROP workout_id');
        $this->addSql('ALTER TABLE workout ADD circuits LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE workout_log DROP FOREIGN KEY FK_6F5B68DA76ED395');
        $this->addSql('ALTER TABLE workout_log DROP FOREIGN KEY FK_6F5B68DA6CCCFC9');
        $this->addSql('DROP INDEX IDX_6F5B68DA76ED395 ON workout_log');
        $this->addSql('DROP INDEX IDX_6F5B68DA6CCCFC9 ON workout_log');
        $this->addSql('ALTER TABLE workout_log ADD user LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', ADD workout LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', DROP user_id, DROP workout_id');
    }
}
