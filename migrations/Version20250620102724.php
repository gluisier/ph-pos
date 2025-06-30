<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620102724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Printers';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE printer (id VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, location VARCHAR(255) DEFAULT NULL, ip VARCHAR(63) NOT NULL, port SMALLINT NOT NULL, api VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD printer_id VARCHAR(255) DEFAULT NULL AFTER name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D64946EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D64946EC494A ON user (printer_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D64946EC494A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D64946EC494A ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP printer_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE printer
        SQL);
    }
}
