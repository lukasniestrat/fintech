<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926172632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO bank_account (name, iban, is_saving_account) VALUES ("Girkonto", "DE***********5065", 0)');
        $this->addSql('INSERT INTO category (name, tags) VALUES("Sonstiges", null)');
        $this->addSql('INSERT INTO category (name, tags) VALUES("Wohnen", "EWE, Telekom, Miete")');
        $this->addSql('INSERT INTO category (name, tags) VALUES("Lebensmittel", "Edeka, Combi, Lidl, Aldi")');
        $this->addSql('INSERT INTO category (name, tags) VALUES("Freizeit", "Netflix, Amazon, Spotify, PayPal")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
