<?PHP
session_start();
$vista_actual = "contacto"; 
require_once("includes/header.php");

function getRealIP()
{
 
   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
   {
      $client_ip =  
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" );
 
      // los proxys van añadiendo al final de esta cabecera
      // las direcciones ip que van "ocultando". Para localizar la ip real
      // del usuario se comienza a mirar por el principio hasta encontrar 
      // una dirección ip que no sea del rango privado. En caso de no 
      // encontrarse ninguna se toma como valor el REMOTE_ADDR
 
      $entries = preg_split('/[, ]/', $_SERVER['HTTP_X_FORWARDED_FOR']);
 
      reset($entries);
      while (list(, $entry) = each($entries)) 
      {
         $entry = trim($entry);
         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
         {
            // http://www.faqs.org/rfcs/rfc1918.html
            $private_ip = array(
                  '/^0\./', 
                  '/^127\.0\.0\.1/', 
                  '/^192\.168\..*/', 
                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', 
                  '/^10\..*/');
 
            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
 
            if ($client_ip != $found_ip)
            {
               $client_ip = $found_ip;
               break;
            }
         }
      }
   }
   else
   {
      $client_ip = 
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR']  :  "unknown" );
   }
 
   return $client_ip;
 
}

if(isset($_POST["nombre"]) AND isset($_POST["correo"]) AND isset($_POST["telefono"]) AND isset($_POST["mensaje"]) AND isset($_POST["token"]))
{


if($_POST["token"] == $_SESSION["etoken"])
{

    $mail_nombre    = $_POST["nombre"];
    $mail_correo    = $_POST["correo"];
    $mail_telefono  = $_POST["telefono"];
    $mail_mensaje   = $_POST["mensaje"];
    
    
    
include("includes/sendmail/class.phpmailer.php");
include("includes/sendmail/class.smtp.php");


	$destino = 	explode(";" , "jeanasab@jeanasab.com.mx;jlrc23@gmail.com");
	//$destino = 	explode(";" , "eabrmz@gmail.com");

	$mensaje  = "El siguiente mensaje ha sido envíado a través de su sitio web.<br /><br />";  
	$mensaje  .= "<b>Nombre: </b>" . utf8_decode($mail_nombre)."<br />";  
	$mensaje .= "<b>Mail: </b>" . utf8_decode($mail_correo )."<br />";  
	if($_POST["telefono"] != "") 
		{ $mensaje .= utf8_decode("<b>Teléfono: </b>" . $mail_telefono)."<br />";  }
		
	$mensaje .= "<b>Mensaje:</b><br />" . utf8_decode($mail_mensaje);
	$mensaje .= "<br /><br /><b>IP: </b><br />" . getRealIP();
	
	
	

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = false;
//	$mail->SMTPSecure = "ssl";
	$mail->Host = "localhost";
	$mail->Port = 25;
	$mail->Username = "notificaciones@jeanasab.com.mx";
	$mail->Password = "contras3na";
	

	$mail->From = "notificaciones@jeanasab.com.mx";
	$mail->FromName = "Contacto Jean & Asab";
	$mail->Subject = "Contacto desde www.jeanasab.com.mx";

	
	// Texto sin tags
	$mail->AltBody = strip_tags($mensaje);
	// Texto con tags
	$mail->MsgHTML($mensaje);
	
    foreach ($destino as $correo) {
		$mail->AddAddress($correo, $correo);
	}
	
	$mail->IsHTML(true);
    $correcto = false;
	if(!$mail->Send()) {
	  echo "Error: " . $mail->ErrorInfo;
	} else {
	 $correcto = true;
	}
    



    
    if($correcto) {
        echo '<script>alert("Su mensaje ha sido enviado satisfactoriamente")</script>';
    }
    
    }  
    else
    {
        echo '<script>alert("Tenemos problemas al enviar su mensaje, intente nuevamente.")</script>';
    }
}
    $_SESSION["etoken"] = md5(date('YmdHis').date("is"));
?>

<div class="container marketing">
  <div class="row">
    <div class="" style="position: relative;">
      <div class="titulo-enorme-oscuro color-jya"><span class="color-jya-rojo">C</span>ONTACTO</div>
      <span style="padding: 10px 20px; position: absolute; top: 0; right: 20px;"> <img src="img/jean_logo_small.png" width="80" /> </span>
      <hr class="faded"/>
      <div class="row"  style="margin: 0 0 0 30px; " >
      <form class="form-horizontal" method="post" action="contacto.php">
        <input name="token" type="hidden" value="<?PHP echo $_SESSION["etoken"]; ?>">
        <fieldset>
          <div class="control-group"> 
            
            <!-- Text input-->
            <label class="control-label" for="input01">Nombre</label>
            <div class="controls">
              <input name="nombre" type="text" placeholder="¿Cuál es su nombre?" class="input-xlarge">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group"> 
            
            <!-- Text input-->
            <label class="control-label" for="input01">Correo electrónico</label>
            <div class="controls">
              <input name="correo"  type="text" placeholder="Proporcione su email" class="input-xlarge">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group"> 
            
            <!-- Text input-->
            <label class="control-label" for="input01">Teléfono</label>
            <div class="controls">
              <input name="telefono"  type="text" placeholder="Teléfono de contácto" class="input-xlarge">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group"> 
            
            <!-- Textarea -->
            <label class="control-label">Mensaje</label>
            <div class="controls">
              <div class="textarea">
                <textarea  name="mensaje" type="" class="" style="margin: 0px; width: 477px; height: 81px;"> </textarea>
              </div>
            </div>
          </div>
          <div class="control-group"> 
            
            <!-- Textarea -->
            <label class="control-label">&nbsp;</label>
            <div class="controls">
              <input type="submit" value="Enviar ahora" />
            </div>
          </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
</div>
<br />
<hr class="faded" />
<br />
<?php include("includes/footer.php") ?>