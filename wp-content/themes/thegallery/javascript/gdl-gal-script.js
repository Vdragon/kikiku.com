jQuery(document).ready(function(){

	// For Gallery Page 
	var bottom_slide_width;
	var bottom_slide_height = 0;
	var gal_container = jQuery('#gdl-gal-container');
	var gal_container_content = gal_container.children('#gdl-gal-container-content');
	var gal_container_loading = gal_container.children('#gdl-gal-container-loading-overlay');
	var window_width = jQuery(window).width();
	var bottom_slide = jQuery('#gdl-bottom-image-slide');
	var bottom_slide_inner = bottom_slide.find('#gdl-bottom-image-slide-inner');
	
	var bg_caption_wrapper = jQuery('#supersized-caption-wrapper');
	
	// initial the bottom slide value
	function init_bottom_slide(){
		bottom_slide.each(function(){
			var bottom_slide_child = jQuery(this).find('.bottom-image-slide').filter(':first');
			var margin = parseInt( bottom_slide_child.css('margin-right'), 10 );
			var padding = parseInt( bottom_slide_child.children('img, div.flexslider-gallery').css('padding-right'), 10 );
			var children_width = parseInt( bottom_slide_child.children('img, div.flexslider-gallery').css('width'), 10 ) + (margin * 2) + (padding * 2);
			bottom_slide_width = bottom_slide_inner.find('.bottom-image-slide').length * children_width;
		});
	}
	
	// center the bottom slide inner
	function center_bottom_slide(){
		bottom_slide_inner.each(function(){
			bottom_slide_inner.css( 'left', (window_width - bottom_slide_width) / 2 ); 
		});
	}
	
	// animate the bottom slide to left / right
	function animate_bottom_slide(e, pos_len){
		var left_pos = 0;
		if( window_width > bottom_slide_width ){
			left_pos = (window_width - bottom_slide_width) / 2; 
		}else if( e == null){
			left_pos = ( window_width - bottom_slide_width ) * pos_len;
		}else{
			left_pos = ( window_width - bottom_slide_width ) * (  get_mouse_smooth(e.pageX) / window_width );
		}
		bottom_slide_inner.animate({ left: left_pos }, {queue:false, duration:400, easing:'easeOutExpo'});
	}
	
	// smooth the left and right corner
	function get_mouse_smooth( mouse_pos ){
		if( mouse_pos < 200 ){
			return mouse_pos / 10;
		}else if( window_width - mouse_pos < 200 ){
			return window_width - (window_width - mouse_pos) / 10;
		}
		return mouse_pos;
	}
	
	// bind the audio script
	function bind_audio_script( container ){
		container.find('.gdl-audio-player').each(function(){
			var audio_path = jQuery(this).attr('data-audio');
			var audio_player = '#' + jQuery(this).children('.jp-audio-wrapper').attr('id');
			jQuery(this).children('.jp-jplayer').jPlayer({
				ready: function(){
					jQuery(this).jPlayer('setMedia', { mp3: audio_path });
				},
				swfPath: URL.goodlayers + "/javascript/jplayer",
				supplied: 'm4a, oga, mp3',
				cssSelectorAncestor: audio_player,
				wmode: "window",
				supplied:"mp3"
			});
		
		});
	}
	
	// bind the gallery image hover script
	function bind_gallery_ajax_script( container ){
		//bind the pretty photo
		container.bindPrettyPhoto();
	
		//bind the hover effect
		container.find('.gdl-gallery-image-overlay').hover(function(){
			jQuery(this).animate({ opacity: 1 }, 300);
			jQuery(this).children().animate({ opacity: 1 }, 300); // for IE8
		}, function(){
			jQuery(this).animate({ opacity: 0 }, 300);
			jQuery(this).children().animate({ opacity: 0 }, 300); // for IE8
		});
		container.find('.gdl-gallery-folder-overlay').hover(function(){
			jQuery(this).animate({ opacity: 0.7 }, 300);
			jQuery(this).children().animate({ opacity: 0.7 }, 300); // for IE8
		}, function(){
			jQuery(this).animate({ opacity: 1 }, 300);
			jQuery(this).children().animate({ opacity: 1 }, 300); // for IE8
		});		
		container.find('.gdl-gallery-image.audio').hover(function(){
			jQuery(this).find('.jp-audio').animate({ opacity: 1 }, 200);
		}, function(){
			jQuery(this).find('.jp-audio').animate({ opacity: 0.7 }, 200);
		});		
		
		container.find('div[data-ajax=gdl_get_gallery_data]').click(function(){
			ajax_folder_call( jQuery(this) );
		});
		
		bind_audio_script( container );
		
		container.bindPreloader();
	}
	
	// send the ajax call to get the gallery data
	function ajax_folder_call( current_element ){
		var sending_val = new Object();
		sending_val.action = current_element.attr('data-ajax');
		sending_val.root = current_element.attr('data-root');
		sending_val.dir = current_element.attr('data-dir');
		
		if( sending_val.dir == sending_val.root || !sending_val.dir ){
			bottom_slide.slideUp( 400, 'easeOutExpo', function(){	
				// remove class and get back to initialize state
				bottom_slide.children('#gdl-bottom-image-slide-inner-wrapper').show();
				bottom_slide.children('#bottom-image-slide-control').addClass('active').css('opacity','1');
				bottom_slide_inner.children('.bottom-image-slide').removeClass('active').find('img').css('opacity', '1');
				
				jQuery(this).removeClass('bottom-image-slide2-wrapper').addClass('bottom-image-slide1-wrapper');
				jQuery(this).slideDown( 400, 'easeOutExpo' );
				init_bottom_slide();
				animate_bottom_slide( null, 0.5 );
			});
			
			gal_container.fadeOut();	
			bg_caption_wrapper.fadeIn();
		}else{	
			gal_container_content.animate({opacity: 0.4}, 200);
			gal_container_loading.fadeIn();
			jQuery.post( URL.ajaxurl, sending_val , function(data){
				gal_container_content.html(data);
				gal_container_content.animate({opacity: 1}, function(){
					jQuery(this).children('#gdl-gallery-image-holder').bindGalFilterable();
					bind_gallery_ajax_script( jQuery(this) );
				});
				
				// replace the cufon to the gallery title
				if( typeof(Cufon) != 'undefined' ){
					var gdl_font_fam = gal_container_content.find('.gdl-title').filter(':first').css('font-family');
					gdl_font_fam = gdl_font_fam.replace(/'/g,"");
					if( Cufon.hasFont( gdl_font_fam ) ){
						Cufon.replace(gal_container_content.find('.gdl-gal-container-title.gdl-title'), {fontFamily: gdl_font_fam});		
					}				
				}
				gal_container_loading.fadeOut();
			});	
		}
	}
	
	init_bottom_slide();
	center_bottom_slide();
	
	// init the variable when window is resize;
	jQuery(window).resize(function(){
		window_width = jQuery(window).width();
		animate_bottom_slide( null, 0.5 );
	});	
	
	// change state and call the ajax to get gallery images
	bottom_slide_inner.find('.bottom-image-slide').click(function(){
		var curr_item = jQuery(this);
		
		if( curr_item.hasClass('active') ) return false;
	
		if( bottom_slide.hasClass('bottom-image-slide1-wrapper') ){
		
			var pos = jQuery(this).index();
			var len = bottom_slide_inner.find('.bottom-image-slide').length - 1;
			
			bottom_slide.slideUp( 400, 'easeOutExpo', function(){
				jQuery(this).removeClass('bottom-image-slide1-wrapper').addClass('bottom-image-slide2-wrapper');
				jQuery(this).slideDown( 400, 'easeOutExpo', function(){
					bottom_slide_height = jQuery(this).height();
					gal_container_content.css('margin-bottom', bottom_slide_height + 'px');
				});
				init_bottom_slide();
				animate_bottom_slide( null, pos/len );
				
			});
			
			gal_container.fadeIn();
			bg_caption_wrapper.fadeOut();
			
		}
		
		// set the active class when choosing the slide
		jQuery(this).find('.bottom-image-folder').fadeOut();
		jQuery(this).find('img').animate({opacity: 1}, function(){
			curr_item.addClass('active');
		});
		jQuery(this).siblings('.active').each(function(){
			jQuery(this).removeClass('active');
			jQuery(this).find('img').animate({opacity: 0.4});
		});
		
		ajax_folder_call( jQuery(this) );
	});
	
	
	
	// hover state of bottom image slide
	bottom_slide_inner.find('.bottom-image-slide').hover(function(){
		if( bottom_slide.hasClass('bottom-image-slide2-wrapper') && !jQuery(this).hasClass('active') ){
			jQuery(this).find('.bottom-image-folder').fadeIn(200);
		}
	}, function(){
		if( bottom_slide.hasClass('bottom-image-slide2-wrapper') ){
			jQuery(this).find('.bottom-image-folder').fadeOut(200);
		}
	});
	
	// check if the bottom slide is in hover state
	var is_over_slide = false;
	bottom_slide_inner.hover(function(){	
		is_over_slide = true;
	}, function(){
		is_over_slide = false;
	});
	
	// check if mouse move and bottom slide is in hover state
	jQuery(document).mousemove(function(e){
		if( is_over_slide ){
			animate_bottom_slide(e);
		}
	});	
	
	// hide the bottom slide button
	bottom_slide.children('#bottom-image-slide-control').click(function(){
		/*
		
		if( bottom_slide.hasClass('bottom-image-slide1-wrapper') ){
			if( jQuery(this).hasClass('active') ){
				jQuery(this).removeClass('active');
				var button_control = jQuery(this);
				bottom_slide.children('#gdl-bottom-image-slide-inner-wrapper').animate({ opacity: 0 }, 120, function(){
					button_control.animate({ opacity: 0.5 }, 300);
				});
			}else{
				jQuery(this).addClass('active');
				var button_control = jQuery(this);
				bottom_slide.children('#gdl-bottom-image-slide-inner-wrapper').animate({ opacity: 1 }, 120, function(){
					button_control.animate({ opacity: 1 }, 300);
				});
			}
		}else{} 
		
		*/
		if( jQuery(this).hasClass('active') ){
			jQuery(this).removeClass('active');
			var button_control = jQuery(this);
			gal_container_content.animate({'margin-bottom': 0}, { 'duration':300, easing: "easeOutExpo" });
			bottom_slide.children('#gdl-bottom-image-slide-inner-wrapper').slideUp({easing: "easeOutExpo"}, function(){
				button_control.animate({ opacity: 0.5 }, 300);
			});
		}else{
			jQuery(this).addClass('active');
			var button_control = jQuery(this);
			gal_container_content.animate({'margin-bottom': bottom_slide_height}, { 'duration':300, easing: "easeOutExpo" });
			bottom_slide.children('#gdl-bottom-image-slide-inner-wrapper').slideDown({easing: "easeOutExpo"}, function(){
				button_control.animate({ opacity: 1 }, 300);
			});
		}		
	
	});
	bottom_slide.children('#bottom-image-slide-control').hover(function(){
		if( !jQuery(this).hasClass('active') ){
			jQuery(this).animate({ opacity: 1 }, 300);
		}else{
			jQuery(this).animate({ opacity: 0.5 }, 300);
		}
	},function(){
		if( !jQuery(this).hasClass('active') ){
			jQuery(this).animate({ opacity: 0.5 }, 300);
		}else{
			jQuery(this).animate({ opacity: 1 }, 300);
		}
	});	
	
});