

<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>ACAMA | Clientes</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('public/logo.png') }}" type="image/png">
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{asset('public/assets/css/core/libs.min.css')}}">
      
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/hope-ui.min.css?v=4.0.0')}}">
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/custom.min.css?v=4.0.0')}}"> 
      <link rel="stylesheet" href="{{asset('public/assets/css/acama-custom.css')}}">
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/dark.min.css')}}">
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/customizer.min.css')}}">
      <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
/>

      <!-- RTL Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/rtl.min.css')}}">
        <style>
          body {
              background-color: #0A3640 !important;
          }
      </style>
       @yield('css')
  </head>
  <body class="boxed">
    <div class="boxed-inner">
      <!-- loader Start -->
      <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body">
            </div>
        </div>      </div>
      <!-- loader END -->
      <span class="screen-darken"></span>
      <main class="main-content">
        <!--Nav Start-->
        <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
          <div class="container-fluid navbar-inner">
            <button data-trigger="navbar_main" class="d-lg-none btn btn-primary rounded-pill p-1 pt-0" type="button">
              <svg class="icon-20" width="20px"  viewBox="0 0 24 24">
                <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"></path>
            </svg>
            </button>
            <a href="../dashboard/index.html" class="navbar-brand">
                <!--Logo start-->
            
                <img src="{{asset('public/storage/acama.png')}}" class="img-fluid" alt="">
                <!--logo End-->        <h4 class="logo-title">Clientes ACAMA</h4>
            </a>
            <!-- Horizontal Menu Start -->
            <nav id="navbar_main" class="mobile-offcanvas nav navbar navbar-expand-xl hover-nav horizontal-nav mx-md-auto">
            <div class="container-fluid">
               <div class="offcanvas-header px-0">
                  <div class="navbar-brand ms-3">
                     
                     <!--Logo start-->
                     <div class="logo-main">
                         <div class="logo-normal">
                             <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                 <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                 <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                 <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                             </svg>
                         </div>
                         <div class="logo-mini">
                             <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                 <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                 <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                 <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                             </svg>
                         </div>
                     </div>
                     <!--logo End-->
                     
                     
                     
                     
                     <!-- <h4 class="logo-title">Hope UI</h4> -->
                  </div>
                  <button class="btn-close float-end"></button>
               </div>
               <ul class="navbar-nav">
                  <li class="nav-item"><a class="nav-link " href="{{url('/dashboard/home')}}"> Inicio </a></li>
                  <li class="nav-item"><a class="nav-link " href="{{url('/dashboard/informes')}}"><span class="item-name">Informes</span></a></li>
                  <li class="nav-item"><a class="nav-link " href="{{url('/dashboard/comparacion')}}"><span class="item-name">Comparación</span></a></li>
                  <li class="nav-item"><a class="nav-link " href="{{url('/dashboard/seguimiento')}}"><span class="item-name">Seguimiento</span></a></li>
                  <!-- <li class="nav-item"><a class="nav-link " href="{{url('logout')}}"> Cerrar sesión</a></li> -->
               </ul>
            </div> <!-- container-fluid.// -->
            </nav>
            <!-- Sidebar Menu End -->    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon">
                  <span class="navbar-toggler-bar bar1 mt-2"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
             
                <li class="nav-item dropdown">
                  <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{asset('/public/img/perfil.png')}}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                    <div class="caption ms-3 d-none d-md-block">
                        <h6 class="mb-0 caption-title">{{session('User')}}</h6>
                        <p class="mb-0 caption-sub-title"></p>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../dashboard/app/user-profile.html">Perfil</a></li>
                    <li><a class="dropdown-item" href="../dashboard/app/user-privacy-setting.html">Aviso de privacidad</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{url('logout')}}">Cerrar sesión</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>        <!--Nav End-->
       

        
   @yield('content')

        
        <!-- Footer Section Start -->
        <footer class="footer">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="../dashboard/extra/privacy-policy.html">Politicas de privacidad</a></li>
                    <li class="list-inline-item"><a href="../dashboard/extra/terms-of-service.html">Terminos y condiciones</a></li>
                </ul>
                <div class="right-panel">
                    ©<script>document.write(new Date().getFullYear())</script> Hope UI, Made with
                    <span class="">
                        <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.85 2.50065C16.481 2.50065 17.111 2.58965 17.71 2.79065C21.401 3.99065 22.731 8.04065 21.62 11.5806C20.99 13.3896 19.96 15.0406 18.611 16.3896C16.68 18.2596 14.561 19.9196 12.28 21.3496L12.03 21.5006L11.77 21.3396C9.48102 19.9196 7.35002 18.2596 5.40102 16.3796C4.06102 15.0306 3.03002 13.3896 2.39002 11.5806C1.26002 8.04065 2.59002 3.99065 6.32102 2.76965C6.61102 2.66965 6.91002 2.59965 7.21002 2.56065H7.33002C7.61102 2.51965 7.89002 2.50065 8.17002 2.50065H8.28002C8.91002 2.51965 9.52002 2.62965 10.111 2.83065H10.17C10.21 2.84965 10.24 2.87065 10.26 2.88965C10.481 2.96065 10.69 3.04065 10.89 3.15065L11.27 3.32065C11.3618 3.36962 11.4649 3.44445 11.554 3.50912C11.6104 3.55009 11.6612 3.58699 11.7 3.61065C11.7163 3.62028 11.7329 3.62996 11.7496 3.63972C11.8354 3.68977 11.9247 3.74191 12 3.79965C13.111 2.95065 14.46 2.49065 15.85 2.50065ZM18.51 9.70065C18.92 9.68965 19.27 9.36065 19.3 8.93965V8.82065C19.33 7.41965 18.481 6.15065 17.19 5.66065C16.78 5.51965 16.33 5.74065 16.18 6.16065C16.04 6.58065 16.26 7.04065 16.68 7.18965C17.321 7.42965 17.75 8.06065 17.75 8.75965V8.79065C17.731 9.01965 17.8 9.24065 17.94 9.41065C18.08 9.58065 18.29 9.67965 18.51 9.70065Z" fill="currentColor"></path>
                        </svg>
                    </span> by <a href="https://iqonic.design/">Desarrollo & Inninnovación ACAMA</a>.
                </div>
            </div>
        </footer>
        <!-- Footer Section End -->      </main>
      <!-- Wrapper End-->
    </div>
   

     <!-- offcanvas END-->
    <!-- Library Bundle Script -->
    <script src="{{asset('public/assets/js/core/libs.min.js')}}"></script>
    
    <!-- External Library Bundle Script -->
    <script src="{{asset('public/assets/js/core/external.min.js')}}"></script>
    
    <!-- Widgetchart Script -->
    <script src="{{asset('public/assets/js/charts/widgetcharts.js')}}"></script>
    
    <!-- mapchart Script -->
    <script src="{{asset('public/assets/js/charts/vectore-chart.js')}}"></script>
    <script src="{{asset('public/assets/js/charts/dashboard.js')}}" ></script>
    
    <!-- fslightbox Script -->
    <script src="{{asset('public/assets/js/plugins/fslightbox.js')}}"></script>
    
    <!-- Settings Script -->
    <script src="{{asset('public/assets/js/plugins/setting.js')}}"></script>
    
    <!-- Slider-tab Script -->
    <script src="{{asset('public/assets/js/plugins/slider-tabs.js')}}"></script>
    
    <!-- Form Wizard Script -->
    <script src="{{asset('public/assets/js/plugins/form-wizard.js')}}"></script>
    
    <!-- AOS Animation Plugin-->
    
    <!-- App Script -->
    <script src="{{asset('public/assets/js/hope-ui.js')}}" defer></script>
    
    
     <script>
        var usuario = "{{ session('User') }}";
        var idUsuario = "{{ session('Id_cliente') }}";
        var base_url = "{{url('')}}"; 
     </script>

      @yield('js')
  </body>
</html>