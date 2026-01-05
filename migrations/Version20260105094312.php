<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105094312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_plats (commande_id INT NOT NULL, plats_id INT NOT NULL, INDEX IDX_BC22BDC982EA2E54 (commande_id), INDEX IDX_BC22BDC9AA14E1C8 (plats_id), PRIMARY KEY (commande_id, plats_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande_plats ADD CONSTRAINT FK_BC22BDC982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_plats ADD CONSTRAINT FK_BC22BDC9AA14E1C8 FOREIGN KEY (plats_id) REFERENCES plats (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_plats DROP FOREIGN KEY FK_BC22BDC982EA2E54');
        $this->addSql('ALTER TABLE commande_plats DROP FOREIGN KEY FK_BC22BDC9AA14E1C8');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande_plats');
        $this->addSql('ALTER TABLE plats DROP FOREIGN KEY FK_854A620A12469DE2');
        $this->addSql('DROP INDEX IDX_854A620A12469DE2 ON plats');
        $this->addSql('ALTER TABLE plats DROP description, DROP image, DROP category_id');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
