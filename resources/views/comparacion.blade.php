@extends('master')
@section('css')
    <style>
        .scroll-grafica::-webkit-scrollbar {
            width: 14px;
            height: 14px;
        }

        .scroll-grafica::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scroll-grafica::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .scroll-grafica::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <style>
        /* Overlay para cubrir toda la pantalla */
        #loading-spinner {
            position: absolute;
            /* 🔑 NO fixed */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            z-index: 10;

            display: flex;
            /* 🔑 FLEX */
            justify-content: center;
            /* 🔑 CENTRADO HORIZONTAL */
            align-items: center;
            /* 🔑 CENTRADO VERTICAL */
        }

        .loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;

            /* oculto por defecto */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
        }

        /* cuando se muestra */
        .loader-overlay.active {
            opacity: 1;
            visibility: visible;
        }



        /* Grid de cubos */
        .sk-cube-grid {
            width: 60px;
            height: 60px;
        }

        /* Limpia los floats */
        .sk-cube-grid::after {
            content: "";
            display: block;
            clear: both;
        }

        .sk-cube-grid .sk-cube {
            width: 33%;
            height: 33%;
            background-color: #007bff;
            /* Azul Bootstrap para que se vea mejor */
            float: left;
            -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
            animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
        }

        .sk-cube-grid .sk-cube1 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        .sk-cube-grid .sk-cube2 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s;
        }

        .sk-cube-grid .sk-cube3 {
            -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s;
        }

        .sk-cube-grid .sk-cube4 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s;
        }

        .sk-cube-grid .sk-cube5 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        .sk-cube-grid .sk-cube6 {
            -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s;
        }

        .sk-cube-grid .sk-cube7 {
            -webkit-animation-delay: 0s;
            animation-delay: 0s;
        }

        .sk-cube-grid .sk-cube8 {
            -webkit-animation-delay: 0.1s;
            animation-delay: 0.1s;
        }

        .sk-cube-grid .sk-cube9 {
            -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
        }

        @-webkit-keyframes sk-cubeGridScaleDelay {

            0%,
            70%,
            100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            }

            35% {
                -webkit-transform: scale3D(0, 0, 1);
                transform: scale3D(0, 0, 1);
            }
        }

        @keyframes sk-cubeGridScaleDelay {

            0%,
            70%,
            100% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1);
            }

            35% {
                -webkit-transform: scale3D(0, 0, 1);
                transform: scale3D(0, 0, 1);
            }
        }
    </style>
@endsection

@section('content')
    <div class="conatiner-fluid content-inner pb-0">
        <div class="row">

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filtros de comparación</h5>
                    </div>

                    <div class="card-body">
                        <div class="row g-3 align-items-end">

                            <!-- Clientes -->
                            <div class="col-md-3">
                                <label class="form-label">Clientes</label>
                                <select class="form-select form-select-sm shadow-none" id="sucursal">
                                    <option selected>Selecciona una opción</option>
                                    @foreach ($sucursal as $item)
                                        <option value="{{ $item->Id_sucursal }}">{{ $item->Empresa }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Punto de muestreo -->
                            <div class="col-md-3">
                                <label class="form-label">Punto de muestreo</label>
                                <select class="form-select form-select-sm shadow-none" id="punto">
                                    <option selected>Selecciona una opción</option>
                                </select>
                            </div>

                            <!-- Fecha inicio -->
                            <div class="col-md-2">
                                <label class="form-label">Fecha inicio</label>
                                <input type="date" class="form-control form-control-sm" id="fechaIni">
                            </div>

                            <!-- Fecha fin -->
                            <div class="col-md-2">
                                <label class="form-label">Fecha fin</label>
                                <input type="date" class="form-control form-control-sm" id="fechaFin">
                            </div>

                            <!-- Tipo comparación -->
                            {{-- <div class="col-md-2">
                                <label class="form-label">Tipo comparación</label>
                                <select id="tipoGrafica" class="form-select form-select-sm">
                                    <option value="bar-horizontal">Barras horizontales</option>
                                    <option value="bar-vertical">Barras verticales</option>
                                    <option value="line">Líneas</option>
                                    <option value="area">Área</option>
                                    <option value="stack">Barras apiladas</option>
                                    <option value="radar">Radar</option>
                                </select>
                            </div> --}}

                            <!-- Botón -->
                            <div class="col-md-12 text-end">
                                <button type="button" id="btnComparar" class="btn btn-primary">
                                    Comparar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div id="main-container" class="scroll-grafica" style="max-height:800px; overflow:auto;">

                    <!-- CARD: STACKED LINE -->
                    <div class="card shadow-sm mb-4 position-relative">

                        <!-- LOADER -->
                        <div id="loaderComparar" class="loader-overlay">
                            <div class="text-center">
                                <div class="spinner-border text-success mb-3" role="status"></div>
                                <div class="fw-semibold text-muted">Cargando información...</div>
                            </div>
                        </div>

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Resultados vs Límites</h5>
                            <span class="badge bg-primary">Stacked Line</span>
                        </div>

                        <div class="card-body">

                            <!-- GRÁFICA -->
                            <div id="chartLine" style="width:1400px; height:600px;"></div>

                            <hr class="my-4">

                 <!-- COMENTARIO IA -->
<div class="ia-comment">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-robot fs-4 text-success me-2"></i>
        <h6 class="mb-0 fw-bold">Análisis automático</h6>
    </div>

    <p id="iaComentario" class="mb-0 text-muted">
        Esperando análisis...
    </p>
</div>


                        </div>
                    </div>

                    <!-- CARD: BARRAS -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Comparación por Parámetro</h5>
                            <span class="badge bg-success">Barras</span>
                        </div>

                        <div class="card-body">
                            <div id="chartBar" style="width:1400px; height:800px;"></div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
    @endsection

    @section('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/6.0.0/echarts.min.js"
            integrity="sha512-4/g9GAdOdTpUP2mKClpKsEzaK7FQNgMjq+No0rX8XZlfrCGtbi4r+T/p5fnacsEC3zIAmHKLJUL7sh3/yVA4OQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('public/js/comparacion.js') }}"></script>
    @endsection
