/**
 * Service de gestion de la fidélité côté client
 */
class FideliteService {
    constructor() {
        this.storageKey = 'drivncook_fidelite';
        this.initFidelite();
    }

    /**
     * Initialiser la fidélité si elle n'existe pas
     */
    initFidelite() {
        if (!localStorage.getItem(this.storageKey)) {
            const fideliteInitiale = {
                points: 0,
                niveau: 1,
                reduction_cumulee: 0,
                lastUpdate: new Date().toISOString()
            };
            localStorage.setItem(this.storageKey, JSON.stringify(fideliteInitiale));
        }
    }

    /**
     * Obtenir les informations de fidélité
     */
    getInfosFidelite() {
        const fidelite = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
        const points = fidelite.points || 0;
        
        return {
            points: points,
            niveau: this.calculerNiveau(points),
            reduction_disponible: this.calculerReduction(points),
            reduction_cumulee: fidelite.reduction_cumulee || 0,
            prochain_palier: this.getProchainPalier(points),
            points_pour_prochain_niveau: this.getPointsPourProchainNiveau(points),
            niveau_nom: this.getNomNiveau(this.calculerNiveau(points)),
            avantages_niveau: this.getAvantagesNiveau(this.calculerNiveau(points))
        };
    }

    /**
     * Calculer le niveau de fidélité basé sur les points
     */
    calculerNiveau(points) {
        if (points >= 1000) return 5; // VIP
        if (points >= 500) return 4;  // Gold
        if (points >= 200) return 3;  // Silver
        if (points >= 50) return 2;   // Bronze
        return 1; // Nouveau
    }

    /**
     * Calculer la réduction disponible
     */
    calculerReduction(points) {
        // 100 points = 5€ de réduction
        return Math.floor(points / 100) * 5;
    }

    /**
     * Obtenir le prochain palier de points
     */
    getProchainPalier(points) {
        if (points < 50) return 50;
        if (points < 200) return 200;
        if (points < 500) return 500;
        if (points < 1000) return 1000;
        return null; // Niveau maximum atteint
    }

    /**
     * Obtenir les points nécessaires pour le prochain niveau
     */
    getPointsPourProchainNiveau(points) {
        const prochainPalier = this.getProchainPalier(points);
        return prochainPalier ? prochainPalier - points : 0;
    }

    /**
     * Obtenir le nom du niveau
     */
    getNomNiveau(niveau) {
        const niveaux = {
            1: 'Nouveau',
            2: 'Bronze',
            3: 'Silver',
            4: 'Gold',
            5: 'VIP'
        };
        return niveaux[niveau] || 'Nouveau';
    }

    /**
     * Obtenir les avantages du niveau
     */
    getAvantagesNiveau(niveau) {
        const avantages = {
            1: ['Réduction de base : 100 points = 5€'],
            2: ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 30€'],
            3: ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 25€', 'Offres exclusives'],
            4: ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 20€', 'Offres exclusives', 'Support prioritaire'],
            5: ['Réduction de base : 100 points = 5€', 'Livraison gratuite', 'Offres exclusives', 'Support prioritaire', 'Accès VIP']
        };
        return avantages[niveau] || avantages[1];
    }

    /**
     * Ajouter des points après une commande
     */
    ajouterPoints(montantCommande) {
        const fidelite = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
        const pointsGagnes = Math.floor(montantCommande);
        
        fidelite.points = (fidelite.points || 0) + pointsGagnes;
        fidelite.niveau = this.calculerNiveau(fidelite.points);
        fidelite.lastUpdate = new Date().toISOString();
        
        localStorage.setItem(this.storageKey, JSON.stringify(fidelite));
        
        console.log(`Points de fidélité ajoutés : ${pointsGagnes} points (Total: ${fidelite.points})`);
        
        return pointsGagnes;
    }

    /**
     * Utiliser des points pour une réduction
     */
    utiliserPoints(montantReduction) {
        const fidelite = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
        const pointsUtilises = montantReduction * 20; // 5€ = 100 points, donc 1€ = 20 points
        
        if (pointsUtilises > (fidelite.points || 0)) {
            throw new Error('Points insuffisants');
        }
        
        fidelite.points = (fidelite.points || 0) - pointsUtilises;
        fidelite.reduction_cumulee = (fidelite.reduction_cumulee || 0) + montantReduction;
        fidelite.niveau = this.calculerNiveau(fidelite.points);
        fidelite.lastUpdate = new Date().toISOString();
        
        localStorage.setItem(this.storageKey, JSON.stringify(fidelite));
        
        console.log(`Points utilisés : ${pointsUtilises} points pour ${montantReduction}€ de réduction`);
        
        return {
            pointsUtilises,
            pointsRestants: fidelite.points,
            reductionCumulee: fidelite.reduction_cumulee
        };
    }

    /**
     * Vérifier si une réduction peut être appliquée
     */
    peutAppliquerReduction(montantReduction, montantCommande) {
        const infos = this.getInfosFidelite();
        
        // Vérifier que la réduction est disponible
        if (montantReduction > infos.reduction_disponible) {
            return { valide: false, message: 'Réduction supérieure à votre cagnotte disponible' };
        }
        
        // Vérifier que la réduction ne dépasse pas 50% du montant
        const reductionMaximale = montantCommande * 0.5;
        if (montantReduction > reductionMaximale) {
            return { valide: false, message: `La réduction ne peut pas dépasser ${this.formaterPrix(reductionMaximale)} (50% du montant)` };
        }
        
        return { valide: true };
    }

    /**
     * Formater un prix
     */
    formaterPrix(prix) {
        const prixArrondi = Math.round(prix * 100) / 100;
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(prixArrondi);
    }

    /**
     * Synchroniser avec le serveur (optionnel)
     */
    async synchroniserAvecServeur() {
        try {
            const fidelite = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
            const response = await fetch('/client/fidelite/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(fidelite)
            });
            
            if (response.ok) {
                console.log('Fidélité synchronisée avec le serveur');
            }
        } catch (error) {
            console.warn('Erreur lors de la synchronisation de la fidélité:', error);
        }
    }

    /**
     * Réinitialiser la fidélité (pour les tests)
     */
    reset() {
        localStorage.removeItem(this.storageKey);
        this.initFidelite();
        console.log('Fidélité réinitialisée');
    }
}

// Créer une instance globale
window.fideliteService = new FideliteService();
