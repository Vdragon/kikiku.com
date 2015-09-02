jQuery(document).ready(function(){
	<?php 
		global $all_font;

		$used_font = substr(get_option(THEME_SHORT_NAME.'_header_font'), 2);
		
		if($used_font != 'default -'){
			if($all_font[$used_font]['type'] == 'Cufon'){
				echo "Cufon.replace(jQuery('h1, h2, h3, h4, h5, h6, .gdl-title').not('.nivo-caption .gdl-title'), {fontFamily: '" . $used_font . "' , hover: true});";
			}
		}
	?>
});