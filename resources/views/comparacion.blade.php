@extends('master')
@section('css')
@endsection

@section('content')
<div class="conatiner-fluid content-inner pb-0">
    <div class="row">

        <div class="col-md-12 col-lg-12" style="display: flex">
            <div class="input-group mb-3">
                <div class="form-group">
                    <label class="form-label">Clientes</label>
                    <select class="form-select form-select-sm mb-3 shadow-none" id="sucursal">
                        <option selected="">Seleciona una opción</option>
                        @foreach ($sucursal as $item)
                            <option value="{{$item->Id_sucursal}}">{{$item->Empresa}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
             <div class="input-group mb-3">
                <div class="form-group">
                    <label class="form-label">Punto de muestreo</label>
                    <select class="form-select form-select-sm mb-3 shadow-none" id="punto">
                        <option selected="">Seleciona una opción</option>
                    </select>
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;
             <div class="input-group mb-2">
                 <div class="form-group">
                    <label class="form-label" for="exampleInputdate">Fecha inicio</label>
                    <input type="date" class="form-control" id="fechaIni">
                </div>
            </div>
            <div class="input-group mb-2">
                 <div class="form-group">
                    <label class="form-label" for="exampleInputdate">Fecha fin</label>
                    <input type="date" class="form-control" id="fechaFin" >
                </div>
            </div>
            <div class="input-group mb-2">
                 <div class="form-group">
                    <label class="form-label" for="exampleInputdate">Tipo comparacion</label>
                    <select id="tipoGrafica" class="form-select mb-2">
                        <option value="bar-horizontal">Barras horizontales</option>
                        <option value="bar-vertical">Barras verticales</option>
                        <option value="line">Líneas</option>
                        <option value="area">Área</option>
                        <option value="stack">Barras apiladas</option>
                        <option value="radar">Radar</option>
                    </select>
                </div>
            </div>
           <div class="input-group mb-2">
                 <div class="form-group">
                    <br>
                    <button type="button" id="btnComparar" class="btn btn-primary">Comparar</button>
                </div>
            </div>
           
        </div>

     
        <div class="col-md-12 col-lg-12">
            <div id="main-container" style="max-height: 800px; overflow-y: auto;">
                
                <div id="main" style="width: 100%; height: 1000px;"></div>
            </div>
        </div>

      
     
    </div>

    @endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/6.0.0/echarts.min.js" integrity="sha512-4/g9GAdOdTpUP2mKClpKsEzaK7FQNgMjq+No0rX8XZlfrCGtbi4r+T/p5fnacsEC3zIAmHKLJUL7sh3/yVA4OQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('public/js/comparacion.js')}}"></script>
@endsection