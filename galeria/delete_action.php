<?
session_start();
include_once("includes/php/manager.php");
include_once("includes/php/config.php");

$lm = new manager('','',$cfg['photo_folder_http'],'photo');
# check admin access right
$lm->checkAccess();

for($a=0;$a<sizeof($_POST['image_cb']);$a++)
{
	$lm->file_name = $_POST['image_cb'][$a];
	$msg = $lm->deleteImage();
}
header("location:manager.php?msg=".$msg."&folder_name=".$_POST['folder_name']);
?>