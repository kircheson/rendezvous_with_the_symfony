<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620144719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_manager_table ADD user_id INT DEFAULT NULL, DROP email');
        $this->addSql('ALTER TABLE task_manager_table ADD CONSTRAINT FK_9AE1822DA76ED395 FOREIGN KEY (user_id) REFERENCES users_table (id)');
        $this->addSql('CREATE INDEX IDX_9AE1822DA76ED395 ON task_manager_table (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_manager_table DROP FOREIGN KEY FK_9AE1822DA76ED395');
        $this->addSql('DROP INDEX IDX_9AE1822DA76ED395 ON task_manager_table');
        $this->addSql('ALTER TABLE task_manager_table ADD email VARCHAR(255) NOT NULL, DROP user_id');
    }
}
