<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande #{{ $commande->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon span {
            display: inline-block;
            width: 60px;
            height: 60px;
            background-color: #10b981;
            border-radius: 50%;
            color: white;
            font-size: 30px;
            line-height: 60px;
            text-align: center;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin-top: 0;
            color: #f97316;
            border-bottom: 2px solid #f97316;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .detail-row:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .items-section {
            margin: 20px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-weight: bold;
        }
        .item-details {
            color: #6b7280;
            font-size: 14px;
        }
        .item-price {
            text-align: right;
        }
        .total-section {
            background-color: #f97316;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
        }
        .next-steps {
            background-color: #dbeafe;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps h3 {
            color: #1e40af;
            margin-top: 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .step-number {
            background-color: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-right: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .step-text {
            color: #1e40af;
            line-height: 1.4;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .contact-info {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .contact-info h4 {
            margin-top: 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍔 Driv'n Cook</h1>
        <p>Confirmation de votre commande</p>
    </div>
    
    <div class="content">
        <div class="success-icon">
            <span>✓</span>
        </div>
        
        <h2>Bonjour {{ $commande->client->prenom }} {{ $commande->client->nom }},</h2>
        
        <p>Nous avons bien reçu votre commande et nous vous remercions pour votre confiance !</p>
        
        <div class="order-details">
            <h3>📋 Détails de votre commande</h3>
            
            <div class="detail-row">
                <span class="detail-label">Numéro de commande :</span>
                <span class="detail-value">#{{ $commande->id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Date de commande :</span>
                <span class="detail-value">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Statut :</span>
                <span class="detail-value">En attente de confirmation</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Méthode de paiement :</span>
                <span class="detail-value">{{ ucfirst($commande->mode_paiement) }}</span>
            </div>
        </div>
        
        <div class="items-section">
            <h3>🍽️ Articles commandés</h3>
            
            @foreach($commande->menus as $menu)
            <div class="item">
                <div>
                    <div class="item-name">{{ $menu->nom }}</div>
                    <div class="item-details">Quantité : {{ $menu->pivot->quantite }} × {{ number_format($menu->pivot->prix_unitaire, 2, ',', ' ') }} €</div>
                </div>
                <div class="item-price">
                    <strong>{{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €</strong>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="total-section">
            <div class="total-row">
                <span>Montant total :</span>
                <span>{{ number_format($commande->montant_final, 2, ',', ' ') }} €</span>
            </div>
        </div>
        
        <div class="contact-info">
            <h4>📍 Informations de récupération</h4>
            <p><strong>Food Truck :</strong> {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</p>
            <p><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</p>
            <p><strong>Téléphone :</strong> {{ $commande->telephone_contact }}</p>
        </div>
        
        <div class="next-steps">
            <h3>🔄 Prochaines étapes</h3>
            
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-text">Vous recevrez un email de confirmation avec les détails de votre commande</div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-text">Votre commande sera préparée et vous serez contacté pour le retrait de la commande</div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">Vous pouvez suivre l'état de votre commande dans votre espace client</div>
            </div>
        </div>
        
        <p><strong>Merci de votre confiance !</strong></p>
        <p>L'équipe Driv'n Cook</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement suite à votre commande.</p>
        <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
    </div>
</body>
</html>
