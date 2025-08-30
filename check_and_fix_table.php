<?php

// Connexion à la base de données
$host = 'localhost';
$dbname = 'drivn_cook'; // Remplacez par le nom de votre base de données
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie.\n";
    
    // Vérifier si la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'commande_clients'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "Table commande_clients existe.\n";
        
        // Vérifier la structure de la colonne statut
        $stmt = $pdo->query("DESCRIBE commande_clients");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $statutColumn = null;
        foreach ($columns as $column) {
            if ($column['Field'] === 'statut') {
                $statutColumn = $column;
                break;
            }
        }
        
        if ($statutColumn) {
            echo "Colonne statut trouvée: " . $statutColumn['Type'] . "\n";
            
            // Vérifier si la colonne accepte 'en_livraison'
            if (strpos($statutColumn['Type'], 'en_livraison') === false) {
                echo "La colonne statut ne contient pas 'en_livraison'. Correction en cours...\n";
                
                // Modifier la colonne statut
                $sql = "ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee') DEFAULT 'en_attente'";
                $pdo->exec($sql);
                echo "Colonne statut corrigée avec succès !\n";
            } else {
                echo "La colonne statut est déjà correcte.\n";
            }
        } else {
            echo "Colonne statut non trouvée. Création de la colonne...\n";
            $sql = "ALTER TABLE commande_clients ADD COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee') DEFAULT 'en_attente' AFTER franchise_id";
            $pdo->exec($sql);
            echo "Colonne statut créée avec succès !\n";
        }
    } else {
        echo "Table commande_clients n'existe pas. Création...\n";
        
        $sql = "CREATE TABLE commande_clients (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            client_id BIGINT UNSIGNED NOT NULL,
            franchise_id BIGINT UNSIGNED NOT NULL,
            statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee') DEFAULT 'en_attente',
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
        )";
        
        $pdo->exec($sql);
        echo "Table commande_clients créée avec succès !\n";
    }
    
    // Test d'insertion pour vérifier
    echo "Test de la colonne statut...\n";
    $testSql = "INSERT INTO commande_clients (client_id, franchise_id, statut, montant_total, montant_final, adresse_livraison, telephone_contact) VALUES (1, 1, 'en_livraison', 10.00, 10.00, 'Test', 'Test')";
    $pdo->exec($testSql);
    echo "Test réussi ! La valeur 'en_livraison' est acceptée.\n";
    
    // Supprimer l'enregistrement de test
    $pdo->exec("DELETE FROM commande_clients WHERE adresse_livraison = 'Test'");
    echo "Enregistrement de test supprimé.\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

