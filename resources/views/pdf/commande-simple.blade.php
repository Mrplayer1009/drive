<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de commande #{{ $commande->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .products-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            margin-top: 30px;
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Bon de commande #{{ $commande->id }}</div>
        <div>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Franchisé :</td>
                <td>{{ $commande->franchise->nom ?? 'N/A' }} {{ $commande->franchise->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td class="info-label">Entrepôt :</td>
                <td>{{ $commande->entrepot->nom ?? 'N/A' }} - {{ $commande->entrepot->ville ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Date de commande :</td>
                <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="info-label">Statut :</td>
                <td>{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</td>
            </tr>
            @if($commande->notes)
            <tr>
                <td class="info-label">Notes :</td>
                <td>{{ $commande->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($commande->produits && $commande->produits->count() > 0)
    <div class="info-section">
        <h3>Produits commandés</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->produits as $produit)
                <tr>
                    <td>{{ $produit->nom ?? 'N/A' }}</td>
                    <td>{{ ucfirst($produit->categorie ?? 'N/A') }}</td>
                    <td>{{ $produit->pivot->quantite ?? 0 }} {{ $produit->unite_mesure ?? '' }}</td>
                    <td>{{ number_format($produit->pivot->prix_unitaire ?? 0, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($produit->pivot->prix_total ?? 0, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="total-section">
        <div>Total obligatoire : {{ number_format($commande->total_obligatoire ?? 0, 2, ',', ' ') }} €</div>
        <div>Total libre : {{ number_format($commande->total_libre ?? 0, 2, ',', ' ') }} €</div>
        <div style="font-size: 16px; font-weight: bold; color: #f39c12;">
            Total général : {{ number_format($commande->total_commande ?? 0, 2, ',', ' ') }} €
        </div>
    </div>

    <div class="footer">
        <p>Driv'n Cook - Système de gestion des franchises</p>
        <p>Ce document a été généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
