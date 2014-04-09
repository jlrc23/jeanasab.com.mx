<?
session_start();
include_once("includes/php/manager.php");
include_once("includes/php/config.php");

$lm = new manager($_GET['pageNo'],$_GET['num_per_page'],$cfg['photo_folder_http'],'photo',$_GET['folder_name'],$_GET['keyword']);

# check admin access right
$lm->checkAccess();

# header
$backend_header = $lm->genBackendHeader();

# tab menu
$li_tab = $lm->genAdminMenu('u');

# check browser to determine which upload method to use
$is_simple_upload = $lm->useSimpleUpload();

# main display
$display = $lm->genAdminPhotoUpload($_GET['msg'],$is_simple_upload);
	
# js message	
$js_msg = $lm->genBackendJSMessge();	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$cfg['page_title']?></title>

<link href="includes/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="includes/css/style.css" rel="stylesheet" type="text/css" />
<link href="includes/css/ie.css" rel="stylesheet" type="text/css" />

<!--[if lte IE 7]>
<link href="includes/css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript" src="includes/js/jquery-1.8.3.min.js"></script>
<?=$js_msg?>

<script src="includes/colorbox/jquery.colorbox.js"></script>
<link type="text/css" media="screen" rel="stylesheet" href="includes/colorbox/colorbox.css" />
</head>
<body>
<form  method="post" name="form1" id="form1" action="upload_action.php" enctype="multipart/form-data">
<script type="text/javascript" src="includes/js/script.js"></script>
<script src="includes/js/jquery.fileupload.js"></script>
<script src="includes/js/jquery.fileupload-ui.js"></script>
<script language="Javascript">
<?if($is_simple_upload==false)
{?>
$(function () {
    $('#form1').fileUploadUI({
        uploadTable: $('#files'),
        
        buildUploadRow: function (files, index) {
	        
	        fileNames = '';
            for (i = 0; i < files.length; i += 1) {
	            if(i>0)
	            	fileNames+= ' , ';
                fileNames = fileNames + files[i].name;
            }
            	//var return_html ='<tr><td>' + fileNames + '<\/td><\/tr><tr><td class="uploading">'+upload_image_msg+'<div><\/div><\/td><\/tr>';
           	//var return_html ='<tr><td class="uploading">'+upload_image_msg+'<div><\/div><\/td><\/tr>';
           	var return_html ='<tr><td class="uploading">'+upload_image_msg+'<br/>('+fileNames+')<\/td><\/tr>';
           	
           	
            return $(return_html);
        },
        buildDownloadRow: function (file) {
	        document.form1.action="upload.php?msg=Image Uploaded";
	        setTimeout("document.form1.submit()",500);
	        return $(file.hidden_value);
	        
        },
    multiFileRequest: true
    });
});
<?}?>
</script>
<!-- header start -->
<?=$backend_header?>
<!-- header end -->
<!-- content_wrapper start -->
<div class="content_wrapper">
<div class="content">
<!-- ############# Photo Manage ########### admin_box start ############# -->
<div class="admin_box" >
    <div class="admin_box_header">
    <!-- tabs --> 
    <ul class="admin_tab">
    <?=$li_tab?>
    </ul>
    </div>
        <!-- ############# admin_content start ############# -->
        <div class="admin_content" id="content_div">
        <?=$display?>
        </div>
        <!-- ############# admin_content end ############# -->
</div>
<!-- ############# Photo Manage ########### admin_box end ############# -->
        </div>
</div>
<!-- content_wrapper end -->
<input type="hidden" name="msg" id="msg"/>

<input type="hidden" name="t_width" id="t_width"/>
<input type="hidden" name="t_height" id="t_height"/>
<input type="hidden" name="l_width" id="l_width"/>
<input type="hidden" name="l_height" id="l_height"/>
</form>
</body>
</html>