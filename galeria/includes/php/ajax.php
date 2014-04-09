<?php

include_once("manager.php");
include_once("config.php");

$photo_folder_path	= $cfg['photo_folder_path'];
$photo_folder_http	= $cfg['photo_folder_http'];

$pageNo				= trim($_POST['pageNo']);
$page_size			= trim($_POST['page_size']);
$record_per_page	= trim($_POST['recordPerPage']);
$folder_name		= trim($_POST['folder_name']);
$keyword			= trim($_POST['keyword']);
$no_record_msg		= trim($_POST['no_record_msg']);

$lm = new manager($pageNo,$record_per_page,$photo_folder_http,$photo_folder_path,$folder_name,$keyword);
$lm->no_record_msg = $no_record_msg;
$lm->identifier		= $_POST['identifier'];

switch($_POST['task'])
{
	case "delete_image":
		$lm->file_name = $_POST['file_name'];
		echo $lm->deleteImage();
	break;
	case "update_category_name":
		$new_name = trim($_POST['value']);
		$lm->folder_name = trim($_POST['id']);
		if($new_name!='')$lm->updateFolderName($new_name);
	break;
	case "edit_image":
		echo $lm->genEditImagePanel($_POST['image']); 
	break;
	
	case "cancel_edit":
		$lm->contructInfo($_POST['image']);
		echo $lm->genAdminPhotoManagerRowInfo();
	break;
	
	case "save_image_info":
		echo $lm->saveImageInfo($_POST['image'],$_POST['folder_name'],$_POST['image_name']);
	break;	
	
	case "add_new_folder":
		echo $lm->addNewFolder($_POST['new_folder']);
	break;	
	
	case "delete_category":
		$lm->folder_name = $_POST['folder_name'];
		echo $lm->deleteFolder();
	break;
}

?>