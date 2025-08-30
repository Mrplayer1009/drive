-- Script pour corriger la colonne statut de la table commande_clients
-- Ce script doit être exécuté dans votre base de données MySQL

-- Vérifier si la table existe
SET @table_exists = (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'commande_clients');

-- Si la table n'existe pas, la créer
SET @sql = IF(@table_exists = 0, 
    'CREATE TABLE commande_clients (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        client_id BIGINT UNSIGNED NOT NULL,
        franchise_id BIGINT UNSIGNED NOT NULL,
        statut ENUM("en_attente", "confirmee", "en_preparation", "en_livraison", "livree", "annulee") DEFAULT "en_attente",
        montant_total DECIMAL(10,2) NOT NULL,
        reduction_fidelite DECIMAL(10,2) DEFAULT 0,
        montant_final DECIMAL(10,2) NOT NULL,
        notes TEXT NULL,
        adresse_livraison VARCHAR(255) NOT NULL,
        telephone_contact VARCHAR(255) NOT NULL,
        mode_paiement VARCHAR(255) NULL,
        reference_paiement VARCHAR(255) NULL,
        date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        date_livraison_souhaitee TIMESTAMP NULL,
        date_livraison_effective TIMESTAMP NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
        FOREIGN KEY (franchise_id) REFERENCES franchises(id) ON DELETE CASCADE
    )',
    'SELECT "Table commande_clients already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Si la table existe, modifier la colonne statut
SET @sql = IF(@table_exists = 1, 
    'ALTER TABLE commande_clients MODIFY COLUMN statut ENUM("en_attente", "confirmee", "en_preparation", "en_livraison", "livree", "annulee") DEFAULT "en_attente"',
    'SELECT "Table was created, no modification needed" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Afficher le résultat
SELECT "Colonne statut corrigée avec succès !" as result;

