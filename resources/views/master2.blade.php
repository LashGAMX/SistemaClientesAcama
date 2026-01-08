<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Órdenes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
     @yield('css')
  <link rel="stylesheet" href="{{asset('public/css/home.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>


  <!-- Barra de navegación -->
<!-- Barra de navegación -->
<header class="navbar">
  <div class="logo">
    <img src="http://sistemasofia.ddns.net:86/sofia/public/storage/Acama_Imagotipo.png" alt="Logo de la empresa">
  </div>
  <nav>
    <a href="#">Inicio</a>
    <a href="#">Mis órdenes</a>
    <a href="{{url('dashboard/informes')}}">Informes</a>
    <a href="#">Consultas IA</a>
    <a href="#">Perfil</a>
    <a href="{{url('logout')}}">Cerrar sesión</a>
  </nav>
</header>

   @yield('content')

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <script>
        var usuario = "{{ session('User') }}";
        var idUsuario = "{{ session('Id_cliente') }}";
        var base_url = "{{url('')}}"; 
     </script>
       <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
   @yield('js')
</body>
</html>
