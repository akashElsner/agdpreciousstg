<?php remove_filter('the_excerpt', 'wpautop'); ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<i class="format-icon fa fa-quote-left"></i>

	<!-- BEGIN .article -->
	<div class="article">
		
		<p class="quote-text"><?php the_excerpt(); ?></p>
		
		<?php if ( get_post_meta($post->ID, 'q_author', true) ) { ?>
			<p class="quote-author"><?php echo get_post_meta($post->ID, 'q_author', true); ?></p>
		<?php } ?>
	
	<!-- END .article -->
	</div>
	
<!-- END .post class -->
</div>
