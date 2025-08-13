<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>
<?php $has_content = get_the_content(); ?>

<li <?php post_class(); ?> id="post-<?php the_ID(); ?>" data-type="background" data-speed="10" <?php if ( has_post_thumbnail()) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } ?>>

	<!-- BEGIN .row -->
	<div class="row">
			
		<!-- BEGIN .information -->
		<div class="information vertical-center">
			
			<!-- BEGIN .content -->
			<div class="content text-white clearfix">
				
				<!-- BEGIN .sixteen columns -->
				<div class="sixteen columns">
					
					<!-- BEGIN .text-holder -->
					<div class="text-holder text-center">
						
						<h2 class="headline"><a href="<?php if ( get_post_meta($post->ID, 'l_url', true) ) { echo get_post_meta($post->ID, 'l_url', true); } else { the_permalink(); } ?>" rel="bookmark" target="_blank"><?php the_title(); ?> </a></h2>
						
						<?php if ( !empty( $post->post_excerpt ) ) { ?>

							<div class="excerpt"><?php the_excerpt(); ?></div>
							
							<?php if ( get_post_meta($post->ID, 'l_url', true) ) { ?> 
								<a class="more-link" href="<?php echo get_post_meta($post->ID, 'l_url', true); ?>" target="_blank"><?php _e("Learn More", 'organicthemes'); ?></a>
							<?php } ?>

						<?php } ?>
					
					<!-- END .text-holder -->
					</div>
				
				<!-- END .sixteen columns -->
				</div>
			
			<!-- END .content -->
			</div>
		
		<!-- END .information -->
		</div>
	
	<!-- END .row -->
	</div>

</li>