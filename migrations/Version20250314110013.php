<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314110013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate_data ADD currency_pair_id INT NOT NULL, DROP currency_pair');
        $this->addSql('ALTER TABLE currency_rate_data ADD CONSTRAINT FK_D67968E4A311484C FOREIGN KEY (currency_pair_id) REFERENCES currency_pair (id)');
        $this->addSql('CREATE INDEX IDX_D67968E4A311484C ON currency_rate_data (currency_pair_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rate_data DROP FOREIGN KEY FK_D67968E4A311484C');
        $this->addSql('DROP INDEX IDX_D67968E4A311484C ON currency_rate_data');
        $this->addSql('ALTER TABLE currency_rate_data ADD currency_pair VARCHAR(255) NOT NULL, DROP currency_pair_id');
    }
}
