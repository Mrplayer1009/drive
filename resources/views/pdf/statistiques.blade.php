<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistiques Driv'n Cook</title>
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
            border-bottom: 2px solid #f97316;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #f97316;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .stats-cell h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #f97316;
        }
        .stats-value {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .stats-subtitle {
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #f97316;
            font-size: 18px;
            border-bottom: 1px solid #f97316;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .evolution-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .evolution-cell {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .evolution-value {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .evolution-label {
            font-size: 11px;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        .table td {
            font-size: 10px;
        }
        .products-grid {
            display: table;
            width: 100%;
        }
        .products-row {
            display: table-row;
        }
        .products-cell {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-details {
            font-size: 9px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .positive { color: #10b981; }
        .negative { color: #ef4444; }
        .neutral { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Driv'n Cook</h1>
        <p>Rapport des Statistiques</p>
        <p>Généré le {{ $date_generation }}</p>
    </div>

    <!-- Statistiques générales -->
    <div class="section">
        <h2>Statistiques Générales</h2>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <h3>Franchisés</h3>
                    <div class="stats-value">{{ $total_franchises }}</div>
                    <div class="stats-subtitle">{{ $franchises_actifs }} actifs</div>
                </div>
                <div class="stats-cell">
                    <h3>CA Total</h3>
                    <div class="stats-value">{{ number_format($ca_total, 0, ',', ' ') }} €</div>
                    <div class="stats-subtitle @if($croissance_ca > 0) positive @elseif($croissance_ca < 0) negative @else neutral @endif">
                        @if($croissance_ca > 0)+@endif{{ $croissance_ca }}% ce mois
                    </div>
                </div>
                <div class="stats-cell">
                    <h3>Reversements</h3>
                    <div class="stats-value">{{ number_format($total_reverse, 0, ',', ' ') }} €</div>
                    <div class="stats-subtitle">{{ $pourcentage_reverse }}% du CA</div>
                </div>
                <div class="stats-cell">
                    <h3>Commandes</h3>
                    <div class="stats-value">{{ $total_commandes }}</div>
                    <div class="stats-subtitle">{{ $commandes_en_attente }} en attente</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution des ventes -->
    <div class="section">
        <h2>Évolution des Ventes</h2>
        <div class="evolution-grid">
            <div class="evolution-cell">
                <div class="evolution-label">Ce mois</div>
                <div class="evolution-value">{{ number_format($ca_mois_courant, 0, ',', ' ') }} €</div>
                <div class="evolution-label @if($croissance_ca > 0) positive @elseif($croissance_ca < 0) negative @else neutral @endif">
                    @if($croissance_ca > 0)+@endif{{ $croissance_ca }}%
                </div>
            </div>
            <div class="evolution-cell">
                <div class="evolution-label">Mois dernier</div>
                <div class="evolution-value">{{ number_format($ca_mois_precedent, 0, ',', ' ') }} €</div>
            </div>
            <div class="evolution-cell">
                <div class="evolution-label">Moyenne 6 mois</div>
                <div class="evolution-value">{{ number_format($ca_moyenne_6mois, 0, ',', ' ') }} €</div>
            </div>
        </div>
    </div>

    <!-- Reversements et Commandes -->
    <div class="section">
        <h2>Détails Financiers</h2>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <h3>Reversements</h3>
                    <div class="stats-value">{{ number_format($total_reverse, 0, ',', ' ') }} €</div>
                    <div class="stats-subtitle @if($evolution_reverse > 0) positive @elseif($evolution_reverse < 0) negative @else neutral @endif">
                        @if($evolution_reverse > 0)+@endif{{ $evolution_reverse }}% vs mois dernier
                    </div>
                </div>
                <div class="stats-cell">
                    <h3>Commandes</h3>
                    <div class="stats-value">{{ $total_commandes }}</div>
                    <div class="stats-subtitle @if($evolution_commandes > 0) positive @elseif($evolution_commandes < 0) negative @else neutral @endif">
                        @if($evolution_commandes > 0)+@endif{{ $evolution_commandes }}% vs mois dernier
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 des franchisés -->
    <div class="section">
        <h2>Top 10 des Franchisés</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Franchisé</th>
                    <th>Ville</th>
                    <th>Statut</th>
                    <th>CA Total</th>
                    <th>Reversements</th>
                    <th>Ventes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_franchises as $index => $franchise)
                <tr>
                    <td><strong>#{{ $index + 1 }}</strong></td>
                    <td>{{ $franchise->nom_complet }}</td>
                    <td>{{ $franchise->ville }}</td>
                    <td>{{ ucfirst($franchise->statut) }}</td>
                    <td>{{ number_format($franchise->ventes_sum_montant_total ?? 0, 0, ',', ' ') }} €</td>
                    <td>{{ number_format($franchise->ventes_sum_montant_reverse ?? 0, 0, ',', ' ') }} €</td>
                    <td>{{ $franchise->ventes_count ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Produits les plus commandés -->
    <div class="section">
        <h2>Produits les Plus Commandés</h2>
        <div class="products-grid">
            @foreach($produits_populaires->chunk(3) as $chunk)
            <div class="products-row">
                @foreach($chunk as $produit)
                <div class="products-cell">
                    <div class="product-name">{{ $produit->nom }}</div>
                    <div class="product-details">
                        <div>Commandes: {{ $produit->commandes_count ?? 0 }}</div>
                        <div>Prix: {{ $produit->prix_formate }}</div>
                        <div>Catégorie: {{ $produit->categorie_label }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <div class="footer">
        <p>Rapport généré automatiquement par le système Driv'n Cook</p>
        <p>© {{ date('Y') }} Driv'n Cook - Tous droits réservés</p>
    </div>
</body>
</html>
