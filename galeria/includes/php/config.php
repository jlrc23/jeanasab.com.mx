<?

##################################### Backend #####################################

$cfg['photo_folder_path'] = "../../photo"; 
$cfg['photo_folder_http'] = "http://www.jeanasab.com.mx/galeria/photo";
$cfg['page_title'] = "Administración de proyecto";
$cfg['no_file_msg'] = "Archivo no encontrado";
$cfg['no_folder_msg'] = "Directorio no encontrado";

// admin login info
$cfg['user_login'] = "admin";
$cfg['user_password'] = "admin";
 
//set thumbnail width
$cfg['thumb_default_width'] = 180;
//set thumbnai height
$cfg['thumb_default_height'] = 180;
//set enlarged width
$cfg['large_default_width'] = 900;
//set enlarged height
$cfg['large_default_height'] = 600;

$cfg['js_msg']['remove_image_warn_msg'] = "¿Está seguro de eliminar esta imagen?";
$cfg['js_msg']['editing_warn_msg'] = "En este momento está editando un archivo, por favor terminar esa primero.";
$cfg['js_msg']['leave_editing_warn_msg'] = "En este momento está editando un archivo sin hacer cambios.?";
$cfg['js_msg']['remove_cat_msg'] = "¿Está seguro de eliminar esta carpeta Todos los archivos dentro de esta carpeta se eliminarán de forma permanente";
$cfg['js_msg']['invalid_name_msg'] = "Nombre no válido";
$cfg['js_msg']['select_folder_msg'] = "Por favor, seleccione una carpeta";
$cfg['js_msg']['select_image_msg'] = "Seleccione una imagen";
$cfg['js_msg']['delete_selected_image'] = "¿Está seguro de eliminar la imagen (s) seleccionada?";
$cfg['js_msg']['upload_image_msg'] = "Subiendo, por favor espere ...";
$cfg['js_msg']['loading_msg'] = "Cargando ...";

$cfg['memory_limit'] = "200M";
$cfg['upload_max_filesize'] = "10M";

##################################### Frontend #####################################

##################################
# View type 1 start
##################################
//set opacity of small photos
$cfg['frontend_config1']['po'] = 0.8;

//set padding between photos
$cfg['frontend_config1']['pp'] = 14;

//fine tune gallery_wrapper top margin
$cfg['frontend_config1']['gwt'] = 0;

//fine tune gallery_wrapper left margin
$cfg['frontend_config1']['gwl'] = 15;

//set small thumbnail width
$cfg['frontend_config1']['s_width'] = 105;

//set small thumbnai height
$cfg['frontend_config1']['s_height'] = 105;

//set enlarged thumbnail width
$cfg['frontend_config1']['b_width'] = 180;

//set enlarged phot height
$cfg['frontend_config1']['b_height'] = 180;

//gallery slideshow setting
$cfg['frontend_config1']['CBss'] = false;
$cfg['frontend_config1']['CBspeed'] = 5000;

//frontend record per page
$cfg['frontend_config1']['f_rpg'] = 24;

//set frontend image order by name or time uploaded
$cfg['frontend_config1']['f_order'] = "time";  # name / time
$cfg['frontend_config1']['f_order_type'] = "desc"; # asc / desc
##################################
# View type 1 end
##################################

##################################
# View type 2 start
##################################
//set opacity of small photos
$cfg['frontend_config2']['po'] = 0.5;

//set padding between photos
$cfg['frontend_config2']['pp'] = 12;

//fine tune gallery_wrapper top margin
$cfg['frontend_config2']['gwt'] = 50;

//fine tune gallery_wrapper left margin
$cfg['frontend_config2']['gwl'] = 25;

//set small thumbnail width
$cfg['frontend_config2']['s_width'] = 34;

//set small thumbnai height
$cfg['frontend_config2']['s_height'] = 34;

//set enlarged thumbnail width
$cfg['frontend_config2']['b_width'] = 180;

//set enlarged phot height
$cfg['frontend_config2']['b_height'] = 180;

//gallery slideshow setting
$cfg['frontend_config2']['CBss'] = false;
$cfg['frontend_config2']['CBspeed'] = 5000;

//frontend record per page
$cfg['frontend_config2']['f_rpg'] = 60;

//set frontend image order by name or time uploaded
$cfg['frontend_config2']['f_order'] = "time"; # name / time
$cfg['frontend_config2']['f_order_type'] = "desc"; # asc / desc
##################################
# View type 2 end
##################################

##################################
# View type 3 start
##################################
//set opacity of small photos
$cfg['frontend_config3']['po'] = 1;

//set padding between photos
$cfg['frontend_config3']['pp'] = 0;

//fine tune gallery_wrapper top margin
$cfg['frontend_config3']['gwt'] = 0;

//fine tune gallery_wrapper left margin
$cfg['frontend_config3']['gwl'] = 30;

//set small thumbnail width
$cfg['frontend_config3']['s_width'] = 150;

//set small thumbnai height
$cfg['frontend_config3']['s_height'] = 90;

//set enlarged thumbnail width
$cfg['frontend_config3']['b_width'] = 180;

//set enlarged phot height
$cfg['frontend_config3']['b_height'] = 180;

//gallery slideshow setting
$cfg['frontend_config3']['CBss'] = false;
$cfg['frontend_config3']['CBspeed'] = 5000;

//frontend record per page
$cfg['frontend_config3']['f_rpg'] = 24;

//set frontend image order by name or time uploaded
$cfg['frontend_config3']['f_order'] = "time"; # name / time
$cfg['frontend_config3']['f_order_type'] = "desc"; # asc / desc
##################################
# View type 3 end
##################################

##################################
# View type 4 start
##################################
//set opacity of small photos
$cfg['frontend_config4']['po'] = 0.8;

//set padding between photos
$cfg['frontend_config4']['pp'] = 11;

//fine tune gallery_wrapper top margin
$cfg['frontend_config4']['gwt'] = -20;

//fine tune gallery_wrapper left margin
$cfg['frontend_config4']['gwl'] = 22;

//set small thumbnail width
$cfg['frontend_config4']['s_width'] = 20;

//set small thumbnai height
$cfg['frontend_config4']['s_height'] = 130;

//set enlarged thumbnail width
$cfg['frontend_config4']['b_width'] = 180;

//set enlarged phot height
$cfg['frontend_config4']['b_height'] = 180;

//gallery slideshow setting
$cfg['frontend_config4']['CBss'] = false;
$cfg['frontend_config4']['CBspeed'] = 5000;

//frontend record per page
$cfg['frontend_config4']['f_rpg'] = 60;

//set frontend image order by name or time uploaded
$cfg['frontend_config4']['f_order'] = "time"; # name / time
$cfg['frontend_config4']['f_order_type'] = "desc"; # asc / desc
##################################
# View type 4 end
##################################



?>