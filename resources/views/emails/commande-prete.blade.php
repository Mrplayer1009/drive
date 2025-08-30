<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre commande est prête !</title>
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
            background: linear-gradient(135deg, #f97316, #dc2626);
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
            border: 3px solid #f97316;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #f97316;
            letter-spacing: 5px;
        }
        .button {
            display: inline-block;
            background: #f97316;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍔 Votre commande est prête !</h1>
        <p>Commande #{{ $commande->id }} - {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</p>
    </div>

    <div class="content">
        <h2>Bonjour {{ $commande->client->nom }},</h2>
        
        <p>Votre commande est maintenant prête et vous pouvez venir la récupérer !</p>

        <div class="code-box">
            <h3>Votre code de récupération :</h3>
            <div class="code">{{ $codeRecuperation }}</div>
            <p><strong>Présentez ce code au food truck pour récupérer votre commande.</strong></p>
        </div>

        <h3>📋 Récapitulatif de votre commande :</h3>
        <ul>
            @foreach($commande->menus as $menu)
                <li>{{ $menu->nom }} (x{{ $menu->pivot->quantite }}) - {{ number_format($menu->pivot->prix_total, 2, ',', ' ') }} €</li>
            @endforeach
        </ul>

        <p><strong>Total : {{ number_format($commande->montant_final, 2, ',', ' ') }} €</strong></p>

        <h3>📍 Informations de récupération :</h3>
        <ul>
            <li><strong>Food Truck :</strong> {{ $commande->franchise->prenom }} {{ $commande->franchise->nom }}</li>
            <li><strong>Adresse :</strong> {{ $commande->adresse_livraison }}</li>
            <li><strong>Téléphone :</strong> {{ $commande->telephone_contact }}</li>
        </ul>

        <p><strong>⚠️ Important :</strong> N'oubliez pas d'apporter ce code avec vous. Sans ce code, nous ne pourrons pas vous remettre votre commande.</p>

        <p>Merci de votre confiance !</p>
        <p>L'équipe Driv'n Cook</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        <p>Driv'n Cook - Les meilleurs burgers de Paris</p>
    </div>
</body>
</html>

