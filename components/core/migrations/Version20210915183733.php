<?php

declare(strict_types = 1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915183733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, iban VARCHAR(22) NOT NULL, is_saving_account TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account_amount (id INT AUTO_INCREMENT NOT NULL, bank_account_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_3ECDC2E812CB990C (bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_identifier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tags VARCHAR(510) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_bank_account (category_id INT NOT NULL, bank_account_id INT NOT NULL, INDEX IDX_89751D9812469DE2 (category_id), INDEX IDX_89751D9812CB990C (bank_account_id), PRIMARY KEY(category_id, bank_account_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repeating_transaction (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, last_booking_date DATE NOT NULL, next_booking_date DATE NOT NULL, INDEX IDX_5ABDD0003301C60 (booking_id), INDEX IDX_5ABDD00012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, bank_account_id INT NOT NULL, name VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, booking_date DATE NOT NULL, iban VARCHAR(22) NOT NULL, INDEX IDX_723705D112CB990C (bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_category (transaction_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_483E30A92FC0CB0F (transaction_id), INDEX IDX_483E30A912469DE2 (category_id), PRIMARY KEY(transaction_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account_amount ADD CONSTRAINT FK_3ECDC2E812CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE category_bank_account ADD CONSTRAINT FK_89751D9812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_bank_account ADD CONSTRAINT FK_89751D9812CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repeating_transaction ADD CONSTRAINT FK_5ABDD0003301C60 FOREIGN KEY (booking_id) REFERENCES booking_identifier (id)');
        $this->addSql('ALTER TABLE repeating_transaction ADD CONSTRAINT FK_5ABDD00012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE transaction_category ADD CONSTRAINT FK_483E30A92FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_category ADD CONSTRAINT FK_483E30A912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account_amount DROP FOREIGN KEY FK_3ECDC2E812CB990C');
        $this->addSql('ALTER TABLE category_bank_account DROP FOREIGN KEY FK_89751D9812CB990C');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D112CB990C');
        $this->addSql('ALTER TABLE repeating_transaction DROP FOREIGN KEY FK_5ABDD0003301C60');
        $this->addSql('ALTER TABLE category_bank_account DROP FOREIGN KEY FK_89751D9812469DE2');
        $this->addSql('ALTER TABLE repeating_transaction DROP FOREIGN KEY FK_5ABDD00012469DE2');
        $this->addSql('ALTER TABLE transaction_category DROP FOREIGN KEY FK_483E30A912469DE2');
        $this->addSql('ALTER TABLE transaction_category DROP FOREIGN KEY FK_483E30A92FC0CB0F');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE bank_account_amount');
        $this->addSql('DROP TABLE booking_identifier');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_bank_account');
        $this->addSql('DROP TABLE repeating_transaction');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_category');
    }
}
