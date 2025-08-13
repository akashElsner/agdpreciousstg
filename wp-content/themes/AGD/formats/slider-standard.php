<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>
<?php if (isset($_POST['featurevid'])){ $custom = get_post_custom($post->ID); $featurevid = $custom['featurevid'][0]; } ?>

<li <?php post_class(); ?> id="post-<?php the_ID(); ?>" data-type="background" data-speed="10" <?php if ( has_post_thumbnail()) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } if ( ! has_post_thumbnail() && ! get_post_meta($post->ID, 'featurevid', true) ) { ?> style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/default-img.svg);"<?php } ?>>

	<?php if ( ! empty( $post->post_excerpt ) ) { ?>
		
		<!-- BEGIN .information -->
		<div class="information vertical-center">
			
			<!-- BEGIN .content -->
			<div class="content text-white clearfix">
				
				<!-- BEGIN .text-holder -->
				<div class="text-holder text-center">
				
					<h2 class="headline"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
					
					<div class="excerpt"><?php the_excerpt(); ?></div>	
					
					<a class="more-link" href="<?php the_permalink(); ?>"><?php _e("Learn More", 'organicthemes'); ?></a>
				
				<!-- END .text-holder -->
				</div>
			
			<!-- END .content -->
			</div>
		
		<!-- END .information -->
		</div>
		
	<?php } ?>

	<?php if ( get_post_meta($post->ID, 'featurevid', true) ) { ?>
		<div class="feature-vid"><?php echo get_post_meta($post->ID, 'featurevid', true); ?></div>
	<?php } ?>

</li>