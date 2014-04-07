<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Jean & Asab</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Jean & Asab">
<link rel="shortcut icon" href="img/arrow_asab.png" />
<script src="js/jquery-1.8.3.min.js"></script>
<!-- Le styles -->
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:300' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Niconne' rel='stylesheet' type='text/css'>


<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">


<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script>
      $(document).ready(function() {
            $("#jdctrlhide").hide();
            $(".fsmall").hide();
       });
      </script>
      <style>
      .carousel-control
      {
        font-size: 5em;
      }
      .items-menu-grande li{
        padding-bottom: 10px;
        font-size: 1em;
      }
      
      </style>
    <![endif]-->
<!-- Fav and touch icons -->




</head>

<body>
<header>
    <!-- NAVBAR ================================================== -->
    <div class="navbar-wrapper" id="jdctrlhide"> 
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
      <div class="container">
        <div class="navbar">
          <div class="navbar-inner"> 
            <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="brand" href="/"><img src="img/jean_logo.png"  ></a> 
            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <nav class="nav-collapse collapse">
              <ul class="nav">
                <li><a href="index.php" <?PHP if($vista_actual == "") { echo ' class="active" '; } ?>>Inicio</a></li>
                <li><a href="nosotros.php" <?PHP if($vista_actual == "nosotros") { echo ' class="active" '; } ?>>Nosotros</a></li>
                <li><a href="servicios.php" <?PHP if($vista_actual == "servicios") { echo ' class="active" '; } ?>>Servicios</a></li>
                <li><a href="contacto.php" <?PHP if($vista_actual == "contacto") { echo ' class="active" '; } ?>>Contacto</a></li>
              </ul>
            </nav>
            <!--/.nav-collapse --> 
          </div>
          <!-- /.navbar-inner --> 
        </div>
        <!-- /.navbar --> 
        
      </div>
      <!-- /.container --> 
    </div>
    <!-- /.navbar-wrapper -->

    <div class="row menu-grande-bg">
      <nav class="container menu-grande"> <a href="/"> <img src="img/jean_logo_spaced.png" border="0"  ></a>
        <ul class="items-menu-grande">
          <li><a href="index.php" <?PHP if($vista_actual == "") { echo ' class="active" '; } ?>>Inicio</a></li>
          <li><a href="nosotros.php" <?PHP if($vista_actual == "nosotros") { echo ' class="active" '; } ?>>Nosotros</a></li>
          <li><a href="servicios.php" <?PHP if($vista_actual == "servicios") { echo ' class="active" '; } ?>>Servicios</a></li>
          <li><a href="contacto.php" <?PHP if($vista_actual == "contacto") { echo ' class="active" '; } ?>>Contacto</a></li>
        </ul>
      </nav>
    </div>

    <!-- Carousel ================================================== -->
    <div id="myCarousel" class="carousel slide" >
      <div class="carousel-inner">
        <div class="item active"> <img src="img/slides/slide-01.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Montajes especializados</h1>
              <p class="lead">Estructural y Electromecánico</p>
            </div>
          </div>
        </div>
        <div class="item"> <img src="img/slides/slide-02.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Pailería</h1>
              <p class="lead">Ligera o estructural</p>
            </div>
          </div>
        </div>
        <div class="item"> <img src="img/slides/slide-03.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Tubería para baja y alta presión</h1>
              <p class="lead">Ferroducto, agua, aire, nitrogeno, gas, oxigeno y vapor</p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </div>
</header>