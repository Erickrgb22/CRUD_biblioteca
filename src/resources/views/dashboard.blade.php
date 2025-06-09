@extends('layouts.app') {{-- Le dice a esta vista que use la plantilla 'layouts.app' --}}

@section('title', 'Dashboard') {{-- Define el título específico para esta página --}}

@section('content') {{-- Aquí empieza el contenido que se inyectará en el layout --}}
    <h1 class="mb-4">Dashboard</h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Total de Libros</h5>
                        <p class="card-text fs-1 fw-bold text-primary">{{ $totalBooks }}</p>
                    </div>
                    <div class="fs-1 text-primary">
                        📚
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Total de Ejemplares</h5>
                        <p class="card-text fs-1 fw-bold text-success">{{ $totalExemplars }}</p>
                    </div>
                    <div class="fs-1 text-success">
                        📖
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Ejemplares Disponibles</h5>
                        <p class="card-text fs-1 fw-bold text-info">{{ $availableExemplars }}</p>
                    </div>
                    <div class="fs-1 text-info">
                        ✅
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-muted">Ejemplares Atrasados</h5>
                        <p class="card-text fs-1 fw-bold text-danger">{{ $overdueExemplars }}</p>
                    </div>
                    <div class="fs-1 text-danger">
                        ⏰
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection