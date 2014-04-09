<?php
session_start();
include_once("includes/php/config.php");

$remember_check = (isset($_COOKIE['username']) && isset($_COOKIE['password']))?"checked":"";

# verify with preset info
if(isset($_POST['username']) || $_POST['password'])
{
	
	if($_POST['username'] == $cfg['user_login'] && $_POST['password'] == $cfg['user_password'])
	{
		# user is now login and can access admin pages
		$is_login = true;
		
		# set cookie
		if($_POST['remember']==true)
		{
			setcookie("username", $_POST['username'] , time() + 2419200);
			setcookie("password", $_POST['password'] , time() + 2419200);
		}
		else
		{
			# unset cookie
			setcookie("username", "");
			setcookie("password", "");
		}
		
		$_SESSION['is_login'] = $is_login;
		# redirect to manager page
		header("location:manager.php");
		die;
	}
	else
	{
		# display message
		$notice_msg = '<div class="notice_box" id="notice_box">
        				<div class="alert">Invalid login name or password</div>
        				</div>';		
	}
}



        
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$cfg['page_title']?></title>
<link href="includes/css/reset.css" rel="stylesheet" type="text/css" media="screen" /> <!-- reset -->
<link href="includes/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="includes/js/<?=$config_file?>"></script>
<script type="text/javascript" src="includes/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="includes/js/msg_config.js"></script>
<script src="includes/colorbox/jquery.colorbox.js"></script>
<script type="text/javascript" src="includes/js/script.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="includes/colorbox/colorbox.css" />
</head>
<script language="Javascript">
$(document).ready(function(){
				
	$("a[rel='cata01']").colorbox();
		
	$("#click").click(function(){ 
		$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
		return false;
	});
	
	 $("#username").focus();


});

</script>
<body>
<form  method="post" name="form1" id="form1" action="login.php">
<!-- header start -->
<div class="header">
	
	<div class="top">
    
    	<a href="#" class="logo"></a>
				
    </div>
    
</div>
<!-- header end -->


<!-- content_wrapper start -->
<div class="content_wrapper">

	<!-- login box start -->
	<div class="login">
		<h2>Login</h2>   
        
        <!-- system message box appear in this div#notice_box -->
        <?=$notice_msg?>
        
         		<form action="" method="post">

				
                    <p><label for="username">Name</label> <input type="text" name="username" id="username" class="login_field" value="<?=$_COOKIE['username']?>"/></p>
                    
                    <p><label for="password">Password</label> <input type="password" name="password" id="password" class="login_field" value="<?=$_COOKIE['password']?>"/></p>
                   
                    <p class="remember_me"><input type="checkbox" id="remember" name="remember" value="true" <?=$remember_check?>/>remember me</p>
                    
                    <input name="submit" class="action_btn submit" type="submit" value="Login"/>
                
                </form>

	</div>

	<!-- login box end -->
    
</div>
<!-- content_wrapper end -->
</form>
</body>
</html>
