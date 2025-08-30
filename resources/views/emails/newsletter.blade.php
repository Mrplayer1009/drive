<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sujet }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .newsletter-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ff6b6b;
            margin: 20px 0;
        }
        .newsletter-content h2 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 22px;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #ff6b6b;
            text-decoration: none;
        }
        .unsubscribe {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #34495e;
            font-size: 12px;
            opacity: 0.8;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍔 Driv'n Cook</h1>
            <p>Les meilleurs burgers de Paris</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Bonjour {{ $client->prenom ? $client->prenom : $client->nom }},
            </div>
            
            <div class="newsletter-content">
                {!! nl2br(e($contenu)) !!}
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('client.index') }}" class="cta-button">
                    Commander maintenant
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Driv'n Cook</strong> - Les meilleurs burgers de Paris</p>
            <p>📍 Food trucks dans toute la ville</p>
            <p>📧 contact@drivncook.fr | 📞 01 23 45 67 89</p>
            
            <div class="unsubscribe">
                <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
                <p>Pour vous désabonner, <a href="{{ route('client.profile') }}">modifiez vos préférences</a> dans votre profil.</p>
            </div>
        </div>
    </div>
</body>
</html>
