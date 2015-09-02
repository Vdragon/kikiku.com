jQuery(document).ready(function(){

	// Menu Navigation
	jQuery('#main-superfish-wrapper ul.sf-menu').supersubs({
		minWidth: 14.5,
		maxWidth: 27,
		extraWidth: 1
	}).superfish({
		delay: 100,
		speed: 'fast',
		animation: {opacity:'show',height:'show'}
	});
	
	// Accordion
	jQuery("ul.gdl-accordion li").each(function(){
		if(jQuery(this).index() > 0){
			jQuery(this).children(".accordion-content").css('display','none');
		}else{
			jQuery(this).find(".accordion-head-image").addClass('active');
		}
		
		jQuery(this).children(".accordion-head").bind("click", function(){
			jQuery(this).children().addClass(function(){
				if(jQuery(this).hasClass("active")) return "";
				return "active";
			});
			jQuery(this).siblings(".accordion-content").slideDown();
			jQuery(this).parent().siblings("li").children(".accordion-content").slideUp();
			jQuery(this).parent().siblings("li").find(".active").removeClass("active");
		});
	});
	
	// Toggle Box
	jQuery("ul.gdl-toggle-box li").each(function(){
		jQuery(this).children(".toggle-box-content").not(".active").css('display','none');
		
		jQuery(this).children(".toggle-box-head").bind("click", function(){
			jQuery(this).children().addClass(function(){
				if(jQuery(this).hasClass("active")){
					jQuery(this).removeClass("active");
					return "";
				}
				return "active";
			});
			jQuery(this).siblings(".toggle-box-content").slideToggle();
		});
	});
	
	// Social Hover
	jQuery(".social-icon").hover(function(){
		jQuery(this).animate({ opacity: 1 }, 150);
	}, function(){
		jQuery(this).animate({ opacity: 0.55 }, 150);
	});
	
	// Scroll Top
	jQuery('div.scroll-top').click(function() {
		  jQuery('html, body').animate({ scrollTop:0 }, { duration: 600, easing: "easeOutExpo"});
		  return false;
	});
	
	// Blog Hover
	jQuery(".blog-thumbnail-image img").hover(function(){
		jQuery(this).animate({ opacity: 0.55 }, 150);
	}, function(){
		jQuery(this).animate({ opacity: 1 }, 150);
	});
	
	// Gallery Hover
	jQuery(".gallery-thumbnail-image img").hover(function(){
		jQuery(this).animate({ opacity: 0.55 }, 150);
	}, function(){
		jQuery(this).animate({ opacity: 1 }, 150);
	});
	
	// Port Hover
	jQuery("#portfolio-item-holder .portfolio-thumbnail-image-hover").hover(function(){
		jQuery(this).animate({ opacity: 1 }, 300);
	}, function(){
		jQuery(this).animate({ opacity: 0 }, 300);
	});
	
});

jQuery(window).load(function(){

	// Set Portfolio Max Height
	var port_item_holder = jQuery('div#portfolio-item-holder');
	port_item_holder.equalHeights();
	jQuery(window).resize(function(){
		port_item_holder.children().css('min-height','0');
		port_item_holder.equalHeights();
	});

});


/* Tabs Activiation
================================================== */
jQuery(document).ready(function() {
	var tabs = jQuery('ul.tabs');
	tabs.each(function(i) {
		//Get all tabs
		var tab = jQuery(this).find('> li > a');
		tab.click(function(e) {
			//Get Location of tab's content
			var contentLocation = jQuery(this).attr('href');
			//Let go if not a hashed one
			if(contentLocation.charAt(0)=="#") {
				e.preventDefault();
				//Make Tab Active
				tab.removeClass('active');
				jQuery(this).addClass('active');
				//Show Tab Content & add active class
				jQuery(contentLocation).show().addClass('active').siblings().hide().removeClass('active');
			}
		});
	});
});

/* Equal Height Function
================================================== */
(function($) {
	$.fn.equalHeights = function(px) {
		$(this).each(function(){
			var currentTallest = 0;
			$(this).children().each(function(i){
				if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
			});
			$(this).children().css({'min-height': currentTallest}); 
		});
		return this;
	};
})(jQuery);
