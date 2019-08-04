<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190804143157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE metric (name VARCHAR(25) NOT NULL, section_id VARCHAR(25) NOT NULL, title VARCHAR(100) NOT NULL, order_index INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, format_decimals_count INTEGER NOT NULL, format_decimal_point VARCHAR(1) NOT NULL, format_thousands_separator VARCHAR(1) NOT NULL, format_unit VARCHAR(25) NOT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_87D62EE33ED13FC ON metric (order_index)');
        $this->addSql('CREATE INDEX IDX_87D62EE3D823E37A ON metric (section_id)');
        $this->addSql('CREATE TABLE value (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, metric_id VARCHAR(25) NOT NULL, created_at DATETIME NOT NULL, value DOUBLE PRECISION NOT NULL, tag VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1D775834A952D583 ON value (metric_id)');
        $this->addSql('CREATE TABLE section (name VARCHAR(25) NOT NULL, title VARCHAR(100) NOT NULL, order_index INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEF3ED13FC ON section (order_index)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE metric');
        $this->addSql('DROP TABLE value');
        $this->addSql('DROP TABLE section');
    }
}
