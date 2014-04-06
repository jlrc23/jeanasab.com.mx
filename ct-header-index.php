<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Jean & Asab</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="img/arrow_asab.png" />
<script src="js/jquery-1.8.3.min.js"></script>
<!-- Le styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/global.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<!-- sliderwindy -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300' rel='stylesheet' type='text/css'/>
<link rel="stylesheet" type="text/css" href="css/windy.css"/>
<link rel="stylesheet" type="text/css" href="css/demo.css"/>
<link rel="stylesheet" type="text/css" href="css/windy-style.css"/>
<script type="text/javascript" src="js/modernizr.custom.79639.js"></script>
<noscript>
<link rel="stylesheet" type="text/css" href="css/noJS.css"/>
</noscript>
<!--  //////////////////////   -->

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
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="ico/favicon.png">
<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:300' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Niconne' rel='stylesheet' type='text/css'>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->

</head><body>

<!-- NAVBAR
    ================================================== -->
<div class="navbar-wrapper" id="jdctrlhide"> 
  <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
  <div class="container">
    <div class="navbar">
      <div class="navbar-inner"> 
        <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="brand" href="#"  ><img src="img/jean_logo.png"></a> 
        <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
        <div class="nav-collapse collapse">
          <ul class="nav">
            <li><a href="index.php">Inicio</a></li>
            <li class="active"><a href="nosotros.php">Nosotros</a></li>
            <li><a href="servicios.php">Servicios</a></li>
            <?PHP if($contador_galerias > 0) {	?>
            <li><a href="galerias.php">Galerías</a></li>
            <?PHP } ?>
            <li><a href="contacto.php">Contacto</a></li>
          </ul>
        </div>
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
  <div class="container menu-grande"> <img src="img/jean_logo_spaced.png"  >
    <ul class="items-menu-grande">
      <li><a href="index.php" class="active">Inicio</a></li>
      <li><a href="nosotros.php">Nosotros</a></li>
      <li><a href="servicios.php">Servicios</a></li>
      <?PHP if($contador_galerias > 0) {	?>
      <li><a href="galerias.php">Galerías</a></li>
      <?PHP } ?>
      <li><a href="contacto.php">Contacto</a></li>
    </ul>
  </div>
</div>

<!-- Carousel
    ================================================== -->
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
<!-- /.carousel -->