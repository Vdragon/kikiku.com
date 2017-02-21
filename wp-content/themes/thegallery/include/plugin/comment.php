<?php

	/*	
	*	Goodlayers Comment File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file return the comment list to selected the post_type
	*	---------------------------------------------------------------------
	*/
	 
	function get_comment_list( $comment, $args, $depth ) {
	
		$GLOBALS['comment'] = $comment;
		
		switch ( $comment->comment_type ) :
			case 'pingback'  :
			case 'trackback' :
			?>
				<li class="post pingback">	
					<p>
						<?php _e( 'Pingback:', 'gdl_back_office'); ?>
						<?php comment_author_link(); ?>
						<?php edit_comment_link( __('(Edit)', 'gdl_back_office'), ' ' ); ?>
					</p>
			<?php
				break;
				
			default :
			?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<div class="comment-body gdl-divider">
						<div class="comment-avartar">
							<?php echo get_avatar( $comment, 48 ); ?>
						</div>
						<div class="comment-context">
							<div class="comment-head">
								<span class="comment-author"><?php echo get_comment_author_link(); ?></span>
								<span class="comment-date post-info-color"><?php echo get_comment_date();?></span>
								<span class="comment-date post-info-color"> at <?php echo get_comment_time();?></span>
								<span class="comment-reply">
									<?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
								</span> 
							</div>
							<div class="comment-content"><?php echo comment_text(); ?></div>
						</div>
						<div class="clear"></div>
					</div><!-- end of comment body -->   
			<?php
				break;
		endswitch;
		
	}
?>