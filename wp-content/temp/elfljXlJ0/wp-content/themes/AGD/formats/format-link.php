<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<i class="format-icon fa fa-link"></i>
	
	<!-- BEGIN .article -->
	<div class="article">
		
		<h2 class="headline small text-center"><a href="<?php if ( get_post_meta($post->ID, 'l_url', true) ) { echo get_post_meta($post->ID, 'l_url', true); } else { the_permalink(); } ?>" rel="bookmark" target="_blank"><?php the_title(); ?> </a></h2>
		
		<?php if ( !empty( $post->post_excerpt ) ) { ?>
			<?php the_excerpt(); ?>
			<?php if ( get_post_meta($post->ID, 'l_url', true) ) { ?> 
				<a class="more-link" href="<?php echo get_post_meta($post->ID, 'l_url', true); ?>" target="_blank"><?php _e("Visit Link", 'organicthemes'); ?> &nbsp;<i class="fa fa-external-link"></i></a>
			<?php } ?> 
		<?php } ?>
		
	<!-- END .article -->
	</div>
	

<!-- END .post class -->
</div>