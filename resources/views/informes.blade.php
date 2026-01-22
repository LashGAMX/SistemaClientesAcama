@extends('master')
@section('css')
    <style>
        .table-scroll {
            max-height: 350px;
            /* límite visible */
            overflow-y: auto;
            /* scroll vertical */
            overflow-x: hidden;
            /* evita scroll horizontal raro */
        }

        .chat-container {
            max-height: 650px;
            overflow-y: auto;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .chat-msg {
            max-width: 85%;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .chat-ia {
            background: #e9f2ff;
            align-self: flex-start;
        }

        .chat-user {
            background: #d1f5e0;
            margin-left: auto;
            align-self: flex-end;
        }

        .chat-wrapper {
            display: flex;
            flex-direction: column;
        }

        /* Cards de resumen iguales */
        .summary-card {
            height: 140px;
            /* todos del mismo alto */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-card .card-body {
            padding: 12px;
        }

        /* Texto más compacto */
        .summary-card h6 {
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .summary-card h4 {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        .summary-card small {
            font-size: 0.75rem;
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        /* ===== Scrollbar grande (Chrome, Edge, Safari) ===== */
#main-container::-webkit-scrollbar {
    width: 16px;              /* ancho de la barra */
}

#main-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

#main-container::-webkit-scrollbar-thumb {
    background-color: #b5b5b5;
    border-radius: 8px;
    border: 3px solid #f1f1f1;
}

#main-container::-webkit-scrollbar-thumb:hover {
    background-color: #8f8f8f;
}
/* CONTENEDOR PRINCIPAL */
/* CONTENEDOR DE SCROLL POR GRAFICA */
.chart-scroll {
    max-height: 520px;
    max-width: 100%;
    overflow: auto;
    padding: 8px;
}

/* Tamaño REAL de la gráfica */
.chart-canvas {
    min-width: 900px;   /* fuerza scroll horizontal */
    height: 650px;      /* fuerza scroll vertical */
}

/* ===== SCROLL GRANDE ===== */
.chart-scroll::-webkit-scrollbar {
    width: 14px;
    height: 14px;
}

.chart-scroll::-webkit-scrollbar-thumb {
    background-color: #198754;
    border-radius: 10px;
}

.chart-scroll::-webkit-scrollbar-track {
    background-color: #e9ecef;
}

/* Firefox */
.chart-scroll {
    scrollbar-width: auto;
    scrollbar-color: #198754 #e9ecef;
}

/* THEAD fijo */
#tabInforme thead {
    display: table;
    width: 100%;
    table-layout: fixed;
}

/* TBODY con scroll */
#tabInforme .tbody-scroll {
    display: block;
    max-height: 350px;   /* <-- ajusta la altura */
    overflow-y: auto;
    width: 100%;
}

/* Mantener columnas alineadas */
#tabInforme tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}


    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">

        <div class="col-md-12 col-lg-8">
            <div class="card" data-aos="fade-up" data-aos-delay="800">
                <div class="card-header">

                    <h6>Buscar folio</h6>
                </div>

                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="folio" placeholder="Folio de servicio Ejemplo: XX-XX/XX-X"
                            aria-label="Folio de servicio" aria-describedby="button-addon2">
                        <button class="btn btn-success" type="button" id="btnBuscar">Buscar</button>
                    </div>
                </div>

            </div>

            <!-- SECCION RESUMEN -->
            <!-- SECCION RESUMEN -->
            <div class="row mb-4">

                <!-- Cumplimiento NOM -->
                <div class="col-md-6">
                    <div class="card text-center shadow-sm summary-card">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="summary-icon bg-success">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">Cumplimiento NOM</h6>
                            <h4 class="fw-bold mb-0" id="txtCumple"></h4>
                            <small class="text-success">Cumple</small>
                        </div>
                    </div>
                </div>

                <!-- Estado de Riesgo -->
                <div class="col-md-6">
                    <div class="card text-center shadow-sm summary-card">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="summary-icon bg-danger">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">Estado de Riesgo</h6>
                            <h4 class="fw-bold mb-0" id="txtCritico">Crítico</h4>
                        </div>
                    </div>
                </div>

                {{-- <!-- Tendencia -->
                <div class="col-md-3">
                    <div class="card text-center shadow-sm summary-card">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="summary-icon bg-warning">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">Tendencia Actual</h6>
                            <h4 class="fw-bold mb-0 text-warning">Al alza</h4>
                        </div>
                    </div>
                </div>

                <!-- Muestra -->
                <div class="col-md-3">
                    <div class="card text-center shadow-sm summary-card">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="summary-icon bg-primary">
                                    <i class="fa-solid fa-flask-vial"></i>
                                </span>
                            </div>
                            <h6 class="mb-1">Muestra Seleccionada</h6>
                            <h4 class="fw-bold mb-0">Muestra 105</h4>
                        </div>
                    </div>
                </div> --}}

            </div>


            <div class="card overflow-hidden">
                <div class="card-header d-flex justify-content-between flex-wrap">
                    <div class="header-title">
                        <h4 class="card-title mb-2">Folios registrados</h4>
                        <p class="mb-0" id="datosFolio"></p>
                    </div>
                </div>

           <div class="card-body p-0">
    <div class="table-responsive mt-4">
        <table class="table table-sm table-striped mb-0" id="tabInforme">
            <thead>
                <tr>
                    <th>Parametro</th>
                    <th>Resultado</th>
                    <th>Comparado</th>
                    <th>Declaracion</th>
                </tr>
            </thead>
            <tbody id="informe" class="tbody-scroll"></tbody>
        </table>
    </div>
</div>

            </div>


        </div>

        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="user-post-data mb-3">
                        <div class="d-flex align-items-center">
                            <div class="media-support-user-img me-3">
                                <img class="rounded-circle p-1 bg-soft-danger img-fluid avatar-60"
                                    src="{{ asset('public/assets/images/avatars/02.png') }}" alt="">
                            </div>
                            <div>
                                <h5 class="mb-0">Interpretación de resultados por IA</h5>
                                <small class="text-muted">Asistente técnico</small>
                            </div>
                        </div>
                    </div>

                    <!-- Chat -->
                    <div id="chatContainer" class="chat-container mb-3">
                        <!-- Mensajes IA / Usuario -->
                    </div>

                    <!-- Input -->
                    <div class="input-group">
                        <input type="text" class="form-control" id="chatInput"
                            placeholder="Escribe una pregunta sobre los resultados…">
                        <button class="btn btn-primary" id="sendChat">
                            <i class="ti ti-send"></i>
                        </button>
                    </div>

                </div>
            </div>

        </div>

<div class="col-md-12 col-lg-12">
    <div class="row">

        <!-- CARD GRAFICA LINEA -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Comportamiento de los resultados</h6>
                </div>
                <div class="card-body p-0">

                    <!-- SCROLL SOLO DE ESTA GRAFICA -->
                    <div class="chart-scroll">
                        <div id="line-container" class="chart-canvas"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- CARD GRAFICA BARRAS -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Comparación por parámetro</h6>
                </div>
                <div class="card-body p-0">

                    <!-- SCROLL SOLO DE ESTA GRAFICA -->
                    <div class="chart-scroll">
                        <div id="bar-container" class="chart-canvas"></div>
                    </div>

                </div>
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
    <script src="{{ asset('public/js/informes.js') }}"></script>
@endsection
