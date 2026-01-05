<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105160318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, numero_table INT DEFAULT NULL, aemporter TINYINT NOT NULL, total DOUBLE PRECISION DEFAULT NULL, restaurant_id INT NOT NULL, client_id INT NOT NULL, serveur_id INT DEFAULT NULL, INDEX IDX_6EEAA67DB1E7706E (restaurant_id), INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67DB8F06499 (serveur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande_plats (commande_id INT NOT NULL, plats_id INT NOT NULL, INDEX IDX_BC22BDC982EA2E54 (commande_id), INDEX IDX_BC22BDC9AA14E1C8 (plats_id), PRIMARY KEY (commande_id, plats_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE horaire (id INT AUTO_INCREMENT NOT NULL, jour VARCHAR(20) NOT NULL, ouverture_midi TIME DEFAULT NULL, fermeture_midi TIME DEFAULT NULL, ouverture_soir TIME DEFAULT NULL, fermeture_soir TIME DEFAULT NULL, ferme TINYINT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_BBC83DB6B1E7706E (restaurant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, commande_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), INDEX IDX_3170B74BD73DB560 (plat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE plats (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, restaurant_id INT NOT NULL, category_id INT DEFAULT NULL, INDEX IDX_854A620AB1E7706E (restaurant_id), INDEX IDX_854A620A12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE plats_stock (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_9030DC85D73DB560 (plat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, est_ouvert VARCHAR(255) DEFAULT NULL, nombre_places INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, is_verified TINYINT NOT NULL, discr VARCHAR(255) NOT NULL, adresse_livraison VARCHAR(255) DEFAULT NULL, matricule VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB8F06499 FOREIGN KEY (serveur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande_plats ADD CONSTRAINT FK_BC22BDC982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_plats ADD CONSTRAINT FK_BC22BDC9AA14E1C8 FOREIGN KEY (plats_id) REFERENCES plats (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE horaire ADD CONSTRAINT FK_BBC83DB6B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BD73DB560 FOREIGN KEY (plat_id) REFERENCES plats (id)');
        $this->addSql('ALTER TABLE plats ADD CONSTRAINT FK_854A620AB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE plats ADD CONSTRAINT FK_854A620A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE plats_stock ADD CONSTRAINT FK_9030DC85D73DB560 FOREIGN KEY (plat_id) REFERENCES plats (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB1E7706E');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB8F06499');
        $this->addSql('ALTER TABLE commande_plats DROP FOREIGN KEY FK_BC22BDC982EA2E54');
        $this->addSql('ALTER TABLE commande_plats DROP FOREIGN KEY FK_BC22BDC9AA14E1C8');
        $this->addSql('ALTER TABLE horaire DROP FOREIGN KEY FK_BBC83DB6B1E7706E');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BD73DB560');
        $this->addSql('ALTER TABLE plats DROP FOREIGN KEY FK_854A620AB1E7706E');
        $this->addSql('ALTER TABLE plats DROP FOREIGN KEY FK_854A620A12469DE2');
        $this->addSql('ALTER TABLE plats_stock DROP FOREIGN KEY FK_9030DC85D73DB560');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_plats');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE plats');
        $this->addSql('DROP TABLE plats_stock');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
