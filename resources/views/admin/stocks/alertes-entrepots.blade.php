@extends('layouts.admin')

@section('title', 'Alertes de Stock - Entrepôts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Alertes de Stock - Entrepôts
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($alertes) > 0)
                        @foreach($alertes as $alerte)
                            <div class="card mb-3 border-{{ $alerte['produits_en_rupture']->count() > 0 ? 'danger' : 'warning' }}">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-warehouse"></i>
                                        Entrepôt : {{ $alerte['entrepot']->nom }}
                                        <span class="badge badge-{{ $alerte['produits_en_rupture']->count() > 0 ? 'danger' : 'warning' }} ml-2">
                                            {{ $alerte['produits_en_rupture']->count() + $alerte['produits_stock_insuffisant']->count() }} alertes
                                        </span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($alerte['produits_en_rupture']->count() > 0)
                                        <div class="alert alert-danger">
                                            <h6><i class="fas fa-times-circle"></i> Produits en rupture de stock</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Produit</th>
                                                            <th>Catégorie</th>
                                                            <th>Stock actuel</th>
                                                            <th>Stock minimum</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($alerte['produits_en_rupture'] as $stock)
                                                            <tr>
                                                                <td>{{ $stock->produit->nom }}</td>
                                                                <td>
                                                                    <span class="badge badge-info">{{ $stock->produit->categorie_label }}</span>
                                                                </td>
                                                                <td class="text-danger font-weight-bold">{{ number_format($stock->quantite_stock, 2) }}</td>
                                                                <td>{{ number_format($stock->stock_minimum, 2) }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.entrepots.stocks.show', [$alerte['entrepot']->id, $stock->produit_id]) }}" 
                                                                       class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i> Voir
                                                                    </a>
                                                                    <a href="{{ route('admin.entrepots.stocks.edit', [$alerte['entrepot']->id, $stock->id]) }}" 
                                                                       class="btn btn-warning btn-sm">
                                                                        <i class="fas fa-edit"></i> Modifier
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    @if($alerte['produits_stock_insuffisant']->count() > 0)
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-exclamation-triangle"></i> Produits avec stock insuffisant</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Produit</th>
                                                            <th>Catégorie</th>
                                                            <th>Stock actuel</th>
                                                            <th>Stock minimum</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($alerte['produits_stock_insuffisant'] as $stock)
                                                            <tr>
                                                                <td>{{ $stock->produit->nom }}</td>
                                                                <td>
                                                                    <span class="badge badge-info">{{ $stock->produit->categorie_label }}</span>
                                                                </td>
                                                                <td class="text-warning font-weight-bold">{{ number_format($stock->quantite_stock, 2) }}</td>
                                                                <td>{{ number_format($stock->stock_minimum, 2) }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.entrepots.stocks.show', [$alerte['entrepot']->id, $stock->produit_id]) }}" 
                                                                       class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i> Voir
                                                                    </a>
                                                                    <a href="{{ route('admin.entrepots.stocks.edit', [$alerte['entrepot']->id, $stock->id]) }}" 
                                                                       class="btn btn-warning btn-sm">
                                                                        <i class="fas fa-edit"></i> Modifier
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="text-right">
                                        <a href="{{ route('admin.entrepots.stocks.index', $alerte['entrepot']->id) }}" 
                                           class="btn btn-primary">
                                            <i class="fas fa-boxes"></i> Gérer tous les stocks
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Aucune alerte de stock</h5>
                            <p class="text-muted">Tous les entrepôts ont des stocks suffisants.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

