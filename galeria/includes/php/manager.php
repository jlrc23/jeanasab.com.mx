<?php

class manager {

	function manager($page_no='',$record_per_page='',$photo_folder_http='',$photo_folder_path='',$folder_name='',$keyword='')
	{		
		global $cfg;
		
		$this->no_file_msg 		= $cfg['no_file_msg'];
		$this->no_folder_msg 	= $cfg['no_folder_msg'];
		
		$this->photo_folder_http	= $photo_folder_http;
		$this->photo_folder_path	= $photo_folder_path;
		
		$this->org_folder_path		= $photo_folder_path.'/org';
		$this->org_folder_http		= $photo_folder_http.'/org';
		
		$this->thumb_folder_path	= $photo_folder_path.'/thumb';
		$this->thumb_folder_http	= $photo_folder_http.'/thumb';
		
		$this->large_folder_path	= $photo_folder_path.'/large';
		$this->large_folder_http	= $photo_folder_http.'/large';
		
		$this->keyword				= $keyword;
		$this->image_sorting		= "time"; # option: time/name
		$this->image_sorting_type	= "desc"; # option: time/name
		# current viewing folder
		$this->view_folder_name = $folder_name;
		
		$this->file_name 		= "";	
		$this->extension 		= "";
	
		$this->pageNo 			= trim($page_no);
		
		
		$this->record_per_page	= trim($record_per_page);
		$this->folder_name 		= trim($folder_name);
		
		
		# always look at org folder when retriving info
		$this->folder_location = ($this->folder_name=='')?$this->org_folder_path:$this->org_folder_path.'/'.$this->folder_name; 
		# the excat path of the image file
		$this->org_file_location	= '';	
		$this->thumb_file_location	= '';	
		$this->large_file_location	= '';	
		
		$this->org_file_size = '';
		$this->thumb_file_size = '';
		$this->large_file_size = '';
				
		$this->pageNo 			= ($this->pageNo=='0'||$this->pageNo!='')?$this->pageNo:"1";
		
		
		$this->record_per_page 	= ($this->record_per_page=='0'||$this->record_per_page!='')?$this->record_per_page:"10";
		$this->form_name		= 'form1';
				
		$this->record_from		= ($this->pageNo-1) * $this->record_per_page;
		$this->record_to 		= $this->record_from + $this->record_per_page-1;
		
		//$this->debug_r($this);
	}
	
	function hasAdminRight()
	{
		if($_SESSION['is_login']==true)
		return true;	
	}
	
	function checkAccess()
	{
		if(!$this->hasAdminRight())
		header("location:login.php");
	}
	function genAdminMenu($curr)
	{
		switch ($curr)
		{
			# manager
			case 'm':
				$html = '<li class="current">Photo Manage</li>
	    			 <li><a href="upload.php">Upload Photo</a></li>
	    			 <li><a href="category.php">Category</a></li>';
    		break;	 
    		#upload
			case 'u':
				$html = '<li><a href="manager.php">Photo Manage</a></li>
    			 <li class="current">Upload Photo</li>
    			 <li><a href="category.php">Category</a></li>';
    		break;
    		# category	 	
			case 'c':
				$html = '<li><a href="manager.php">Photo Manage</a></li>
    			 <li><a href="upload.php">Upload Photo</a></li>
    			 <li class="current">Category</li>';
    		break;	 
		}
		
    			 
		return $html;
	}
	function getImageInfo()
	{
		
		if($this->folder_name!='' && $this->file_name!='' && $this->extension!='')
		{
			$this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
		}
		
		if($this->identifier!='')
		{			
			$this->org_file_location 						= $this->org_folder_path.'/'.$this->identifier;
			$this->thumb_file_location 						= $this->thumb_folder_path.'/'.$this->identifier;
			$this->large_file_location 						= $this->large_folder_path.'/'.$this->identifier;
			$this->folder_name			=	$this->getFolderName($this->org_file_location);
			$this->folder_location		= 	$this->org_folder_path.'/'.$this->folder_name;
								
			if(is_file($this->org_file_location) && is_file($this->thumb_file_location) && is_file($this->large_file_location))
			{
				$this->org_file_size 							= filesize($this->org_file_location);
				$this->thumb_file_size 							= filesize($this->thumb_file_location);
				$this->large_file_size 							= filesize($this->large_file_location);
				
				list($this->org_width,$this->org_height)		= getimagesize($this->org_file_location);
				list($this->thumb_width,$this->thumb_height)	= getimagesize($this->thumb_file_location);
				list($this->large_width,$this->large_height)	= getimagesize($this->large_file_location);
				
				$this->org_last_modified 						= date("Y-m-d G:i:s", filemtime($this->org_file_location));
				$this->thumb_last_modified 						= date("Y-m-d G:i:s", filemtime($this->thumb_file_location));
				$this->large_last_modified 						= date("Y-m-d G:i:s", filemtime($this->large_file_location));
			}
		}
		
	}
	function genFrontendImage()
	{			
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		$image_arr = $this->getFolderImage();		
				
		for($a=0;$a<sizeof($image_arr);$a++)
		{
			$this->file_name = $lf->file_name($image_arr[$a]['org']['path']);
			
			# get folder name
			$this->folder_name = $this->getFolderName($image_arr[$a]['org']['path']);
			
			if($this->view_folder_name!='')
			{
				if($this->view_folder_name != $this->folder_name)
					continue;
			}
			$image_url = $this->sgUrlDecode($image_arr[$a]['thumb']['http']);
			$photo_html.='<div class="photo">
							<a href="'.$image_arr[$a]['large']['http'].'" rel="colorbox" class="photo_name" pictitle="'.$this->file_name.'">
							<div class="thumb" style="background-image:url('.$image_url.')"></div>
							</a>
							</div>';   	
		}
		
		$photo_html.='<div class="clear"></div></div><!-- gallery_wrapper end -->';
		if(sizeof($image_arr)>0)$photo_html.=$this->genGoPage();
		
		return $photo_html;
	}
	
	function sgUrlDecode($link)
	{
		$link = str_replace(" ","%20",$link);
		$link = str_replace("(","%28",$link);
		$link = str_replace(")","%29",$link);
		
		return $link;	
	}
	
	function genPaginationHiddenField()
	{
		$hidden_value 		= '
							   <input type="hidden" id="pageNo" name="pageNo" value="'.$this->pageNo.'"/>
							   <input type="hidden" id="recordPerPage" name="recordPerPage" value="'.$this->record_per_page.'"/></table>';
		return $hidden_value; 
	}
	
	# Usually, this function should only return those that are in browsing range. 
	# Set $read_all = true when need to read all the files in the folder
	function getFolderImage($read_all=false,$image_to_view='') 
	{		
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		# get the image in specified folder
		$folder_list = $lf->return_folderlist($this->folder_location);

		$show_this = false;
		$sort_arr = array();
				
		for($a=0;$a<sizeof($folder_list);$a++)
		{	
			$this->extension = $lf->getFileExtension($folder_list[$a]);
			if($this->extension!='')
			{
				$this->folder_name = $this->getFolderName($folder_list[$a]);
				$this->file_name = $lf->file_name($folder_list[$a]);
				$this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
				
				if($this->image_sorting=="name")
				{
					$sort_arr[$this->identifier] = $this->file_name;	
				}
				else
				{
					$tmp_arr[$this->identifier]  = $this->identifier;
					$sort_arr[$this->identifier] = date("Y-m-d G:i:s", filemtime($this->org_folder_path.'/'.$this->identifier));
				}
				
			}
		}
		
		if($this->image_sorting_type=='desc')
			arsort($sort_arr);
		else
			asort($sort_arr);
		
		if($this->image_sorting=="time")
		{
			foreach($sort_arr as $this->identifier =>$value)
			{
				$sort_arr[$this->identifier] = $tmp_arr[$this->identifier];
			}
		}
				
		# count the file number
		$record_count=0;
		
		# for array indexing
		$arr_count = 0;
		
		# count how many pics are being displayed
		$count_display = 0;

		foreach($sort_arr as $this->identifier => $this->file_name)
		{	
			$show_this = false;

			# if specified a file to look for
			if($image_to_view!='')
			{
				if($image_to_view == $this->identifier)
				$show_this = true;
			}
			else
			{
				# if in current range, put into array	
				if($this->keyword!='')
				{				
					if(trim(strpos(strtoupper($this->file_name),strtoupper($this->keyword)))!='')
					{
						$show_this = true;
						$count_display++;
					}
					
				}
				else
				{
					if($record_count>=$this->record_from && $record_count<=$this->record_to )
					{
						$show_this = true;
					}	
					
					$count_display++;
				}
				
				if($read_all==true)
				{
					$show_this = true;
					$count_display++;
				}
			}
			if($show_this==true)
			{
				#original
				$photo_src[$arr_count]['org'] = array('http'=>$this->org_folder_http.'/'.$this->identifier,'path'=>$this->org_folder_path.'/'.$this->identifier);
				#thumb
				$photo_src[$arr_count]['thumb'] = array('http'=>$this->thumb_folder_http.'/'.$this->identifier,'path'=>$this->thumb_folder_path.'/'.$this->identifier);
				#large
				$photo_src[$arr_count]['large'] = array('http'=>$this->large_folder_http.'/'.$this->identifier,'path'=>$this->large_folder_path.'/'.$this->identifier);
				
				$arr_count++;
			}
			$record_count++;					
		}
		
		# set pagination information
		$this->total_row = $count_display;
		$this->max_page = ceil($this->total_row/$this->record_per_page);

		return $photo_src;
	}
	
	function genGoPage()
	{	
		# control the page option
		$max_page_option = 1;
		$x ='<div class="page_area"><span class="pages">Page '.$this->pageNo.' of '.$this->max_page.'</span>';
		# first page button
		$x.= ($this->pageNo>1)?'<a href="javascript:easyGallery.gopage(1,document.'.$this->form_name.'); " title="First Page">&laquo;</a>':'';
		# previous page button
		$x.= ($this->pageNo>1)?'<a href="javascript:easyGallery.gopage('.($this->pageNo-1).',document.'.$this->form_name.'); " title="Previous Page">&lsaquo;</a>':'';
		
		if($this->pageNo-$max_page_option-1>0)
		$x.='<span class="dot">...</span>';
		
		for($a=0;$a<$this->max_page;$a++)
		{
			$page = $a+1;
			if($page == $this->pageNo)
			{
				$x.='<span class="current">'.$page.'</span>';
			}
			else
			{
				if($page<$this->pageNo && $page>=$this->pageNo-$max_page_option)
				{
					$x.='<a href="javascript:void(0)" onClick="easyGallery.gopage('.$page.',document.'.$this->form_name.'); " title="Page '.$page.'">'.$page.'</a>';	
				}
				else if($page>$this->pageNo && $page<=$this->pageNo+$max_page_option)
				{
					$x.='<a href="javascript:void(0)" onClick="easyGallery.gopage('.$page.',document.'.$this->form_name.'); " title="Page '.$page.'">'.$page.'</a>';
				}
				
			}
		}
		if($this->max_page-$this->pageNo>$max_page_option)
		$x.='<span class="dot">...</span>';
		
		# next page button
		$x .= ($this->pageNo<$this->max_page)?'<a href="javascript:void(0)" onClick="easyGallery.gopage('.($this->pageNo+1).',document.'.$this->form_name.'); " title="Next Page">&rsaquo;</a>':'';
		# last page button
		$x .= ($this->pageNo<$this->max_page)?'<a href="javascript:void(0)" onClick="easyGallery.gopage('.($this->max_page).',document.'.$this->form_name.'); " title="Last Page">&raquo;</a>':'';
		
		$x.='</div>';
		return $x;		
			
	}
	
	function deleteImage()
	{
		global $cfg;
		if(isset($this->file_name) && trim($this->file_name)!="")
		{
			$org_file_path = $this->org_folder_path.'/'.$this->file_name;
			$thumb_file_path = $this->thumb_folder_path.'/'.$this->file_name;
			$large_file_path = $this->large_folder_path.'/'.$this->file_name;
			
			if(is_file($org_file_path) && is_file($thumb_file_path) && is_file($large_file_path))
			{
				unlink($org_file_path);
				unlink($thumb_file_path);
				unlink($large_file_path);
				return 	"Image removed";
			}
		}
		
		return "Cannot remove image";
	}
		
	function debug_r($arr) 
	{
		echo "<i>[debug]</i>\n<PRE>\n";
		print_r($arr);
		echo "\n</PRE>\n<br>\n";
	}
	
	function genAdminPhotoManager($notice_msg)
	{
		include_once('libfilesystem.php');
	
		$lf = new libfilesystem();
		
		$read_all = false;
		$image_to_view = '';
		
		$hidden_value		= $this->genPaginationHiddenField();
		
		# if an image just got updated, show that image
		if($_POST['identifier'])
		{
			$this->identifier = $_POST['identifier'];
			$image_to_view = $this->identifier;	
			$this->getImageInfo();
			$this->view_folder_name = $this->folder_name; 
		}
	
		$image_arr = $this->getFolderImage('',$image_to_view);
		
		if(sizeof($image_arr)==0)
		{
			$image_html.='<tr><td colspan="6" style="text-align:center" align="center">'.$this->no_file_msg.'</td></tr>';
		}
		else
		{
			$record_per_page_input 	= $this->genRecordPerPageInput();
			$go_page 				= $this->genGoPage();
			
			for($a=0;$a<sizeof($image_arr);$a++)
			{
				# get file name
				$this->file_name = $lf->file_name($image_arr[$a]['org']['path']);
				
				# get file extension
				$this->extension = $lf->getFileExtension($image_arr[$a]['org']['path']);
				
				# get folder name
				$this->folder_name = $this->getFolderName($image_arr[$a]['org']['path']);
							
				# set identifier
	            $this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
	            
	            
	            # get image info
	            $this->getImageInfo();
				
	            # resize image for display
				if($this->thumb_width>150)
					$resize = 'width="150"';
				else
					$resize = '';
	            
	            # apply css
				$css = $a%2==0?'class="alt_row"':'';
				
				# record count
				$record_count = $this->record_from+$a+1;

				$image_html.='<tr '.$css.'>
	            	<td>'.$record_count.'</td>
	                <td><a href="'.$image_arr[$a]['large']['http'].'" target="_blank" title="view original size photo"><img src="'.$image_arr[$a]['thumb']['http'].'" '.$resize.' /></a></td>
	                <td>
	                	<ul class="pic_info" id="'.$this->identifier.'_ul">
						'.$this->genAdminPhotoManagerRowInfo().'
	                    </ul>
	                </td>
	                <td>'.$this->org_last_modified.'</td>
					<td id="'.$this->identifier.'_btn"><a href="javascript:easyGallery.editImage(\''.$this->identifier.'\')" class="eidt_btn" title="edit"></a><a href="javascript:easyGallery.deleteImage(\''.$this->identifier.'\')" class="del_btn" title="delete"></a></td>
					<td><input type="checkbox" id="'.$this->identifier.'_cb" id="image_cb[]" name="image_cb[]" value="'.$this->identifier.'" ></td>
	            </tr>';
	        }
		}			
		
		if($notice_msg!='')
		$html = ' <div class="notice_box" id="notice_box"><div class="success">'.$notice_msg.'</div></div>';
		
		
		$html .= '<div class="table_top_left_tool"> 
	            '.$this->getFolderSelection('onChange=" document.getElementById(\'pageNo\').value=\'\'; document.form1.submit();"').'
	    		</div>
	            <div class="table_right_tool">
				<div class="searchbox"><!-- searchbox start-->
				<input type="text" class="textfield" size="24" name="keyword" id="keyword" value="'.$this->keyword.'" onFocus="$(this).parent().css(\'background-position\', \'0 -21px\');" onBlur="$(this).parent().css(\'background-position\', \'0 0\');"/>
				<input type="submit" class="button" value="">
				</div><!-- searchbox end --> 
				</div>
				<br class="clear" />
				<div class="table_delete_tool"><input type="button" value="Delete" onclick="easyGallery.deleteSelectedImg();" class="action_btn submit" name="delete"></div>
	            <br class="clear" />
	            <div id="conten_div">
	    		<table class="common_table">
	            <thead>
	            <tr>
	            	<th class="num_col">#</th>
	            	<th class="image_col">Image</th>
	            	<th class="dynamic_col">Detail</th>
	                <th class="num2_col">Upload Date</th>
					<th class="act_col">Action</th>
					<th class="num_col"><input type="checkbox" id="master_checkbox" name="master_checkbox" onClick="easyGallery.toggleCheckbox(this)"></th>
				</tr>
	            </thead>
	            <tbody>            
	          '.$image_html.'
	            </tbody>
	            </table>
	            '.$record_per_page_input.'
				<div class="table_bottom_right_tool">
	            	'.$go_page.'
	            </div>
	            <br class="clear" />
	            </div>';
            	
		return $html.$hidden_value;
	}
	
	function genAdminPhotoManagerRowInfo()
	{
		
		$html = '<li><img src="includes/images/admin/photo.gif" title="Image Name"/> '.$this->file_name.$this->extension.'<br /></li>
                 <li><img src="includes/images/admin/folder.gif" title="Category"/> '.$this->folder_name.'</li>
                 <li><strong>Original Size</strong>: '.$this->org_width.' x '.$this->org_height.' px (<a href="download.php?link='.$this->org_folder_path.'/'.$this->identifier.'">Download</a>)</li>
                 <li><strong>Large Size</strong>: '.$this->large_width.' x '.$this->large_height.' px (<a href="download.php?link='.$this->large_folder_path.'/'.$this->identifier.'">Download</a>)</li>
                 <li><strong>Thumbnail</strong>: '.$this->thumb_width.' x '.$this->thumb_height.' px (<a href="download.php?link='.$this->thumb_folder_path.'/'.$this->identifier.'">Download</a>)</li>';
		return $html;
	}
	function genAdminPhotoUpload($notice_msg,$is_simple_upload)
	{
		global $cfg;
		
		if($notice_msg!='')
		$html = ' <div class="notice_box" id="notice_box"><div class="success">'.$notice_msg.'</div></div>';
			        			        
		$html.='<table class="form_table">
			        <tr>
			        <th>Select Catergory</th>
			        <td>'.$this->genSelectFolderDiv('radio','id="folder_name" name="folder_name"',true).'
			        </td>
			        </tr>
					<tr>
			        <th>Thumbnail Size</th>
			        <td>
			        Min. Width <input name="thumb_size_w" type="text" id="thumb_size_w" value="'.$cfg['thumb_default_width'].'" class="form_short"/>&nbsp;&nbsp;&nbsp;&nbsp;
			        Min. Height <input name="thumb_size_h" type="text" id="thumb_size_h" value="'.$cfg['thumb_default_height'].'" class="form_short"/><br />
					Crop photo to exact dimensions proportionally (not lesser than setting)
			        </td>
			        </tr>
			        <tr>
			        <th>Large Size</th>
					<td>
			        Max. Width <input name="large_size_w" type="text" id="large_size_w" value="'.$cfg['large_default_width'].'" class="form_short"/>&nbsp;&nbsp;&nbsp;&nbsp;
			        Max. Height <input name="large_size_h" type="text" id="large_size_h" value="'.$cfg['large_default_height'].'" class="form_short"/><br />
					Crop photo to exact dimensions proportionally (not greater than setting)
			        </td>
			        </tr>
					<tr>
			        <th>Original Size</th>
			        <td>* Original picture will be kept in "Org" folder</td>
			        </tr>';
		$upload_limit_msg = "*According to your server setting, your total file upload size cannot be more than <b>".ini_get('post_max_size')."</b>.<br/>
							 You may try to override it by setting <b>\$cfg['memory_limit']</b> and <b>\$cfg['upload_max_filesize']</b> in <b>config.php</b>.";
					//$html.='<tr><td colspan="2">'.$upload_limit_msg.'</td></tr>';
			        $html.='<tr>
			        <th>Upload File</th>
					<td id="upload_file_td">';
					
		
		if($is_simple_upload==true)
		{
			$html.='<input type="file" name="file[]" id="file[]"/>';
		}
		else
		{
			$html.='<input type="file" name="file[]" multiple><table id="files"></table>';
		}
		if($is_simple_upload==false)
		{
			//$html.='<tr><th colspan="2"><font color="red">*</font>Hold down the \'Ctrl\' key on your keyboard to select multiple files</th></tr>';	
			$hold_ctrl_html = '*Hold down the \'Ctrl\' key on your keyboard to select multiple files<br/>'.$upload_limit_msg;
			
		}
		$html.=$hold_ctrl_html.'</td></tr>';
		
		//$html.='<tr><th colspan="2"><font color="red">*</font>You cannot upload file larger than '.ini_get('post_max_size').'</th></tr>';	
		
		
		if($is_simple_upload==true)
		{
			$html.='<tr><th></th><td><a href="javascript:void(0)" onClick="easyGallery.addUpload();">+more images</a></div></td></tr>
			        <tr><th></th><td><div class="uploading" id="uploading" style="display:none"></div></td></tr>
					<tr><th></th><td>'.$upload_limit_msg.'</td></tr>
			        
			        </table>
			        <div class="action_row">
		            <input class="action_btn submit" type="button" value="Upload" onClick="easyGallery.uploadImage()"/>
					</div>';
		}
		$html.='</div>';	
		return $html;
	}
	
	function getFolderArray()
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		# get folder list
		$folder_arr = array();
		
		# get folder list, always look at org folder
		$folder_list = $lf->return_folderlist($this->org_folder_path);
				
		for($a=0;$a<sizeof($folder_list);$a++)
		{
			$folder = $this->getFolderName($folder_list[$a]);
						
			if($lf->getFileExtension($folder_list[$a])!='')
				$folder_arr[$folder]['image_count']++;
			else
				$folder_arr[$folder]['image_count']+=0;
			$folder_arr[$folder]['last_modified'] = date("Y-m-d G:i:s", filemtime($folder_list[$a]));	
				
		}
		
		# sort by folder name
		ksort($folder_arr);
		
		return $folder_arr;	
	}
	function genFrontendCategorySelection()
	{
		$folder_arr = $this->getFolderArray();
		
		$css = ($this->view_folder_name=='')?'class="current"':'';
		$list.='<li '.$css.'><a href="javascript:void(0)" onClick="$(\'#folder_name\').val(\'\');$(\'#pageNo\').val(\'1\');document.form1.submit();">All</a></li>';
		foreach($folder_arr as $folder_name=>$folder_obj)
		{
			$css = '';
			if($folder_name==$this->view_folder_name)
			{
				$current = '<div class="box"><div class="current">
				        	<a href="javascript:void(0)">'.$folder_name.'</a>
				        	</div></div>';
				$css = 'class="current"';        	
			}
			
			$list.='<li '.$css.'><a href="javascript:void(0)" onClick="$(\'#folder_name\').val(\''.$folder_name.'\');$(\'#pageNo\').val(\'1\');document.form1.submit();">'.$folder_name.'</a></li>';
		}
		if($current=='')
			$current = '<div class="box"><div class="current">
        				<a href="javascript:void(0)">All</a></div></div>';

        $html=' 	<div class="category">
    		<span class="label"></span>
        	'.$current.'
			<!-- category list start -->
            <div class="show_cat">
     		<ul class="category_list">
			'.$list.'
			</ul>
        	</div>';
        		
		return $html;
	}
	function getFolderSelection($action)
	{
		$folder_arr = $this->getFolderArray();
		
		# generate folder selection box
		$arr[] = array('','All');
		
		foreach($folder_arr as $folder_name=>$image_count_obj)
		{
			$arr[] = array($folder_name,$folder_name.' ('.$image_count_obj['image_count'].')');
		}
		
		$tags = 'id="folder_name" name="folder_name" class="col_filter" '.$action;
		
		$html = $this->genSelectionBox($arr, $tags, '', $this->view_folder_name);
		
		
		return $html;	
	}
	function genRecordPerPageInput()
	{
		$html = '<div class="table_bottom_left_tool">
			<div class="item_per_page">
			<span>Photos per page: </span>
			<select name="num_per_page" onChange="easyGallery.changeRecordPerPage('.$this->form_name.',this.options[this.selectedIndex].value);">';
		
		for($a=1;$a<=10;$a++)
		$html.='<option value='.($a*10).' '.($this->record_per_page==($a*10)? "SELECTED":"").'>'.($a*10).'</option>';				
		$html.='</select>
			</div>
            </div>';
		return $html;
	}
	
	function genSelectionBox($data, $tags, $default, $selected=""){

		$ReturnStr = "<select $tags>\n";
		if ($default!="")
		{
			$ReturnStr .= "<option value=\"\">{$default}</option>\n";
		}
		for ($i=0; $i<sizeof($data); $i++)
		{
			list($ID, $Name) = $data[$i];
			$SelectedStr = ($selected == $ID? "SELECTED":"");
			$ReturnStr .= "<option value=\"{$ID}\" {$SelectedStr}>{$Name}</option>\n";
		}
		$ReturnStr .= "</select>\n";

		return $ReturnStr;
	}
	
	function getFolderName($file_path)
	{
		$file_name = basename($file_path);
		
		if($file_name!='')
		{
			$tmp = str_replace($this->org_folder_path.'/','',$file_path);
			$folder = str_replace('/'.$file_name,'',$tmp);
			
			return $folder;
		}
	}
	
	function genEditImagePanel($image)
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
			
		# get file name
		$this->file_name = $lf->file_name($this->org_folder_path.'/'.$image);
				
		# get file extension
		$this->extension = $lf->getFileExtension($this->org_folder_path.'/'.$image);
		
		# get folder name
		$this->folder_name = $this->getFolderName($this->org_folder_path.'/'.$image);
		
		# identifier for this image row	
		$this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
		
				
		$tag = 'name="'.$this->identifier.'_folder_name" id="'.$this->identifier.'_folder_name"';
		
		$html='<li><img src="includes/images/admin/photo.gif" title="Image Name"/> <input class="pic_name" id="'.$this->identifier.'_img_name" name="'.$this->identifier.'_img_name" value="'.$this->file_name.'" />'.$this->extension.'</li>
               <li><img src="includes/images/admin/folder.gif" title="Category"/> <input type="radio" name="2" checked="checked"/>Move to<br />
			   '.$this->genSelectFolderDiv('radio',$tag).'</li>'; 
			   
		
		return $html;
	}
	
	function contructInfo($image)
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		$pos = strpos($image,"/");
		$folder_name = substr($image,0,$pos);
		$image_name = substr($image,$pos+1,strlen($image));
		
		$this->folder_location.='/'.$folder_name;
		
		$read_all = true;
		$image_arr = $this->getFolderImage($read_all);
		
		for($a=0;$a<sizeof($image_arr);$a++)
		{
			$folder_image_name = basename($image_arr[$a]['org']['path']);

			if($folder_image_name == $image_name)
			{
				# get file name
				$this->file_name = $lf->file_name($image_arr[$a]['org']['path']);
				
				# get file extension
				$this->extension = $lf->getFileExtension($image_arr[$a]['org']['path']);
				
				# get folder name
				$this->folder_name = $this->getFolderName($image_arr[$a]['org']['path']);
				
				# identifier for each image row
		        $this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
		    				        
	            # get image info
	            $this->getImageInfo();
            
			}
		}
	}
		
	function genSelectFolderDiv($type,$tag,$select_first=false)
	{
		$radio_btn = '';
		$folder_arr = $this->getFolderArray();
		
		$count = 0;
		
		foreach($folder_arr as $folder_name=>$image_count_obj)
		{
			$checked = ($this->folder_name==$folder_name)?'checked':'';
			if($select_first==true && $count==0)
				$checked = 'checked';
				
			if($type=='radio')
			$radio_btn.='<input type="radio" '.$tag.' value="'.$folder_name.'"  '.$checked.' />'.$folder_name.'<br />';
			if($type=='checkbox')
			$radio_btn.='<input type="checkbox" '.$tag.' value="'.$folder_name.'"  '.$checked.' />'.$folder_name.'<br />';
			
			$count++;
		}
		
		$html = '<div class="cat_select_box">'.$radio_btn.'</div>';
                
		return $html;
	}
	
	function saveImageInfo($image,$to_folder_name,$image_name)
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		$move_result = array();
		$rename_result = array();
		
		# get current file path
		$org_file 			= $this->org_folder_path.'/'.$image;
		$thumb_file 		= $this->thumb_folder_path.'/'.$image;
		$large_file 		= $this->large_folder_path.'/'.$image;		
		
		# look up from org folder
		$this->folder_name	= $this->getFolderName($org_file);
		$this->extension 	= $lf->getFileExtension($org_file);
		$this->file_name = $image_name;		
		# if the name is specified and changed
		if($to_folder_name!='' && $this->folder_name!=$to_folder_name)
		{
			# set new file path
			$new_file_org_location = $this->org_folder_path.'/'.$to_folder_name.'/'.$this->file_name.$this->extension;
			$new_file_thumb_location = $this->thumb_folder_path.'/'.$to_folder_name.'/'.$this->file_name.$this->extension;
			$new_file_large_location = $this->large_folder_path.'/'.$to_folder_name.'/'.$this->file_name.$this->extension;
			
			# move the files
			$lf->lfs_move($org_file, $new_file_org_location);
			$lf->lfs_move($thumb_file, $new_file_thumb_location);
			$lf->lfs_move($large_file, $new_file_large_location);
			
			$this->folder_name 	= $to_folder_name;
		}
				
		# if move successfully
		if(!in_array(false,$move_result))
		{
			# if need to rename
			if($org_file!=$this->file_name)
			{
				
				# if all these files exist, rename them
				if(is_file($org_file) && is_file($thumb_file) && is_file($large_file))
				{					
					# set new file path
					$new_file_org_location = $this->org_folder_path.'/'.$this->folder_name.'/'.$this->file_name.$this->extension;
					$new_file_thumb_location = $this->thumb_folder_path.'/'.$this->folder_name.'/'.$this->file_name.$this->extension;
					$new_file_large_location = $this->large_folder_path.'/'.$this->folder_name.'/'.$this->file_name.$this->extension;
					
					$move_result[] = $lf->file_rename($org_file, $new_file_org_location);
					$move_result[] = $lf->file_rename($thumb_file, $new_file_thumb_location);
					$move_result[] = $lf->file_rename($large_file, $new_file_large_location);
														
					$this->file_name = $image_name;
			
				}
			}
			$this->identifier = $this->folder_name.'/'.$this->file_name.$this->extension;
		}
	}
	
	function genAdminCategoryManager($notice_msg)
	{
		
		if($notice_msg!='')
		$html = ' <div class="notice_box" id="notice_box"><div class="success">'.$notice_msg.'</div></div>';
		        
		$html.='<div class="table_top_left_tool">            
					<a href="javascript:easyGallery.addCategoryRow($(\'#category_table\'));" class="addnew">Add Category</a>
		            </div>
			
		            <div class="table_right_tool">            
					<div class="searchbox"><!-- searchbox start-->
					<input type="text" class="textfield" size="24" value="'.$this->keyword.'" name="keyword" onFocus="$(this).parent().css(\'background-position\', \'0 -21px\');" onBlur="$(this).parent().css(\'background-position\', \'0 0\');" onKeyDown="if(event.keyCode == 13)document.form1.submit();"/>
					<input type="button" class="button" value="" onClick="document.form1.submit()"/>
					</div><!-- searchbox end --> 
		            </div>
		            <br class="clear" />
		            <div id="conten_div">
		    		<table class="common_table" id="category_table">
		            <thead>
					<tr>
		            	<th class="num_col">#</th>
		            	<th class="dynamic_col">Category</th>
		            	<th class="num2_col">Images</th>
		                <th class="num2_col">Create Date</th>
		                <th class="act_col">Action</th>
		
					</tr>
		            </thead>
		            <tbody>'.$this->genCategoryManagementRecord().'	</tbody>
		            </table>
		            <br class="clear" />
		            </div>
		        </div>';	
        
        return $html;
	}
	
	function genCategoryManagementRecord()
	{
		$folder_arr = $this->getFolderArray();
		$count = 0;
		$html ='';
		
		
		$show_this = false;
		foreach($folder_arr as $folder_name=>$image_count_obj)
		{
			if($this->keyword!='')
			{							
				if(trim(strpos(strtoupper($folder_name),strtoupper($this->keyword)))=='')
				{
					continue;	
				}
			}
			
			# apply css
			$css = $count%2==0?'class="alt_row"':'';

			$html.='<tr '.$css.' id="'.$folder_name.'">
		            	<td>'.($count+1).'</td>
		                <td><span id="'.$folder_name.'" class="editable">'.$folder_name.'</span></td>
		                <td>'.$image_count_obj['image_count'].'</td>
		                <td>'.$image_count_obj['last_modified'].'</td>
		
		                <td><a href="javascript:easyGallery.deleteCategory(\''.$folder_name.'\')" class="del_btn" title="delete"></a></td>
		            </tr>';
		            
		    $count++;
		}

		if($count==0)
			$html.='<tr><td colspan="5" style="text-align:center" align="center">'.$this->no_folder_msg.'</td></tr>';
		
		$js=' $(\'.editable\').editable(\'includes/php/ajax.php\', {
					     indicator : "Saving...",
						tooltip   : "Click to edit",
						submitdata: {task:"update_category_name"},
						onblur: "submit",
						style:"height:70%",
						callback : function(value, settings) 
						{
							$(this).html(\'&nbsp;\');
							document.form1.submit();
						}
				});';
				
		return $html.'<script language="Javascript">$(document).ready(function(){'.$js.'});</script>';
	}
	
	function addNewFolder($new_folder)
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		
		$org_folder = $this->org_folder_path."/".$new_folder;
		$lf->folder_new($org_folder);
		chmod($org_folder, 0755);
				
		$thumb_folder = $this->thumb_folder_path."/".$new_folder;
		$lf->folder_new($thumb_folder);
		chmod($thumb_folder, 0755);
		
		$large_folder = $this->large_folder_path."/".$new_folder;
		$lf->folder_new($large_folder);
		chmod($large_folder, 0755);
		
	}
	
	function deleteFolder()
	{
		if($this->folder_name!='')
		{
			include_once('libfilesystem.php');
			$lf = new libfilesystem();
						
			$lf->folder_remove_recursive($this->org_folder_path."/".$this->folder_name);
			$lf->folder_remove_recursive($this->large_folder_path."/".$this->folder_name);
			$lf->folder_remove_recursive($this->thumb_folder_path."/".$this->folder_name);
		}
	}
	
	function genBackendHeader()
	{
		$html = '<div class="header">
					<div class="top">
				    	<a href="#" class="logo"></a>
				        <!-- top right btn -->
				        <span class="admin_top_btn"><a href="index.php" class="top_btn" target="_blank">View gallery</a> | <a href="logout.php">Log me out</a></span>
					</div>
				</div>';
		return $html;		
	}
	
	function updateFolderName($new_name)
	{
		include_once('libfilesystem.php');
		$lf = new libfilesystem();
		
		$lf->file_rename('../../photo/org/'.$this->folder_name,'../../photo/org/'.$new_name);	
		$lf->file_rename('../../photo/large/'.$this->folder_name,'../../photo/large/'.$new_name);
		$lf->file_rename('../../photo/thumb/'.$this->folder_name,'../../photo/thumb/'.$new_name);
	}
	
	function genFrontendJSConfig($view_type)
	{
		global $cfg;
		
		
		foreach ($cfg['frontend_config'.$view_type] as $var_name => $value)
		{

			if((is_string($value) && ($value!='true' && $value!='false'))) 
				$js.= "var ".$var_name." = '".$value."';\n"; 
			else
			{ 
				if($value=='0')$value='0';
				$js.= "var ".$var_name." = ".$value.";\n";
			}
		}
			return $js;
	}
	
	function genBackendJSMessge()
	{
		global $cfg;
		$js = "<script language=\"Javascript\">\n";
		foreach ($cfg['js_msg'] as $var_name => $value)
		{
			if($value=='')$value = 'false';
			$js.= "var ".$var_name." = '".$value."';\n";		
		}
	
		$js.='</script>';
		return $js;
	}
	
	function useSimpleUpload()
	{
		$arr = $this->browser_info();
		$browser = $arr[0];
		$version = $arr[1];
		
		if($browser=='msie')
			return true;
		elseif($browser=='firefox' && $version<4)
			return true;	
	}
	function browser_info($agent=null) {
	  // Declare known browsers to look for
	  $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
	    'konqueror', 'gecko');
	
	  // Clean up agent and build regex that matches phrases for known browsers
	  // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
	  // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
	  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
	  $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
	
	  // Find all phrases (or return empty array if none found)
	  if (!preg_match_all($pattern, $agent, $matches)) return array();
	
	  // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
	  // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
	  // in the UA).  That's usually the most correct.
	  $i = count($matches['browser'])-1;
	  return array($matches['browser'][$i],$matches['version'][$i]);
	}
}
?>