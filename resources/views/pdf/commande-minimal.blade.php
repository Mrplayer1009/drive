<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bon de commande #{{ $commande->id ?? 'TEST' }}</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="content">
        <h2>Informations de base</h2>
        <table>
            <tr>
                <td><strong>ID Commande:</strong></td>
                <td>{{ $commande->id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Franchisé:</strong></td>
                <td>{{ $commande->franchise->nom ?? 'N/A' }} {{ $commande->franchise->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Entrepôt:</strong></td>
                <td>{{ $commande->entrepot->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $commande->date_commande ?? now() }}</td>
            </tr>
            <tr>
                <td><strong>Statut:</strong></td>
                <td>{{ $commande->statut ?? 'N/A' }}</td>
            </tr>
        </table>

        @if(isset($commande->produits) && $commande->produits->count() > 0)
        <h2>Produits</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->produits as $produit)
                <tr>
                    <td>{{ $produit->nom ?? 'N/A' }}</td>
                    <td>{{ $produit->pivot->quantite ?? 0 }}</td>
                    <td>{{ number_format($produit->pivot->prix_total ?? 0, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Aucun produit dans cette commande</p>
        @endif

        <h2>Totaux</h2>
        <table>
            <tr>
                <td><strong>Total obligatoire:</strong></td>
                <td>{{ number_format($commande->total_obligatoire ?? 0, 2) }} €</td>
            </tr>
            <tr>
                <td><strong>Total libre:</strong></td>
                <td>{{ number_format($commande->total_libre ?? 0, 2) }} €</td>
            </tr>
            <tr>
                <td><strong>Total général:</strong></td>
                <td><strong>{{ number_format($commande->total_commande ?? 0, 2) }} €</strong></td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #666;">
        <p>Driv'n Cook - Test PDF</p>
    </div>
</body>
</html>
