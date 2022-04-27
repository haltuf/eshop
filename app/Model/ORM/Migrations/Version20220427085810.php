<?php

declare(strict_types=1);

namespace Eshop\Model\ORM\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427085810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD delivery_address_name VARCHAR(255) NOT NULL, ADD delivery_address_surname VARCHAR(255) NOT NULL, ADD delivery_address_address VARCHAR(255) NOT NULL, ADD delivery_address_zip VARCHAR(255) NOT NULL, ADD delivery_address_city VARCHAR(255) NOT NULL, ADD delivery_address_country VARCHAR(255) NOT NULL, ADD delivery_address_company VARCHAR(255) NOT NULL, ADD delivery_address_tax_number VARCHAR(255) NOT NULL, ADD delivery_address_vat_number VARCHAR(255) NOT NULL, ADD invoice_address_name VARCHAR(255) NOT NULL, ADD invoice_address_surname VARCHAR(255) NOT NULL, ADD invoice_address_address VARCHAR(255) NOT NULL, ADD invoice_address_zip VARCHAR(255) NOT NULL, ADD invoice_address_city VARCHAR(255) NOT NULL, ADD invoice_address_country VARCHAR(255) NOT NULL, ADD invoice_address_company VARCHAR(255) NOT NULL, ADD invoice_address_tax_number VARCHAR(255) NOT NULL, ADD invoice_address_vat_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD visible TINYINT(1) DEFAULT \'0\' NOT NULL, ADD url VARCHAR(255) NOT NULL, ADD short_description MEDIUMTEXT NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX url_idx ON product (url)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE basket DROP email, DROP phone, DROP delivery_address_name, DROP delivery_address_surname, DROP delivery_address_address, DROP delivery_address_zip, DROP delivery_address_city, DROP delivery_address_country, DROP delivery_address_company, DROP delivery_address_tax_number, DROP delivery_address_vat_number, DROP invoice_address_name, DROP invoice_address_surname, DROP invoice_address_address, DROP invoice_address_zip, DROP invoice_address_city, DROP invoice_address_country, DROP invoice_address_company, DROP invoice_address_tax_number, DROP invoice_address_vat_number');
        $this->addSql('DROP INDEX url_idx ON product');
        $this->addSql('ALTER TABLE product DROP visible, DROP url, DROP short_description, DROP description');
    }
}
