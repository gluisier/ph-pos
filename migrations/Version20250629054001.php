<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629054001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Location';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO location (name) SELECT `location` FROM printer WHERE `location` <> '' AND `location` IS NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE printer ADD location_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE printer ADD CONSTRAINT FK_8D4C79ED64D218E FOREIGN KEY (location_id) REFERENCES location (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D4C79ED64D218E ON printer (location_id)
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE printer SET location_id = (SELECT id FROM `location` WHERE `name` = `location`)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE printer DROP location
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE printer ADD `location` VARCHAR(255) DEFAULT NULL AFTER status
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE printer SET `location` = (SELECT name FROM `location` WHERE location_id = location.id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE printer DROP FOREIGN KEY FK_8D4C79ED64D218E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE location
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D4C79ED64D218E ON printer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE printer DROP location_id
        SQL);
    }
}
