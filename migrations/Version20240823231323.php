<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823231323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Payment methods';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, label VARCHAR(7) NOT NULL, colour VARCHAR(7) DEFAULT NULL, public TINYINT(1) NOT NULL, position SMALLINT DEFAULT NULL, available TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD payment_method_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('CREATE INDEX IDX_F52993985AA1164F ON `order` (payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985AA1164F');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('ALTER TABLE category CHANGE position position SMALLINT NOT NULL');
        $this->addSql('DROP INDEX IDX_F52993985AA1164F ON `order`');
        $this->addSql('ALTER TABLE `order` DROP payment_method_id');
    }
}
