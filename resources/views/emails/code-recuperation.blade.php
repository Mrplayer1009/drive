<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de récupération - Commande #{{ $commande->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .code-box {
            background: #fff;
            border: 3px solid #ff6b35;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 48px;
            font-weight: bold;
            color: #ff6b35;
            letter-spacing: 10px;
            font-family: 'Courier New', monospace;
        }
        .info-box {
            background: #e8f4fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #ff6b35;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍔 Votre commande est prête !</h1>
        <p>Commande #{{ $commande->id }}</p>
    </div>
    
    <div class="content">
        <h2>Bonjour {{ $commande->client->prenom }} {{ $commande->client->nom }},</h2>
        
        <p>Votre commande est maintenant <strong>prête à être récupérée</strong> !</p>
        
        <div class="code-box">
            <h3>Votre code de récupération :</h3>
            <div class="code">{{ $code }}</div>
            <p><em>Donnez ce code oralement au franchisé pour récupérer votre commande</em></p>
        </div>
        
        <div class="info-box">
            <h4>📍 Informations de récupération :</h4>
            <p><strong>Food Truck :</strong> {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</p>
            <p><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</p>
            <p><strong>Montant :</strong> {{ number_format($commande->montant_final, 2, ',', ' ') }} €</p>
        </div>
        
        <h3>📋 Détails de votre commande :</h3>
        <ul>
            @foreach($commande->menus as $menu)
                <li>{{ $menu->nom }} - Quantité : {{ $menu->pivot->quantite }}</li>
            @endforeach
        </ul>
        
        <div class="info-box">
            <h4>⚠️ Important :</h4>
            <ul>
                <li>Ce code est valable uniquement pour cette commande</li>
                <li>Donnez-le oralement au franchisé pour récupérer votre commande</li>
                <li>Ne partagez pas ce code avec d'autres personnes</li>
            </ul>
        </div>
        
        <p>Merci de votre confiance !</p>
        <p><strong>L'équipe Driv'n Cook</strong></p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement suite à la préparation de votre commande.</p>
        <p>Si vous avez des questions, contactez-nous.</p>
    </div>
</body>
</html>
