@extends('master')
@section('css')

@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="conatiner-fluid content-inner pb-0">
    <div class="row">

        <div class="col-md-12 col-lg-8">
            <div class="input-group mb-3">
                    <input type="text" class="form-control" id="folio" placeholder="Folio de servicio" aria-label="Folio de servicio" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="btnBuscar">Buscar</button>
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
                        <table class="table table-sm table table-striped mb-0" role="grid" id="tabInforme">
                            <thead>
                                <tr>
                                    <th>Parametro</th>
                                    <th>Resultado</th>
                                    <th>Comparado</th>
                                    <th>Declaracion</th>
                                </tr>
                            </thead>
                            <tbody id="informe">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-md-12 col-lg-4">
             <div class="card">
                    <div class="card-body">
                        <div class="user-post-data">
                            <div class="d-flex flex-wrap">
                                <div class="media-support-user-img me-3">
                                    <img class="rounded-circle p-1 bg-soft-danger img-fluid avatar-60" src="{{asset('public/assets/images/avatars/02.png')}}" alt="">
                                </div>
                                <div class="media-support-info mt-2">
                                    <h5 class="mb-0 d-inline-block">Interpretacion de resultados por IA</h5>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 overflow-auto" id="resumenia" style="max-height: 100%;">
    </div>
                        
                    </div>
                </div>
         </div>


 <div class="col-md-12 col-lg-12">
    <button onclick="descargarGrafica()" class="btn btn-outline-success"><i class="fas fa-image"></i> Descargar gráfica</button>

    <div id="main-container" style="max-height: 500px; overflow-y: auto;">
        
        <div id="main" style="width: 100%; height: 800px;"></div>
    </div>
</div>
    </div>

    @endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/6.0.0/echarts.min.js" integrity="sha512-4/g9GAdOdTpUP2mKClpKsEzaK7FQNgMjq+No0rX8XZlfrCGtbi4r+T/p5fnacsEC3zIAmHKLJUL7sh3/yVA4OQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('public/js/informes.js')}}"></script>
@endsection