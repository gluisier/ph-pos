<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814095001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Packs and variants';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_attribute (item_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_F6A0F90B126F525E (item_id), INDEX IDX_F6A0F90BB6E62EFA (attribute_id), PRIMARY KEY(item_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition (compound_id INT NOT NULL, component_id INT NOT NULL, INDEX IDX_C7F4347AE76FC6F (compound_id), INDEX IDX_C7F4347E2ABAFFF (component_id), PRIMARY KEY(compound_id, component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_attribute ADD CONSTRAINT FK_F6A0F90B126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_attribute ADD CONSTRAINT FK_F6A0F90BB6E62EFA FOREIGN KEY (attribute_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE composition ADD CONSTRAINT FK_C7F4347AE76FC6F FOREIGN KEY (compound_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE composition ADD CONSTRAINT FK_C7F4347E2ABAFFF FOREIGN KEY (component_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE category ADD public TINYINT(1) NOT NULL, ADD position SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EE6D25B4C');
        $this->addSql('DROP INDEX IDX_1F1B251EE6D25B4C ON item');
        $this->addSql('ALTER TABLE item ADD public TINYINT(1) NOT NULL, ADD position SMALLINT NOT NULL, ADD available TINYINT(1) NOT NULL, ADD separately_sellable TINYINT(1) NOT NULL, CHANGE packed_in_id variant_of_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EDA873C21 FOREIGN KEY (variant_of_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EDA873C21 ON item (variant_of_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_attribute DROP FOREIGN KEY FK_F6A0F90B126F525E');
        $this->addSql('ALTER TABLE item_attribute DROP FOREIGN KEY FK_F6A0F90BB6E62EFA');
        $this->addSql('ALTER TABLE composition DROP FOREIGN KEY FK_C7F4347AE76FC6F');
        $this->addSql('ALTER TABLE composition DROP FOREIGN KEY FK_C7F4347E2ABAFFF');
        $this->addSql('DROP TABLE item_attribute');
        $this->addSql('DROP TABLE composition');
        $this->addSql('ALTER TABLE category DROP public, DROP position');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EDA873C21');
        $this->addSql('DROP INDEX IDX_1F1B251EDA873C21 ON item');
        $this->addSql('ALTER TABLE item DROP public, DROP position, DROP available, DROP separately_sellable, CHANGE variant_of_id packed_in_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EE6D25B4C FOREIGN KEY (packed_in_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EE6D25B4C ON item (packed_in_id)');
    }
}
