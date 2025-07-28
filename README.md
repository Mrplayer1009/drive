# Driv'n Cook - Gestion des Franchisés

Application web complète pour la gestion des franchisés Driv'n Cook avec back-office administrateur et front-office franchisé.

## 🚀 Fonctionnalités

### Back-Office (Administrateur)
- **Tableau de bord** avec statistiques globales
- **Gestion des franchisés** : création, modification, suivi
- **Gestion des entrepôts** : 4 entrepôts avec capacités et cuisines
- **Gestion du parc de camions** : attribution, maintenance, localisation
- **Suivi des ventes** : enregistrement, calcul automatique des pourcentages
- **Gestion des commandes** : contrôle automatique de la règle 80/20
- **Statistiques et rapports** : graphiques et génération de PDF

### Front-Office (Franchisés)
- **Tableau de bord personnalisé** avec statistiques individuelles
- **Gestion du profil** : modification des informations personnelles
- **Consultation des camions** attribués
- **Commande de stocks** avec respect de la règle 80/20
- **Enregistrement des ventes** avec calcul automatique
- **Téléchargement des PDF** de ventes

## 🛠️ Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL/MariaDB
- Apache/Nginx

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [url-du-repo]
cd drive
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifiez le fichier `.env` avec vos informations de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=drivncook
DB_USERNAME=root
DB_PASSWORD=
```

5. **Créer la base de données**
```bash
php artisan migrate
```

6. **Peupler la base de données avec les données de test**
```bash
php artisan db:seed
```

7. **Créer le lien symbolique pour le stockage**
```bash
php artisan storage:link
```

8. **Démarrer le serveur**
```bash
php artisan serve
```

## 🔐 Authentification

### Administrateur
- **Email** : admin@drivncook.com
- **Mot de passe** : admin123

### Franchisés de test
- **Jean Dupont** : jean.dupont@drivncook.com / password123
- **Sophie Martin** : sophie.martin@drivncook.com / password123
- **Pierre Bernard** : pierre.bernard@drivncook.com / password123
- **Marie Petit** : marie.petit@drivncook.com / password123
- **Claude Robert** : claude.robert@drivncook.com / password123

## 📊 Structure de la base de données

### Tables principales
- **franchises** : Informations des franchisés
- **entrepots** : Gestion des 4 entrepôts
- **camions** : Parc de camions avec localisation
- **produits** : Catalogue (ingrédients, plats, boissons)
- **commandes** : Commandes de stock avec règle 80/20
- **ventes** : Enregistrement des ventes avec calcul des pourcentages
- **franchise_camion** : Attribution des camions aux franchisés
- **commande_produits** : Détail des produits dans les commandes

### Règles métier implémentées
- **Droits d'entrée** : 50 000 € par franchisé
- **Pourcentage de ventes** : 4% automatiquement calculé
- **Règle 80/20** : 80% minimum de stock obligatoire, 20% libre
- **Génération de PDF** : Rapports de ventes automatiques

## 🎨 Interface utilisateur

### Technologies utilisées
- **Backend** : Laravel 10
- **Frontend** : Tailwind CSS + Font Awesome
- **Base de données** : MySQL
- **Authentification** : Laravel Sanctum + Guards multiples

### Design
- Interface moderne et responsive
- Navigation intuitive avec sidebar
- Couleurs cohérentes (orange/rouge pour Driv'n Cook)
- Tableaux de bord avec statistiques visuelles

## 📁 Structure du projet

```
drive/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php
│   │   ├── FranchiseController.php
│   │   └── AuthController.php
│   ├── Models/
│   │   ├── Franchise.php
│   │   ├── Entrepot.php
│   │   ├── Camion.php
│   │   ├── Produit.php
│   │   ├── Commande.php
│   │   ├── Vente.php
│   │   └── CommandeProduit.php
│   └── Http/Middleware/
│       └── AdminMiddleware.php
├── database/
│   ├── migrations/
│   │   ├── create_franchises_table.php
│   │   ├── create_entrepots_table.php
│   │   ├── create_camions_table.php
│   │   ├── create_produits_table.php
│   │   ├── create_commandes_table.php
│   │   ├── create_commande_produits_table.php
│   │   ├── create_ventes_table.php
│   │   └── create_franchise_camion_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── EntrepotSeeder.php
│       ├── ProduitSeeder.php
│       ├── FranchiseSeeder.php
│       ├── CamionSeeder.php
│       ├── CommandeSeeder.php
│       └── VenteSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── admin.blade.php
│   ├── admin/
│   │   └── dashboard.blade.php
│   ├── auth/
│   │   └── login.blade.php
│   └── welcome.blade.php
└── routes/
    └── web.php
```

## 🚀 Déploiement

### Configuration serveur
1. **Apache/Nginx** : Configurer le virtual host
2. **PHP** : Activer les extensions nécessaires
3. **MySQL** : Créer la base de données
4. **Permissions** : Donner les droits d'écriture sur storage/

### Variables d'environnement
```env
APP_NAME="Driv'n Cook"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=drivncook
DB_USERNAME=votre_user
DB_PASSWORD=votre_password
```

## 🔧 Maintenance

### Commandes utiles
```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimiser l'application
php artisan optimize

# Vérifier les migrations
php artisan migrate:status

# Régénérer les données de test
php artisan migrate:fresh --seed
```

## 📞 Support

Pour toute question ou problème :
- Vérifiez les logs dans `storage/logs/`
- Consultez la documentation Laravel
- Contactez l'équipe de développement

## 📄 Licence

Ce projet est développé pour Driv'n Cook. Tous droits réservés.
