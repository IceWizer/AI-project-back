<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209120158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, description VARCHAR(4095) NOT NULL, price INT NOT NULL, stock INT NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candle_category (candle_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_E986407F1E70F241 (candle_id), INDEX IDX_E986407F12469DE2 (category_id), PRIMARY KEY(candle_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', candle_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C53D045F1E70F241 (candle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', token VARCHAR(350) DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candle_category ADD CONSTRAINT FK_E986407F1E70F241 FOREIGN KEY (candle_id) REFERENCES candle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candle_category ADD CONSTRAINT FK_E986407F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F1E70F241 FOREIGN KEY (candle_id) REFERENCES candle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candle_category DROP FOREIGN KEY FK_E986407F1E70F241');
        $this->addSql('ALTER TABLE candle_category DROP FOREIGN KEY FK_E986407F12469DE2');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F1E70F241');
        $this->addSql('DROP TABLE candle');
        $this->addSql('DROP TABLE candle_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
    }
}
