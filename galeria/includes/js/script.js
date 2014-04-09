var easyGallery = (function() {

    var g_edit = false;
    var doc_obj = document;

    return {
        editImage : function(identifier) {
			if(g_edit==true)
			{
				alert(editing_warn_msg);	
			}
			else
			{
				easyGallery.displayAjaxLoading(identifier+'_ul',loading_msg);
				
				$.post("includes/php/ajax.php", {task: 'edit_image',image:identifier},  
					function(data, textStatus)
					{
						doc_obj.getElementById(identifier+'_ul').innerHTML = data;
						var btn_html = '<input name="button" class="action_btn submit" type="button" onClick="easyGallery.saveImageInfo(\''+identifier+'\');" value="Save"/>';
						btn_html +='<input class="action_btn cancel" type="button" onClick="easyGallery.cancelEdit(\''+identifier+'\');" value="Cancel"/>';
						doc_obj.getElementById(identifier+'_btn').innerHTML = btn_html;
						
						// set it to edit mode, prevent user to edit other row
						g_edit = true;
					}
						
				);		
			}
        },
        cancelEdit: function(identifier)
		{
			// set edit mode to false
			g_edit = false;
			
			easyGallery.displayAjaxLoading(identifier+'_ul',loading_msg);
			
			$.post("includes/php/ajax.php", {task: 'cancel_edit',image:identifier},
					function(data, textStatus)
					{
						doc_obj.getElementById(identifier+'_ul').innerHTML = data;
						var btn_html = '<a href="javascript:easyGallery.editImage(\''+identifier+'\')" class="eidt_btn" title="edit"></a>';
						btn_html += '<a href="javascript:easyGallery.deleteImage(\''+identifier+'\')" class="del_btn" title="delete"></a>';
						doc_obj.getElementById(identifier+'_btn').innerHTML = btn_html;
					
					}
						
				);	
		},
		
		saveImageInfo: function(identifier)
		{	
			//var pageNo = ($('#pageNo').length)?$('#pageNo').val():'';
			var pageNo = (doc_obj.getElementById('pageNo').length)?$doc_obj.getElementById('pageNo').value:'';
			
			var image_name 		= doc_obj.getElementById(identifier+'_img_name').value;
			var folder_radio	= identifier+'_folder_name';
			var folder_name		= $('input:radio[name="'+folder_radio+'"]:checked').val();
			var ul_obj = doc_obj.getElementById(identifier+'_ul');
			var btn_obj = doc_obj.getElementById(identifier+'_btn');
			
			var valid = easyGallery.validateName(image_name);
			
			if(valid==true)
			{
				$.post("includes/php/ajax.php", {task: 'save_image_info',image:identifier,image_name:image_name,folder_name:folder_name,pageNo:pageNo},
					function(data, textStatus)
					{
						g_edit = false;
						var msg = 'Image updated';
						doc_obj.getElementById('msg').value = msg;
						doc_obj.form1.submit();
					}
						
				);
			}
		},
		deleteImage: function (image)
		{
			if(confirm(remove_image_warn_msg))
			{
				
				$.post("includes/php/ajax.php", {task: 'delete_image',file_name:image},  
					function(data, textStatus){
						doc_obj.form1.submit();
					}
						
				);
			}
		},
		changeRecordPerPage: function (obj,size)
		{
			obj.pageNo.value=1;
			obj.recordPerPage.value=size;
			doc_obj.form1.submit();	
		},
        updateCategoryEnterEvent: function (e)
		{
			// if press enter in textbox
			if(e.keyCode == 13)
			{
				easyGallery.addNewCategory();
			}
		},
		addNewCategory: function ()
		{
			var new_folder = (doc_obj.getElementById('new_folder'))?doc_obj.getElementById('new_folder').value:'';
			var valid = easyGallery.validateName(new_folder);
			if(valid==true)
			{
				g_edit = false;
							
				$.post("includes/php/ajax.php", {task: 'add_new_folder',new_folder:new_folder},  
					function(data, textStatus){
						
						var msg = 'Category added';
						doc_obj.getElementById('msg').value = msg;
						doc_obj.form1.submit();
					}
				);
			}	
		},
		addCategoryRow: function(jQtable)
		{
			if(g_edit==true)
			{
				alert(editing_warn_msg);	
			}
			else
			{
				jQtable.each(function(){
			        var $table = $(this);
			        // Number of td's in the last table row
			        var td_count = $('tr:last td', this).length;
			        var tr_count = $('tr', this).length ;
			        
			        // if there is no folder, remove the row that said "No file"
			        if(td_count==1)
			        {
						easyGallery.removeLastRow();
						tr_count = 1;
					}
			        
			        var css = tr_count%2==0?'class="alt_row"':'';
			        
			        var tds = '<tr '+css+' id="new_row">';
			        tds += '<td>&nbsp;</td><td><input type="text" name="new_folder" id="new_folder" class="pic_name" onKeyDown="easyGallery.updateCategoryEnterEvent(event);" onBlur="easyGallery.addNewCategory();"/></td><td>--</td><td>--</td><td><a href="javascript:easyGallery.removeThisRow();" class="del_btn" title="delete"></a></td>';
			        tds += '</tr>';
			        
			        if($('tbody', this).length > 0){
			            $('tbody', this).prepend(tds);
			        }else {
			            $(this).prepend(tds);
			        }
			    });
			    g_edit = true;
		    }
		},
		removeThisRow: function ()
		{
			
			$('#new_row').remove();
			g_edit=false;
		},
		validateName: function(str)
		{
		        var re = /['":<>\\\/\|\*\?"]/;
		        if (re.test(str)) {
		                alert(invalid_name_msg);
		                return false;
		        }else{
		                return true;
		        }
		},
		deleteCategory: function (folder_name)
		{ 	
			if(confirm(remove_cat_msg))
			{
				$.post("includes/php/ajax.php", {task: 'delete_category',folder_name:folder_name},  
						function(data, textStatus)
						{
							var msg = 'Category deleted';
							doc_obj.getElementById('msg').value = msg;
							doc_obj.form1.submit();
						}
					);
			}
		},
		removeLastRow: function (row)
		{
			$('#category_table tr:last').remove();
			
			g_edit=false;
		},
		uploadImage: function()
		{
			var folder_name		= $('input:radio[name=folder_name]:checked').val();
			if(typeof(folder_name)=='undefined')
				alert(select_folder_msg);
			else	
			{
				$('#uploading').toggle();
				doc_obj.getElementById('uploading').innerHTML = upload_image_msg;
				doc_obj.form1.submit();
			}
		},
		gopage: function(page, obj)
		{
			obj.pageNo.value=page;
			doc_obj.form1.submit();
		},
		displayAjaxLoading: function(div,msg)
		{
			doc_obj.getElementById(div).innerHTML = '<div class="admin_loading">'+msg+'</div>'; 
		},
		addUpload: function()
		{
			$('#upload_file_td').append('<br /><input type="file" name="file[]" id="file" />');
		},
		toggleCheckbox: function (obj)
		{
			if (!obj.checked){
				$('input:checkbox').attr('checked','');
			}
			else{
				$('input:checkbox').attr('checked','checked');
			}
		
		},
		deleteSelectedImg: function()
		{
			
			var mc_checked = ($('#master_checkbox').attr('checked'));
			var ct_check = $("input:checked").length;
			if(ct_check==0 || (ct_check==1 && mc_checked == true))
			{
				alert(select_image_msg);
				return false;
			}
			else
			{
				if(confirm(delete_selected_image))
				{
					document.form1.method="post";	
					document.form1.action="delete_action.php";
					document.form1.submit();
				}
			}
		}

	         
    };

}());






