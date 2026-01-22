@extends('master')

@section('css')
<style>
    .card-title-icon {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-card {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
    }

    .status-card {
        min-height: 150px;
    }
</style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ================= BUSQUEDA ================= -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card search-card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search"></i> Seguimiento por folio
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Folio</label>
                            <input type="text" class="form-control" placeholder="xxx-xx/xx" id="txtFolio">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button id="btnBuscar" class="btn btn-info w-100">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>

                        <div class="col-md-7" id="divPuntos">
                            <label class="form-label">Punto de muestreo</label>
                            <select class="form-select" id="selPunto">
                                <option selected>No hay punto de muestreo</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ESTADOS ================= -->
    <div class="row g-4">

        <!-- FILA 1 -->
        {{-- <div class="col-md-4">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-file-invoice"></i> Cotización
                    </h6>
                </div>
                <div class="card-body" id="divCotizacion"></div>
            </div>
        </div> --}}

        <div class="col-md-4">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-clipboard-list"></i> Orden de servicio
                    </h6>
                </div>
                <div class="card-body" id="divOrden"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-vial"></i> Muestreo
                    </h6>
                </div>
                <div class="card-body" id="divMuestreo"></div>
            </div>
        </div>

        <!-- FILA 2 -->
        <div class="col-md-4">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-inbox"></i> Recepción
                    </h6>
                </div>
                <div class="card-body" id="divRecepcion"></div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-flask"></i> Ingreso al laboratorio
                    </h6>
                </div>
                <div class="card-body" id="divLab"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card status-card shadow-sm">
                <div class="card-header">
                    <h6 class="card-title-icon mb-0">
                        <i class="fas fa-print"></i> Impresión
                    </h6>
                </div>
                <div class="card-body" id="divImpresion"></div>
            </div>
        </div>

    </div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/6.0.0/echarts.min.js"
        integrity="sha512-4/g9GAdOdTpUP2mKClpKsEzaK7FQNgMjq+No0rX8XZlfrCGtbi4r+T/p5fnacsEC3zIAmHKLJUL7sh3/yVA4OQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('public/js/seguimiento.js') }}"></script>
@endsection
