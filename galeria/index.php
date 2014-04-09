<?php

include_once("includes/php/config.php");
include_once("includes/php/manager.php");

$page_no = $_GET['pageNo'];
# set default page no to 1
if($page_no=='')$page_no = 1;

$view_type = $_GET['view_type'];
# set default view type to 1
if($view_type=='')$view_type = 1;

$record_per_page = ($_GET['recordPerPage']=='')?$cfg['frontend_config'.$view_type]['f_rpg']:$_GET['recordPerPage'];

$lm = new manager($page_no,$record_per_page,$cfg['photo_folder_http'],'photo',$_GET['folder_name'],$_GET['keyword']);

$lm->image_sorting = $cfg['frontend_config'.$view_type]['f_order'];
$lm->image_sorting_type = $cfg['frontend_config'.$view_type]['f_order_type'];





# view type menu
$list_menu = '<li class="grid_1"><a href="javascript:void(0)" onClick="changeView(1);"'.(($view_type==1)?'class="current"':'').'></a></li>
		      <li class="grid_2"><a href="javascript:void(0)" onClick="changeView(2);"'.(($view_type==2)?'class="current"':'').'></a></li>
		      <li class="line_1"><a href="javascript:void(0)" onClick="changeView(3);"'.(($view_type==3)?'class="current"':'').'></a></li>
			  <li class="line_2"><a href="javascript:void(0)" onClick="changeView(4);"'.(($view_type==4)?'class="current"':'').'></a></li>';
			  
$lm->view_folder_name = $_GET['folder_name'];

# apply config file
$config_file = 'config_preset'.$view_type.'.js';
$effect_vars = $lm->genFrontendJSConfig($view_type);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$cfg['page_title']?></title> 
<link href="includes/css/reset.css" rel="stylesheet" type="text/css" media="screen" /> <!-- reset -->
<link href="includes/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="includes/js/jquery-1.8.3.min.js"></script>
<script src="includes/colorbox/jquery.colorbox.js"></script>

<link type="text/css" media="screen" rel="stylesheet" href="includes/colorbox/colorbox.css" />
</head>
<script language="Javascript">
$(document).ready(function(){
	
	applyFrontendEffect();
		
});
function changeView(type)
{
	$('#view_type').val(type);
	$('#recordPerPage').val('');
	$('#pageNo').val('');
	document.form1.submit();
}
function applyFrontendEffect()
{
	<?=$effect_vars?>
	var p_left = (b_width - s_width) / 2;
	var p_top = (b_height - s_height) / 2;
	
	$('.thumb').css({'left':p_left  +'px','top':p_top  +'px','height':s_height +'px','width':s_width +'px','opacity':po});
	$('.photo').css({'margin-top':(p_top * -2) + pp +'px','margin-left':(p_left * -2) + pp +'px','height':b_height +'px','width':b_width +'px'});
	$('.gallery_wrapper').css({'margin-top':gwt +'px','margin-left':p_left - pp + gwl +'px'});
		
	//enlarge thumbnail and show pic title
	$('.thumb')
		.hover(function(){
			$(this)
				.stop()
				.animate({
					width: b_width,
					height: b_height,
					top: 0,
					left: 0,
					opacity: 1
				},300);
			$(this).css("z-index" , "2");
			
		var pT = $(this).parent().attr('pictitle');	
		$(this).parent().append('<div class="name_box">' + pT + '</div>');	
				
		$('.name_box').fadeIn('slow');
		$('.name_box').fadeTo('10',0.9);
		
		$(this).parent().mousemove(function(e) {
		
		 e=e || window.event;
	
		$('.name_box').css({'top':e.pageY+30,'left':e.pageX-5});
		});
		
			
		},
		function(){
			$(this)
				.stop()
				.animate({
					width: s_width,
					height: s_height,
					top: p_top +'px',
					left: p_left +'px',
					opacity: po
				},100);
			$(this).css("z-index" , "1");
			
			$(this).parent().children('.name_box').remove();
		});
		
		
	//show hide category list					   
			
		$("ul.category_list").hide(); 
		$(".category .box .current a").click( 
			function () {
				$("ul.category_list").slideToggle("normal");
				return false;
			}
		);	
	
		//Original soruce from: http://colorpowered.com/colorbox/
		$("a[rel='colorbox']").colorbox({slideshow:CBss , slideshowSpeed:CBspeed ,title: function(){
    		var CBpT = $(this).attr('pictitle');
    		return CBpT;
		}});
}
</script>
<body>
<form  method="get" name="form1" id="form1" action="index.php">
<script type="text/javascript" src="includes/js/script.js"></script>
<!-- header start -->
<div class="header">
	<div class="top">
    	<a href="#" class="logo"></a>
        <ul class="view_mode">
        <?=$list_menu?>
        </ul>
    	<!-- category start -->
            <?=$lm->genFrontendCategorySelection();?>
          <!-- category list end -->
    	</div>
    	<!-- category end -->
    </div>
</div>
<!-- header end -->
<!-- content_wrapper start -->
<div class="content_wrapper">
<!-- gallery_wrapper start -->
<div class="gallery_wrapper">
<?=$lm->genFrontendImage()?>


</div>
<!-- content_wrapper end -->
<?=$lm->genPaginationHiddenField()?>
<input type="hidden" id="folder_name" name="folder_name" value="<?=$lm->view_folder_name?>"/>
<input type="hidden" id="view_type" name="view_type" value="<?=$view_type?>" />
</form>
</body>
</html>