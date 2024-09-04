<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904201427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Item#quantity to Item#stock and make position nullable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE quantity stock INT DEFAULT NULL, CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE position position SMALLINT DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE stock quantity INT DEFAULT NULL, CHANGE position position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE category CHANGE position position SMALLINT NOT NULL');
    }
}
