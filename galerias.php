<?PHP
$vista_actual = "galerias"; 
require_once("ct-header.php");
$array_estilos = array("bg-grad-azul", "bg-grad-rojo", "bg-grad-negro");
?>
		<link rel="stylesheet" href="galerias/vlb_files/vlightbox1.css" type="text/css" />
		<link rel="stylesheet" href="galerias/vlb_files/visuallightbox.css" type="text/css" media="screen" />
        <script src="galerias/vlb_engine/jquery.min.js" type="text/javascript"></script>
		<script src="galerias/vlb_engine/visuallightbox.js" type="text/javascript"></script>
    <div class="container marketing">
    
    
    <div class="row">

    <div class="" style="position: relative;">
        <div class="titulo-enorme-oscuro color-jya"><span class="color-jya-rojo">G</span>ALERÍAS</div>
        <span style="padding: 10px 20px; position: absolute; top: 0; right: 20px;">
            <img src="img/jean_logo_small.png" width="80" />
        </span>
<hr class="faded"/>
        <div class="row"  style="margin: 0 0 0 30px; " >
        <?PHP /* if(isset($_GET["GL"]))
        {
        ?>
        	<div id="vlightbox1" class="span12" style="margin-left: 60px; text-align: center;">
			<?PHP
									$url = 'http://67.18.19.147/~jeanasab/administracion/sistema/wservices/fotografias.php?GL='.$_GET["GL"];
									$str_datos = file_get_contents($url);
									$fotos = json_decode($str_datos,true);
									$contador_temporal = 1;
									foreach($fotos AS $f)
										{ 
										?>
										<a class="vlightbox1" href="<?PHP echo $f["foto"] ?>" title=""><img  style="width: 240px; height: 180px"   src="<?PHP echo $f["thumb"] ?>" alt="" title="" /></a>
									<?PHP  } ?>
						
            </div>
            <script src="galerias/vlb_engine/thumbscript1.js" type="text/javascript"></script>
            <script src="galerias/vlb_engine/vlbdata1.js" type="text/javascript"></script>
        <?PHP
        }
        else
        {
        ?>
			
			<?PHP
							$url = 'http://67.18.19.147/~jeanasab/administracion/sistema/wservices/galerias.php';
							
							$str_datos = file_get_contents($url);
							$datos = json_decode($str_datos,true);
							
							foreach($datos AS $d)
							{ 
							?>
								<a href="galerias.php?GL=<?PHP echo $d["id"]; ?>" class="mn-galeria-block">
									<div class="mn-galeria-descripcion bg-grad-gris">
										<div class="mn-galeria-titulo <?PHP echo $array_estilos[rand(0, 2)]; ?>"><?PHP echo $d["titulo"]; ?></div>
										<span class="mn-galeria-texto"><?PHP echo $d["descripcion"]; ?></span>
									</div>                
									<div class="mn-galeria-ft1">
									<?PHP
									$url = 'http://67.18.19.147/~jeanasab/administracion/sistema/wservices/fotografias.php?GL='.$d["id"];
									$str_datos = file_get_contents($url);
									$fotos = json_decode($str_datos,true);
									$contador_temporal = 1;
									foreach($fotos AS $f)
										{ 
										if($contador_temporal++ == 1)
										{
									?>
										<div class="mn-galeria-ft2"><img src="<?PHP echo $f["foto"] ?>" style="width: 180px; height: 135px" /></div>
										<?PHP } else { ?>
											<img src="<?PHP echo $f["foto"] ?>" style="width: 180px; height: 135px" />
									<?PHP break; } } ?>
									</div>
								</a>
								
						<?PHP 
							if($ct_est > 3) { $ct_est = 0; }
							}  ?>
                        
            
        <?PHP
        } */
        ?>
    
        </div>
    
    </div>
    </div>
    </div>
    
    
    <br />
    
<hr class="faded" />
    <br />
<?PHP 
require_once('ct-footer-pre.php');
require_once('ct-footer.php'); ?>