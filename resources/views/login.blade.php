<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Acama | clientes</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{asset('public/assets/images/favicon.ico')}}">
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{asset('public/assets/css/core/libs.min.css')}}">
      
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/hope-ui.min.css?v=4.0.0')}}">
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/custom.min.css?v=4.0.0')}}">
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/dark.min.css')}}">
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/customizer.min.css')}}">
      
      <!-- RTL Css -->
      <link rel="stylesheet" href="{{asset('public/assets/css/rtl.min.css')}}">

      <link rel="stylesheet" href="{{asset('public/css/login.css')}}">
      <style>
         body {
             background-color: #0A3640 !important;
         }
         #particles-js {
            position: relative;
         }

         #particles-js canvas {
            position: absolute !important;
            inset: 0;
            z-index: 0;
         }

         /* Estilos para el carrusel */
         .carousel-login {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
         }

         .carousel-login .carousel-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 2.5s ease-in-out;
         }

         .carousel-login .carousel-item.active {
            opacity: 1;
         }

         .carousel-login img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
         }

         /* Asegurar que el contenedor ocupe todo el espacio */
         .col-md-6.bg-primary {
            padding: 0 !important;
            margin: 0 !important;
         }
      </style>
      
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body">
          </div>
      </div>    
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="row m-0 align-items-center bg-white vh-100">            
            <div class="col-md-6">
               <div class="row justify-content-center">
                  <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body">
                           <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                              
                              <div class="logo-main">
                        <div class="logo-normal">
                              <img 
                                 src="{{asset('public/img/logo.png')}}" 
                                 alt="Logo ACAMA" 
                                 class="img-fluid"
                                 style="height:30px;"
                              >
                        </div>

                        <div class="logo-mini">
                              <img 
                                   src="{{asset('public/img/logo.png')}}" 
                                 alt="Logo ACAMA" 
                                 class="img-fluid"
                                 style="height:30px;"
                              >
                        </div>
                     </div>
                                                
                              
                           </a>
                           <h2 class="mb-2 text-center">Inicio de sesión</h2>
                           <p class="text-center">Bienvenido a ACAMA</p>
                            <form method="POST" action="{{ route('login.post') }}">
                                      @csrf
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="usuario" class="form-label">Usuario</label>
                                       <input type="text" class="form-control" id="usuario" name="usuario" aria-describedby="email" placeholder="Escriba su usuario...">
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Contraseña</label>
                                       <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Escriba su contraseña...">
                                    </div>
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center">
                                 <button class="offset">Iniciar sesión</button>
                              </div>
                            
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sign-bg">
                  <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <g opacity="0.05">
                     <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
                     <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
                     <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
                     <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
                     </g>
                  </svg>
               </div>
            </div>
            
            <!-- Carrusel de imágenes -->
            <div class="col-md-6 d-md-block d-none bg-primary p-0 vh-100 overflow-hidden position-relative" id="particles-js">
               <div class="carousel-login">
                  <div class="carousel-item active">
                     <img src="{{asset('public/img/login1.jpeg')}}" 
                          class="img-fluid gradient-main"
                          alt="Login Image 1">
                  </div>
                  <div class="carousel-item">
                     <img src="{{asset('public/img/login2.jpeg')}}" 
                          class="img-fluid gradient-main"
                          alt="Login Image 2">
                  </div>
               </div>
            </div>

         </div>
      </section>
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <!-- Script para el carrusel -->
    <script>
      // Carrusel automático de imágenes
      document.addEventListener('DOMContentLoaded', function() {
         const items = document.querySelectorAll('.carousel-item');
         let currentIndex = 0;

         function showNextSlide() {
            items[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % items.length;
            items[currentIndex].classList.add('active');
         }

         // Cambiar cada 7 segundos (7000 ms) - era 5, ahora es 7
         setInterval(showNextSlide, 7000);
      });
    </script>

    <script src="{{asset('public/js/login.js')}}"></script>
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
    
    
  </body>
</html>