jQuery(document).ready(function(){
		jQuery('#file-manager-panel').elfinder({
			url : URL.goodlayers + '/include/plugin/elfinder/connectors/php/connector.php'
		});
});

(function($) {
	
	var galdb;
	var globe = new Object();
	
	globe.directory_type = 'directory';
	globe.sound_type = 'audio/mpeg';
	
	globe.title_wrapper = $('<div class="galdb-set-title-wrapper" />');
		globe.title_label = $('<div class="galdb-set-title-label"> Set File Name </div>');
		globe.title_input = $('<input class="galdb-set-title-input" type="text" />');
		globe.title_wrapper.append( globe.title_label )
			.append( globe.title_input )
			.append('<div class="clear">');
	globe.description_wrapper = $('<div class="galdb-set-description-wrapper" />');
		globe.description_label = $('<div class="galdb-set-description-label"> Set Description Name </div>');
		globe.description_input = $('<textarea class="galdb-set-description-input" />');
		globe.description_wrapper.append( globe.description_label )
			.append( globe.description_input )
			.append('<div class="clear">');
	// folder setting
	globe.setting_wrapper = $('<div class="galdb-set-setting-wrapper" />');
		globe.setting_label = $('<div class="galdb-set-setting-label"> Thumbnail setting </div>');
		globe.setting_input = $('<select class="galdb-set-setting-input">\
			<option>Random Thumbnail</option>\
			<option>Choose Thumbnail</option>\
			</select>');
	globe.setting_wrapper.append( globe.setting_label )
			.append( globe.setting_input )
			.append('<div class="clear">');		
		globe.thumbnail_wrapper = $('<div class="galdb-set-thumbnail-wrapper" />');
		globe.thumbnail_label = $('<div class="galdb-set-thumbnail-label"> Set Thumbnail Image </div>');
		globe.thumbnail_input = $('<input type="text" class="galdb-set-thumbnail-input" />');
		globe.thumbnail_upload = $('<input type="button" class="galdb-set-thumbnail-upload" />');
		globe.thumbnail_wrapper.append( globe.thumbnail_label )
			.append( globe.thumbnail_input )
			.append( globe.thumbnail_upload )
			.append('<div class="clear">');		
	// image setting
	globe.image_setting_wrapper = $('<div class="galdb-set-setting-wrapper" />');
		globe.image_setting_label = $('<div class="galdb-set-setting-label"> Lightbox setting </div>');
		globe.image_setting_input = $('<select class="galdb-set-setting-input">\
			<option>Current Thumbnail</option>\
			<option>Choose URL</option>\
			</select>');
		globe.image_setting_wrapper.append( globe.image_setting_label )
			.append( globe.image_setting_input )
			.append('<div class="clear">');	
	// lightbox url
	globe.lightbox_wrapper = $('<div class="galdb-set-title-wrapper" />');
		globe.lightbox_label = $('<div class="galdb-set-title-label"> Lightbox URL </div>');
		globe.lightbox_input = $('<input class="galdb-set-title-input" type="text" />');
		globe.lightbox_wrapper.append( globe.lightbox_label )
			.append( globe.lightbox_input )
			.append('<div class="clear">');	
	
	globe.form_submit = $('<input class="galdb-set-form-submit" type="button" />');	
	globe.now_loading = $('<div class="galdb-now-loading" />');
	
	// bind the upload image button
	globe.thumbnail_upload.click(function(){
		tb_show('Upload Media', 'media-upload.php?post_id=&type=image&amp;TB_iframe=true');
		window.send_to_editor = function(html){
			//thumb_url = $('img',html).attr('src');
			globe.thumbnail_input.val( $(html).attr('href') );
			globe.thumbnail_input.attr( 'data-attid', $(html).attr('attid') );
			tb_remove();
		}
		return false;
	});
	globe.thumbnail_input.change(function(){
		jQuery(this).attr('data-attid','');
	});
	
	function change_select_setting( selection ){
		if( selection == 'Choose Thumbnail' ){
			globe.thumbnail_wrapper.slideDown();
		}else if( selection == 'Random Thumbnail' ){
			globe.thumbnail_wrapper.slideUp();
		}else if( selection == 'Current Thumbnail' ){
			globe.lightbox_wrapper.slideUp();
		}else if( selection == 'Choose URL' ){
			globe.lightbox_wrapper.slideDown();
		}else{
			globe.thumbnail_wrapper.slideUp();
			globe.lightbox_wrapper.slideUp();
		}
	}
	globe.setting_input.change(function(){
		change_select_setting( jQuery(this).val() );
	});
	globe.image_setting_input.change(function(){
		change_select_setting( jQuery(this).val() );
	});	
	
	// append the save option to gdldb wrapper
	$.fn.init_galdb = function(){
		galdb = this;
		galdb.append( globe.title_wrapper )
			.append( globe.description_wrapper.hide() )
			.append( globe.setting_wrapper.hide() )
			.append( globe.image_setting_wrapper.hide() )
			.append( globe.thumbnail_wrapper.hide() )
			.append( globe.lightbox_wrapper.hide() )
			.append( globe.form_submit )
			.append('<div class="clear">')
			.append( globe.now_loading );
	}
	
	// call the get_galdb_file_title function when choosing the image
	$.fn.gal_img_clicked = function( rel ){	
	
		globe.now_loading.addClass('onload');
		
		var data_name = rel + '\\' + $(this).children('.gal-label-attr').filter(':first').attr('data-name');
		var data_type = $(this).children('.gal-label-attr').filter(':first').attr('data-type');
		
		// Decide which field will be shown
		if( globe.directory_type == data_type ){
			globe.description_wrapper.slideDown();
			// globe.thumbnail_wrapper.slideDown();
			globe.setting_wrapper.slideDown();
			globe.image_setting_wrapper.slideUp();
			globe.lightbox_wrapper.slideUp();
		}else if(globe.sound_type == data_type ){
			globe.description_wrapper.slideUp();
			globe.thumbnail_wrapper.slideDown();
			globe.setting_wrapper.slideUp();	
			globe.image_setting_wrapper.slideUp();		
			globe.lightbox_wrapper.slideUp();			
		}else{
			globe.description_wrapper.slideUp();
			globe.thumbnail_wrapper.slideUp();
			globe.setting_wrapper.slideUp();
			globe.image_setting_wrapper.slideDown();
			// globe.lightbox_wrapper.slideDown();
		}
		
		// Ajax call to fetch data from database
		jQuery.post( URL.ajaxurl, 'action=get_galdb_file_title&name=' + data_name , function(data){
			globe.title_input.val(data.title);
			globe.thumbnail_input.val(data.thumbnail_url);
			if( typeof(data.thumbnail_id) != 'undefined' ){
				globe.thumbnail_input.attr('data-attid', data.thumbnail_id);
			}else{
				globe.thumbnail_input.attr('data-attid', '');	
			}
			globe.title_input.attr('data-name',data_name);
			globe.title_input.attr('data-type',data_type);
			globe.now_loading.removeClass('onload');
			
			if( globe.directory_type == data_type ){
				globe.description_input.val(data.description);
				globe.setting_input.val(data.setting);
				change_select_setting(data.setting);
			}else if(globe.sound_type == data_type ){
			}else{
				globe.lightbox_input.val(data.description);
				globe.image_setting_input.val(data.setting);
				change_select_setting(data.setting);				
			}
		}, 'json');
	}
	
	// submit the title
	globe.form_submit.click(function(){
		if( globe.title_input.attr('data-name') ){
			globe.now_loading.addClass('onload');
		
			var data_send = '&name=' + globe.title_input.attr('data-name');
			data_send = data_send + '&type=' + globe.title_input.attr('data-type');
			data_send = data_send + '&title=' + globe.title_input.val();
			data_send = data_send + '&thumbnail=' + globe.thumbnail_input.attr('data-attid');
			
			var data_type = globe.title_input.attr('data-type');

			if( globe.directory_type == data_type ){
				data_send = data_send + '&description=' + globe.description_input.val();
				data_send = data_send + '&setting=' + globe.setting_input.val();
			}else if(globe.sound_type == data_type ){
			}else{
				data_send = data_send + '&description=' + globe.lightbox_input.val();
				data_send = data_send + '&setting=' + globe.image_setting_input.val();			
			}
			
			jQuery.post( URL.ajaxurl, 'action=set_galdb_file_title' + data_send, function(data){
				if( data.success == '1' ){
					alert('Save Complete');
				}else{
					alert('Save Failed');
				}
				globe.now_loading.removeClass('onload');
			}, 'json');
			
		}else{
			alert( 'Please select the elements before saving value.' );
		}
	});
})(jQuery);