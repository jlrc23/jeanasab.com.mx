<?
session_start();
include_once("includes/php/manager.php");
include_once("includes/php/config.php");

if(isset($cfg['memory_limit']))
	ini_set("memory_limit",$cfg['memory_limit']);
if(isset($cfg['upload_max_filesize']))	
	ini_set("upload_max_filesize", $cfg['upload_max_filesize']);


$lm = new manager('','',$cfg['photo_folder_http'],'photo');
# check admin access right
$lm->checkAccess();

for($i=0;$i<sizeof($_FILES['file']['name']);$i++)
{
	if($_FILES['file']['name'][$i]!='')
	{
		$org_folder = $lm->org_folder_path.'/'.$_POST['folder_name'].'/'.$_FILES['file']['name'][$i];
		$thumb_folder = $lm->thumb_folder_path.'/'.$_POST['folder_name'].'/'.$_FILES['file']['name'][$i];
		$large_folder = $lm->large_folder_path.'/'.$_POST['folder_name'].'/'.$_FILES['file']['name'][$i];
		
		$temp_name =$_FILES['file']['tmp_name'][$i];  
		$userfile_type =$_FILES['file']['type'][$i];  
		
		if (!($userfile_type =="image/bmp" OR $userfile_type =="image/pjpeg" OR $userfile_type =="image/jpeg" OR $userfile_type=="image/gif" OR $userfile_type=="image/png" OR $userfile_type=="image/x-png")){  
		die ("You can upload just images in .jpg .jpeg .gif and .png format!<br / >");  
		}  
		
		# store original pic to be stored    
		copy ($temp_name, $org_folder);
		chmod($org_folder,0777);
		# thumbnail pic
		copy ($temp_name, $thumb_folder);
		chmod($thumb_folder,0777);
		# enlarge pic
		copy ($temp_name, $large_folder);
		chmod($large_folder,0777);
							
		$data = fread(fopen($temp_name, "rb"), filesize($temp_name));  
		$src_image = imagecreatefromstring($data);  
		$width = imagesx($src_image);  
		$height = imagesy($src_image);  
		
		# original image ratio
		$image_ratio = $width/$height;
		
		# thumbnail resize
		if($image_ratio<($_POST['thumb_size_w']/$_POST['thumb_size_h']))
		{			
			$thumb_width = $_POST['thumb_size_w'];
			$thumb_height = $_POST['thumb_size_w']/$image_ratio;
		}
		else
		{
			$thumb_height = $_POST['thumb_size_h'];  
			$thumb_width = $_POST['thumb_size_h']*$image_ratio;
		}
		
		# large image resize if dimension is <= default dimension
		if($_POST['large_size_w']>=$width && $_POST['large_size_h']>=$height)
		{
			$large_width = $width;
			$large_height = $height;
		}
		else
		{
			if($image_ratio>($_POST['large_size_w']/$_POST['large_size_h']))
			{
				$large_width = $_POST['large_size_w'];
				$large_height = $_POST['large_size_w']/$image_ratio;
			}
			else
			{
				$large_height = $_POST['large_size_h'];  
				$large_width = $_POST['large_size_h']*$image_ratio;
			}
		}		
		
		$thumb_dest_img = imagecreatetruecolor($thumb_width, $thumb_height);  
		$large_dest_img = imagecreatetruecolor($large_width, $large_height);        
		imagecopyresized($thumb_dest_img, $src_image,0, 0, 0, 0,$thumb_width, $thumb_height,$width, $height);  
		imagecopyresized($large_dest_img, $src_image,0, 0, 0, 0,$large_width, $large_height,$width, $height);  
		ob_start();  
		if($userfile_type == "image/jpeg" OR $userfile_type == "image/pjpeg"){  
		    imagejpeg($thumb_dest_img,$thumb_folder,100);  
		}  
		if($userfile_type == "image/gif"){  
		    imagegif($thumb_dest_img,$thumb_folder,100);  
		}  
		if($userfile_type == "image/png" OR $userfile_type == "image/x-png"){  
		    imagepng($thumb_dest_img,$thumb_folder,9);  
		}  
		$binaryThumbnail = ob_get_contents();  
		ob_end_clean();  
		ob_start();  
		if($userfile_type == "image/jpeg" OR $userfile_type == "image/pjpeg"){  
		    imagejpeg($large_dest_img,$large_folder,100);    
		}  
		if($userfile_type == "image/gif"){  
			imagegif($large_dest_img,$large_folder,100);    
		}  
		if($userfile_type == "image/png" OR $userfile_type == "image/x-png"){  
			imagepng($large_dest_img,$org_folder,9);    
		}  
		$binaryImage = ob_get_contents();  
		ob_end_clean();  
		if(!get_magic_quotes_gpc()){  
		    $binaryThumbnail=addslashes($binaryThumbnail);  
		    $binaryImage=addslashes($binaryImage);  
		}  
		
		$msg = "Image uploaded";	
	}
}
if($lm->useSimpleUpload())
{
	header("location:upload.php?msg=".$msg);
}
else
{
	$file = $_FILES['file'];

	$return = '{"name":"';
	for($a=0;$a<sizeof($file['name']);$a++)
	{
		$return .= $file['name'][$a].'<br/>';
	}
	$return.='","hidden_value":"'.$hidden_field.'"}';
	echo $return;
}

?>