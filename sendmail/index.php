<?php
@session_start();

include("class.phpmailer.php");
include("class.smtp.php");

include_once '../captcha/securimage.php';
$securimage = new Securimage();

/*
Enviar variables por POST a:
http://apilazaro.com.mx/sendmail.php
Variables: 
	destino=correo@servidor.com
	titulo=titulo_del_correo
	mensaje=mensajedelcorreo
	captcha_code=resultado captcha
*/
if(isset($_POST["mensaje"]) AND isset($_POST["nombre"]) AND isset($_POST["mail"]) )
{
	$_SESSION["fr_contacto_nombre"] = $_POST["mensaje"];
	$_SESSION["fr_contacto_mensaje"] = $_POST["nombre"];
	$_SESSION["fr_contacto_mail"] = $_POST["mail"];
	$_SESSION["fr_contacto_telefono"]  = $_POST["telefono"];
}

if($_POST && $securimage->check($_POST['captcha_code']) == false)
{ 	echo 'El código captcha que introdujo no fue correcto. <a  style="color:#ff6600" onclick="recargar_formulario()" href="#"> intentar nuevamente</a>';
	
	exit();
}

if(isset($_POST["mensaje"]) AND isset($_POST["nombre"]) AND isset($_POST["mail"]) )
{
	$destino = 	explode(";" , $_SESSION["var_correo_portal"]);
	//$destino = 	explode(";" , "eabrmz@gmail.com");

	$mensaje  = "<b>Nombre: </b>" . utf8_decode($_POST["nombre"])."<br />";  
	$mensaje .= "<b>Mail: </b>" . utf8_decode($_POST["mail"])."<br />";  
	if($_POST["telefono"] != "") 
		{ $mensaje .= utf8_decode("<b>Teléfono: </b>" . $_POST["telefono"])."<br />";  }
		
	$mensaje .= "<b>Mensaje:</b><br />" . utf8_decode($_POST["mensaje"]);
	$mensaje .= "<br /><br /><b>IP: </b><br />" . getRealIP();
	
	
	

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = false;
//	$mail->SMTPSecure = "ssl";
	$mail->Host = "localhost";
	$mail->Port = 25;
	$mail->Username = "email@portalesdemexico.com";
	$mail->Password = "contras3na";
	

	$mail->From = "email@portalesdemexico.com";
	$mail->FromName = "Contacto Portales de Mexico";
	$mail->Subject = "Contacto desde ".$_SESSION["var_nombre_portal"];

	
	// Texto sin tags
	$mail->AltBody = strip_tags($mensaje);
	// Texto con tags
	$mail->MsgHTML($mensaje);
	
    foreach ($destino as $correo) {
		$mail->AddAddress($correo, $correo);
	}
	
	$mail->IsHTML(true);

	if(!$mail->Send()) {
	  echo "Error: " . $mail->ErrorInfo;
	} else {
	  echo '
	  <div style="color: #ff0000; background-color: #FFFFFF; width: 100%; text-align: center;">Mensaje enviado satisfactoriamente...</div>
	  <script>$("#loader").css("display","none");</script>
	  ';
	}
}

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
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" );
   }
 
   return $client_ip;
 
}
?>
