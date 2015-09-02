<script type="text/javascript" language="javascript">
/* <![CDATA[ */
    function grin(tag) {
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
    		myField = document.getElementById('comment');
    	} else {
    		return false;
    	}
    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	}
    	else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos)
    					  + tag
    					  + myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	}
    	else {
    		myField.value += tag;
    		myField.focus();
    	}
    }
/* ]]> */
</script>
<?php $simile_url = '/smilies/smilies1/';?>
<a href="javascript:grin(':?:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_question.gif" alt="" /></a>
<a href="javascript:grin(':razz:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_razz.gif" alt="" /></a>
<a href="javascript:grin(':sad:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_sad.gif" alt="" /></a>
<a href="javascript:grin(':evil:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_evil.gif" alt="" /></a>
<a href="javascript:grin(':!:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_exclaim.gif" alt="" /></a>
<a href="javascript:grin(':smile:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_smile.gif" alt="" /></a>
<a href="javascript:grin(':oops:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_redface.gif" alt="" /></a>
<a href="javascript:grin(':grin:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_biggrin.gif" alt="" /></a>
<a href="javascript:grin(':eek:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_surprised.gif" alt="" /></a>
<a href="javascript:grin(':shock:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_eek.gif" alt="" /></a>
<a href="javascript:grin(':???:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_confused.gif" alt="" /></a>
<a href="javascript:grin(':cool:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_cool.gif" alt="" /></a>
<a href="javascript:grin(':lol:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_lol.gif" alt="" /></a>
<a href="javascript:grin(':mad:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_mad.gif" alt="" /></a>
<a href="javascript:grin(':twisted:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_twisted.gif" alt="" /></a>
<a href="javascript:grin(':roll:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_rolleyes.gif" alt="" /></a>
<a href="javascript:grin(':wink:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_wink.gif" alt="" /></a>
<a href="javascript:grin(':idea:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_idea.gif" alt="" /></a>
<a href="javascript:grin(':arrow:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_arrow.gif" alt="" /></a>
<a href="javascript:grin(':neutral:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_neutral.gif" alt="" /></a>
<a href="javascript:grin(':cry:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_cry.gif" alt="" /></a>
<a href="javascript:grin(':mrgreen:')"><img src="<?php echo get_bloginfo('template_directory') . $simile_url; ?>icon_mrgreen.gif" alt="" /></a>
<br />