<?php

	// create the database when the theme is activated
	if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ){
		create_galdb_table();
	}
	function create_galdb_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . "galdb";
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL PRIMARY KEY,
			title VARCHAR(255),
			description VARCHAR(255),
			thumbnail VARCHAR(20),
			setting VARCHAR(20),
			type VARCHAR(20),
			UNIQUE KEY id (id)
			) CHARSET=UTF8;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
	
	// get the file title from database
	add_action('wp_ajax_get_galdb_file_title','get_galdb_file_title');
	function get_galdb_file_title(){
		global $wpdb;
		
		$return_val = array('title'=>'');
		$table_name =  $wpdb->prefix . 'galdb';

		$_POST['name'] = str_replace('/', '\\', $_POST['name']);
		$_POST['name'] = str_replace('\\\\', '\\', $_POST['name']);
		$_POST['name'] = mysql_real_escape_string( $_POST['name'] );		
		
		$sql = 'SELECT * FROM ' . $table_name;
		$sql = $sql . ' WHERE name = \'' . $_POST['name'] . '\';';
		
		$galdb_query = $wpdb->get_results( $sql );
		if( !empty( $galdb_query ) ){
			$thumbnail_src = wp_get_attachment_image_src( $galdb_query[0]->thumbnail , 'full' );
		
			$return_val['title'] = $galdb_query[0]->title;
			$return_val['description'] = $galdb_query[0]->description;
			$return_val['setting'] = $galdb_query[0]->setting;
			$return_val['thumbnail_id'] = $galdb_query[0]->thumbnail;
			$return_val['thumbnail_url'] = $thumbnail_src[0];
		}
		
		die( json_encode($return_val) );
	}
	
	// save the file title to database
	add_action('wp_ajax_set_galdb_file_title','set_galdb_file_title');
	function set_galdb_file_title(){
		global $wpdb;
		
		$return_val = array('success'=>'0');
		$table_name =  $wpdb->prefix . 'galdb';

		$_POST['name'] = str_replace('/', '\\', $_POST['name']);
		$_POST['name'] = str_replace('\\\\', '\\', $_POST['name']);
		$_POST['name'] = mysql_real_escape_string( $_POST['name'] );
		
		$sql = 'INSERT INTO ' . $table_name . ' (name, title, type, description, setting, thumbnail)';
		$sql = $sql . ' VALUES (\'' . $_POST['name'] . '\', \'' . $_POST['title'] . '\', \'' . 
			$_POST['type'] . '\', \'' . $_POST['description'] . '\', \'' . $_POST['setting'] . '\', \'' . $_POST['thumbnail'] . '\')';
		$sql = $sql . ' ON DUPLICATE KEY UPDATE ';
		$sql = $sql . ' title = \'' . $_POST['title'] . '\'';
		$sql = $sql . ' ,type = \'' . $_POST['type'] . '\'';
		$sql = $sql . ' ,description = \'' . $_POST['description'] . '\'';
		$sql = $sql . ' ,setting = \'' . $_POST['setting'] . '\'';
		$sql = $sql . ' ,thumbnail = \'' . $_POST['thumbnail'] . '\';';
		
		$galdb_query = $wpdb->query( $sql );
		if( $galdb_query ){
			$return_val = array('success'=>'1');
		}
		
		die( json_encode($return_val) );
	}	
	
	// get the directory list array
	$gal_file_type = array(
		'JPG'=>'image', 'jpg'=>'image', 'jpeg'=>'image', 'gif'=>'image', 'png'=>'image', 'bmp'=>'image', 'mp3'=>'audio'
	);
	function get_directory_list( $directory, $type = '' ){
		global $gal_file_type;

		$directory = str_replace('\\', '/', $directory);
		
		$results = array();
		$files = scandir( $directory ); 
		
		foreach( $files as $file ){
		
			if( $file != ".DS_Store" && $file != "." && $file != ".." && $file != "A-gdl-tmb" && $file != "A-gdl-gallery-resize"){

				if( strrpos($file, '.') > 0 ) { $extension = $gal_file_type[ end( explode('.', $file) ) ]; }
				else{ $extension = 'dir'; }
				
				if( empty($type) ){
					$results[] = array( 'name' => $file, 'type' => $extension );
				}else if( $type == $extension ){
					$results[] = $file;
				}
				
			}
		}
		
		return $results;
	}	
	
	/* function get_directory_list_old( $directory, $type = '' ){
		global $gal_file_type;

		$directory = str_replace('\\', '/', $directory);
		
		$results = array();
		$handler = opendir( $directory );
		$files = array(); 
		
		while( $file = readdir( $handler ) ){
		
			if( $file != ".DS_Store" && $file != "." && $file != ".." && $file != "A-gdl-tmb" && $file != "A-gdl-gallery-resize"){

				if( strrpos($file, '.') > 0 ) { $extension = $gal_file_type[ end( explode('.', $file) ) ]; }
				else{ $extension = 'dir'; }
				
				if( empty($type) ){
					$results[] = array( 'name' => $file, 'type' => $extension );
				}else if( $type == $extension ){
					$results[] = $file;
				}
				
			}
		}
		
		closedir( $handler );
		
		return $results;
	} */
	
	// query the data from database 
	function galdb_query( $query_string ){
		global $wpdb;
		
		$table_name =  $wpdb->prefix . 'galdb';
		
		$sql = 'SELECT * FROM ' . $table_name . ' WHERE ' . $query_string . ';';
		
		$galdb_query = $wpdb->get_results( $sql );
		
		return $galdb_query;	
	}
	
	// get the gallery using ajax call
	add_action('wp_ajax_nopriv_gdl_get_gallery_data','gdl_get_gallery_data');
	add_action('wp_ajax_gdl_get_gallery_data','gdl_get_gallery_data');
	function gdl_get_gallery_data(){

		$img_width = get_option(THEME_SHORT_NAME.'_gallery_page_image_width', '220');
		$img_height = get_option(THEME_SHORT_NAME.'_gallery_page_image_height', '165');
		
		// init the variable
		$gal_root = $_POST['root'];
		$gal_dir = $_POST['dir'];
		
		$audio_counter = 0;
		
		if( empty( $gal_root ) ){ $gal_root = ""; }
		else{ $gal_root = str_replace ( '\\\\', '\\', $gal_root );}
		if( empty( $gal_dir ) ){ $gal_dir = ""; }
		else{ $gal_dir = str_replace ( '\\\\', '\\', $gal_dir ); }
		
		$back_dir_index = strripos($gal_dir, '\\');
		if( $back_dir_index > 0 ){
			$back_dir = substr( $gal_dir, 0, $back_dir_index );
		}else{
			$back_dir = '';
		}

		$header_query_string = 'name = \'' . mysql_real_escape_string(GAL_PREFIX . $gal_root . '\\' . $gal_dir) . '\'';
		
		$header_query = galdb_query($header_query_string);
		if( !empty( $header_query ) && !empty( $header_query[0]->title ) ){
			$header_title = $header_query[0]->title;
			$header_description = $header_query[0]->description;
		}else{
			$header_title = '';
			$header_description = '';
		}
		
		// print the header
		echo '<div class="gdl-gal-container-header-wrapper">';
		
		echo '<div class="gdl-gal-container-header-gimmick"></div>';
		echo '<h1 class="gdl-gal-container-title gdl-title">' . $header_title . '</h1>';
		echo '<div class="gdl-gal-container-caption">' . $header_description . '</div>';
		echo '<div class="clear"></div>';
		
		echo '<div class="gallery-nav-wrapper">';
		echo '<div class="back-gallery-wrapper" data-ajax="gdl_get_gallery_data" data-root="' . $gal_root . '" data-dir="' . $back_dir . '" ><span class="back-gallery-icon"></span>Back</div>';
		echo '<div class="close-gallery-wrapper" data-ajax="gdl_get_gallery_data" data-root="" data-dir="" ><span class="close-gallery-icon"></span>Close</div>';
		echo '</div>';
		
		echo '</div>';

		
		$directory_list = get_directory_list(GAL_DEST . $gal_root . '/' . $gal_dir);
		
		echo '<div id="gdl-gallery-image-holder">';
		foreach( $directory_list as $file ){
			if( $file['type'] == 'image' ){
				echo '<div class="gdl-gallery-image" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;">';
				
				$source = GAL_DEST . $gal_root . '/' . $gal_dir . '/' . $file['name'];
				$thumbnail = gdl_resize_gallery_image( $source, GAL_TMB_DEST, $img_width, $img_height);

				$condition = 'name = \'' . mysql_real_escape_string(GAL_PREFIX . $gal_root . '\\' . $gal_dir . '\\' . $file['name']) . '\'';
				$query_result = galdb_query( $condition );
				
				$image_title = '';
				$icon_type = 'image';
				if( empty( $query_result ) ){ 
					$thumbnail_full = GAL_REAL_DEST . $gal_root . '/' . $gal_dir . '/' . $file['name'];
					$thumbnail_full = str_replace('\\', '/', $thumbnail_full);
				}else{
					$image_title = $query_result[0]->title;
					if( $query_result[0]->setting == "Current Thumbnail" ){
						$thumbnail_full = GAL_REAL_DEST . $gal_root . '/' . $gal_dir . '/' . $file['name'];
						$thumbnail_full = str_replace('\\', '/', $thumbnail_full);
					}else{
						$thumbnail_full = $query_result[0]->description;
						$icon_type = 'video';
					}
				}
				
				if( !empty( $thumbnail ) ){
					echo '<img src="' . $thumbnail . '" alt="" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;" />';
				}
				echo '<a data-rel="prettyPhoto[gdl-gallery]" title="' . $image_title . '" href="' . $thumbnail_full . '">';
				echo '<span class="gdl-gallery-image-overlay">';
				echo '<span class="gdl-gallery-image-icon ' . $icon_type . '"></span>';
				echo '</span>';		
				echo '</a>';
				echo '</div>';
			}else if( $file['type'] == 'audio'){
				$source = GAL_REAL_DEST . $gal_root . '/' . $gal_dir . '/' . $file['name'];
				$audio_title = $file['name'];
				
				$condition = 'name = \'' . mysql_real_escape_string(GAL_PREFIX . $gal_root . '\\' . $gal_dir . '\\' . $file['name']) . '\'';
				$query_result = galdb_query( $condition );	
				if( !empty( $query_result ) ){
					if( !empty( $query_result[0]->title ) ){
						$audio_title = $query_result[0]->title;
					}
					
					$thumbnail_id = $query_result[0]->thumbnail;
					$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $img_width . 'x' . $img_height );
					$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
				}else{
					$thumbnail = '';
				}

				$audio_title = empty( $audio_title )? $file['name'] : $audio_title;
				
				echo '<div class="gdl-gallery-image audio" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;">';
				
				if(!empty( $thumbnail) ){			
					echo '<img src="' . $thumbnail[0] . '" alt="' . $alt_text . '" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;" />';
				}
				
				echo '<div class="gdl-audio-player" data-audio="' . $source . '" >';
				
				?>
				<div class="jp-jplayer"></div>
				<div class="jp-title"><?php echo $audio_title ?></div>
				<div class="jp-audio-wrapper" id="jp-audio-container<?php echo $audio_counter; ?>">
					<div class="jp-time-holder">
						<span class="jp-current-time"></span> / <span class="jp-duration"></span>
					</div>							
					<div class="jp-audio">
						<div class="jp-type-single">
							<div class="jp-gui jp-interface">
								<div class="jp-controls">
								  <div href="javascript:;" class="jp-play" tabindex="1"></div>
								  <div href="javascript:;" class="jp-pause" tabindex="1"></div>
								</div>
								<div class="jp-progress">
								  <div class="jp-seek-bar"><div class="jp-play-bar"></div></div>
								</div>
							</div>
							<div class="jp-no-solution">
								<span>Update Required</span>
								To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
							</div>
							<div class="clear"></div>
						</div>
					</div>				
				</div>	<!-- jp audio wrapper -->
				<?php 
				
				echo '</div>'; 
				echo '</div>';
				
				$audio_counter++;
				
			}else if( $file['type'] == 'dir'){

				$condition = 'name = \'' . mysql_real_escape_string(GAL_PREFIX . $gal_root . '\\' . $gal_dir . '\\' . $file['name']) . '\'';
				$query_result = galdb_query( $condition );	
				if( !empty( $query_result ) ){
					$thumbnail_id = $query_result[0]->thumbnail;
				}else{
					$thumbnail_id = '';
				}
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $img_width . 'x' . $img_height );
				$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
				
				$new_gal_dir = $gal_dir . '\\' . $file['name'];
				
				echo '<div data-ajax="gdl_get_gallery_data" class="gdl-gallery-image" data-root="' . $gal_root . '" data-dir="' . $new_gal_dir  . '" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;">';
				if( !empty( $thumbnail ) ){
					echo '<img src="' . $thumbnail[0] . '" alt="' . $alt_text . '" style="width: ' . $img_width . 'px; height: ' . $img_height . 'px;" />';
				}
				echo '<span class="gdl-gallery-folder-overlay">';	
				echo '<span class="gdl-gallery-folder-icon"></span></span>';	
				echo '</div>';
			}
		}	
		echo '</div>';
		echo '<div class="clear"></div>';
		
		die('');
		
	}	
	
?>