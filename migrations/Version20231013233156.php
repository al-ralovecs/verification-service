<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013233156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE verification (id UUID NOT NULL, confirmed BOOLEAN NOT NULL, code VARCHAR(8) NOT NULL, attempts SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, identity VARCHAR(128) NOT NULL, type VARCHAR(64) NOT NULL, ip_address VARCHAR(64) NOT NULL, user_agent VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX duplicate_exists ON verification (identity, type, created_at, attempts)');
        $this->addSql('COMMENT ON COLUMN verification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN verification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN verification.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX duplicate_exists');
        $this->addSql('DROP TABLE verification');
    }
}
