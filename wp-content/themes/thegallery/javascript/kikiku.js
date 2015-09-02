(function($) {
	var $liveTip = $('<div id="livetip"></div>').hide().appendTo('body');
	var tipTitle = '';
	var timer = null;
	//tooltip
	$.fn.Tooltip = function(){
		$(this).bind('mouseover mouseout mousemove', function(event) {
			$t = $(event.target);
			if (event.type == 'mouseover' || event.type == 'mousemove') {
				  $liveTip.css({
					  top: event.pageY + 12,
					  left: event.pageX + 12
				  });
			  };
			  if (event.type == 'mouseover') {
				  if($t.is('img')){
					  if(typeof($t.attr('alt')) == 'undefined' && typeof($t.attr('title')) == 'undefined'){
						  $liveTip.hide();
						  return false;
					  }
					  var _tipTitle = typeof($t.attr('alt')) == 'undefined'?$t.attr('title'):$t.attr('alt');
					  if (_tipTitle) {
						  tipTitle = _tipTitle;
						  $t.attr('alt','').attr('title','');
					  }
				  } else if ($t.is('a')) {
					  var _tipTitle = typeof($t.attr('title')) == 'undefined'?$t.text():$t.attr('title');
					  if (_tipTitle) {
						  tipTitle = _tipTitle;
						  $t.attr('title','');
					  }
				  }
				  if (tipTitle) {
					  //执行前先干掉
					  clearTimeout(timer);
					  timer = setTimeout(function(){
						  $liveTip.html('<div>' + tipTitle + '</div>')
						  .fadeIn("fast");
					  },500);
				  }
			  };
			  if (event.type == 'mouseout') {
				  //移除鼠标肯定干掉
				  clearTimeout(timer);
				  $liveTip.fadeOut("fast");
				  if (tipTitle && $t.is('img')) {
					  $t.attr('alt',tipTitle).attr('title',tipTitle);
				  }
				  if(tipTitle && $t.is('a')){
					  $t.attr('title',tipTitle);
				  }
			  };
		});
	}
	$('#livetip').css({
		"background-color": "#121212",
		"color": "#aaa",
		"padding": "8px",
		"position": "absolute",
		"z-index": "9999",
		"max-width": "300px",
		"-webkit-box-shadow": "0 0 5px #aaa",
		"box-shadow": "0 0 5px #aaa",
		"border-width": "2px",
		"border-radius": "5px",
		"-moz-border-radius": "5px"
	});
	$('a,img').Tooltip();
})(jQuery);