<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919101652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resident ADD room_id INT NOT NULL, ADD referent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA0654177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA0635E47E35 FOREIGN KEY (referent_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1D03DA0654177093 ON resident (room_id)');
        $this->addSql('CREATE INDEX IDX_1D03DA0635E47E35 ON resident (referent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA0654177093');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA0635E47E35');
        $this->addSql('DROP INDEX IDX_1D03DA0654177093 ON resident');
        $this->addSql('DROP INDEX IDX_1D03DA0635E47E35 ON resident');
        $this->addSql('ALTER TABLE resident DROP room_id, DROP referent_id');
    }
}
