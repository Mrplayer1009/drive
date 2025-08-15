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
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            border-bottom: 1px solid #eee;
        }
        .info-cell {
            padding: 8px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        .info-value {
            width: 70%;
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
        .total-row {
            margin-bottom: 10px;
        }
        .total-label {
            font-weight: bold;
            margin-right: 10px;
        }
        .total-value {
            font-size: 14px;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #f39c12;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f8f9fa;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Driv'n Cook</div>
        <div class="title">Bon de commande #{{ $commande->id }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="info-section">
        <table class="info-grid">
            <tr class="info-row">
                <td class="info-cell info-label">Franchisé :</td>
                <td class="info-cell info-value">{{ $commande->franchise->nom }} {{ $commande->franchise->prenom }}</td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Entrepôt :</td>
                <td class="info-cell info-value">{{ $commande->entrepot->nom }} - {{ $commande->entrepot->ville }}</td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Date de commande :</td>
                <td class="info-cell info-value">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Statut :</td>
                <td class="info-cell info-value">
                    <span class="status-badge">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                </td>
            </tr>
            @if($commande->notes)
            <tr class="info-row">
                <td class="info-cell info-label">Notes :</td>
                <td class="info-cell info-value">{{ $commande->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($commande->produits->count() > 0)
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
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ ucfirst($produit->categorie) }}</td>
                    <td>{{ $produit->pivot->quantite ?? 0 }} {{ $produit->unite_mesure }}</td>
                    <td>{{ number_format($produit->pivot->prix_unitaire ?? 0, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($produit->pivot->prix_total ?? 0, 2, ',', ' ') }} €</td>
                    <td>{{ $produit->obligatoire ? 'Obligatoire' : 'Libre' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Total obligatoire :</span>
            <span class="total-value">{{ number_format($commande->total_obligatoire, 2, ',', ' ') }} €</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total libre :</span>
            <span class="total-value">{{ number_format($commande->total_libre, 2, ',', ' ') }} €</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">Total général :</span>
            <span class="total-value">{{ number_format($commande->total_commande, 2, ',', ' ') }} €</span>
        </div>
    </div>

    <div class="footer">
        <p>Driv'n Cook - Système de gestion des franchises</p>
        <p>Ce document a été généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html> 
