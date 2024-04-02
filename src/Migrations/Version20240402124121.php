<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402124121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Schuldenrust lening to kindregeling';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE voorlegger RENAME COLUMN schuldenrust_lening TO kindregeling");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE voorlegger RENAME COLUMN kindregeling TO schuldenrust_lening");
    }
}