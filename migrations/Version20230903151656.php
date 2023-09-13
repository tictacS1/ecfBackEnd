<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903151656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(190) NOT NULL, anee_edition INT DEFAULT NULL, nombre_pages INT NOT NULL, code_isbn VARCHAR(190) DEFAULT NULL, INDEX IDX_AC634F9960BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F9960BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE emprunt ADD livre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D737D925CB');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F9960BB6FE6');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP INDEX IDX_364071D737D925CB ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP livre_id');
    }
}
