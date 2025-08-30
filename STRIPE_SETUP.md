# Configuration Stripe pour Driv'n Cook

## 🔑 Clés de Test Stripe

### Clés publiques (Frontend)
```
pk_test_51OvXXXXXXXXXXXXX
```

### Clés secrètes (Backend)
```
sk_test_51OvXXXXXXXXXXXXX
```

## 📝 Configuration

### 1. Variables d'environnement (.env)
```env
STRIPE_KEY=pk_test_51OvXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_51OvXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXX
```

### 2. Installation du package
```bash
composer require stripe/stripe-php
```

## 🧪 Cartes de test Stripe

### Cartes qui fonctionnent :
- **4242 4242 4242 4242** - Paiement réussi
- **4000 0000 0000 0002** - Paiement refusé
- **4000 0000 0000 9995** - Paiement refusé (insuffisant)

### Informations de test :
- **Date d'expiration** : N'importe quelle date future (ex: 12/25)
- **CVC** : N'importe quels 3 chiffres (ex: 123)
- **Code postal** : N'importe quoi (ex: 12345)

## 🚀 Utilisation

1. **Ajouter au panier** : Les articles sont stockés en session
2. **Payer avec Stripe** : Bouton vert dans le panier (seul mode de paiement)
3. **Paiement sécurisé** : Interface Stripe Elements simplifiée
4. **Confirmation** : Page de succès avec détails de la commande

## 🔧 Webhooks (Optionnel)

Pour les webhooks en production :
1. Créer un endpoint webhook dans le dashboard Stripe
2. Configurer l'URL : `https://votre-domaine.com/stripe/webhook`
3. Récupérer la clé secrète webhook
4. Ajouter `STRIPE_WEBHOOK_SECRET` dans .env

## 📱 Interface

- **Checkout** : `/client/stripe/checkout`
- **Succès** : `/client/stripe/success`
- **Annulation** : `/client/stripe/cancel`

## 🛡️ Sécurité

- Validation côté serveur
- Clés secrètes protégées
- Webhooks pour confirmation
- Logs d'erreurs détaillés

## 📝 Simplifications pour le projet

- **Paiement unique** : Seul Stripe est disponible (pas d'espèces ni CB classique)
- **Interface simplifiée** : Pas de champs supplémentaires (nom/email automatiques)
- **Points de fidélité** : Supprimés pour simplifier le projet
- **Commande classique** : Supprimée, uniquement paiement Stripe
