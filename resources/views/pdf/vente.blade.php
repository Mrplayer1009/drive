<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de vente #{{ $vente->id }}</title>
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
        .financial-section {
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .financial-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .financial-row {
            border-bottom: 1px solid #dee2e6;
        }
        .financial-cell {
            padding: 12px 8px;
            vertical-align: top;
        }
        .financial-label {
            font-weight: bold;
            width: 50%;
        }
        .financial-value {
            width: 50%;
            text-align: right;
            font-size: 14px;
        }
        .total-row {
            font-size: 16px;
            font-weight: bold;
            color: #f39c12;
            border-top: 2px solid #dee2e6;
        }
        .reverse-row {
            background-color: #d4edda;
            color: #155724;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .date-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            font-size: 10px;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Driv'n Cook</div>
        <div class="title">Rapport de vente #{{ $vente->id }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="info-section">
        <table class="info-grid">
            <tr class="info-row">
                <td class="info-cell info-label">Franchisé :</td>
                <td class="info-cell info-value">{{ $vente->franchise->nom }} {{ $vente->franchise->prenom }}</td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Date de vente :</td>
                <td class="info-cell info-value">
                    <span class="date-badge">{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</span>
                </td>
            </tr>
            @if($vente->camion)
            <tr class="info-row">
                <td class="info-cell info-label">Camion utilisé :</td>
                <td class="info-cell info-value">{{ $vente->camion->immatriculation }} ({{ $vente->camion->marque }} {{ $vente->camion->modele }})</td>
            </tr>
            @endif
            <tr class="info-row">
                <td class="info-cell info-label">Nombre de commandes :</td>
                <td class="info-cell info-value">{{ $vente->nombre_commandes }}</td>
            </tr>
            @if($vente->notes)
            <tr class="info-row">
                <td class="info-cell info-label">Notes :</td>
                <td class="info-cell info-value">{{ $vente->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="financial-section">
        <h3>Détails financiers</h3>
        <table class="financial-grid">
            <tr class="financial-row">
                <td class="financial-cell financial-label">Montant total des ventes :</td>
                <td class="financial-cell financial-value">{{ number_format($vente->montant_total, 2, ',', ' ') }} €</td>
            </tr>
            <tr class="financial-row reverse-row">
                <td class="financial-cell financial-label">Montant reversé ({{ $vente->franchise->pourcentage_ventes }}%) :</td>
                <td class="financial-cell financial-value">{{ number_format($vente->montant_reverse, 2, ',', ' ') }} €</td>
            </tr>
            <tr class="financial-row total-row">
                <td class="financial-cell financial-label">Montant net pour le franchisé :</td>
                <td class="financial-cell financial-value">{{ number_format($vente->montant_total - $vente->montant_reverse, 2, ',', ' ') }} €</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h3>Informations complémentaires</h3>
        <table class="info-grid">
            <tr class="info-row">
                <td class="info-cell info-label">Pourcentage de reversement :</td>
                <td class="info-cell info-value">{{ $vente->franchise->pourcentage_ventes }}%</td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Moyenne par commande :</td>
                <td class="info-cell info-value">
                    @if($vente->nombre_commandes > 0)
                        {{ number_format($vente->montant_total / $vente->nombre_commandes, 2, ',', ' ') }} €
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr class="info-row">
                <td class="info-cell info-label">Performance :</td>
                <td class="info-cell info-value">
                    @if($vente->montant_total > 0)
                        {{ number_format(($vente->montant_reverse / $vente->montant_total) * 100, 1) }}% de reversement
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Driv'n Cook - Système de gestion des franchises</p>
        <p>Ce rapport a été généré automatiquement le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
        <p>Document confidentiel - Usage interne uniquement</p>
    </div>
</body>
</html> 
