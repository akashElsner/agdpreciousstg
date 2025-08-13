<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>
<?php if (isset($_POST['featurevid'])){ $custom = get_post_custom($post->ID); $featurevid = $custom['featurevid'][0]; } ?>

<li <?php post_class(); ?> id="post-<?php the_ID(); ?>" data-type="background" data-speed="10" <?php if ( has_post_thumbnail()) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } ?>>

	<!-- BEGIN .row -->
	<div class="row">

	<?php if ( ! empty( $post->post_excerpt ) ) { ?>
			
		<!-- BEGIN .information -->
		<div class="information vertical-center">
			
			<?php if ( get_post_meta($post->ID, 'featurevid', true) ) { ?>
			
			<!-- BEGIN .content -->
			<div class="content text-white clearfix">
				
				<!-- BEGIN .ten columns -->
				<div class="ten columns">
	
					<div class="feature-vid radius-full"><?php echo get_post_meta($post->ID, 'featurevid', true); ?></div>
				
				<!-- END .ten columns -->
				</div>
				
				<!-- BEGIN .six columns -->
				<div class="six columns">
					
					<!-- BEGIN .text-holder -->
					<div class="text-holder">
					
						<h2 class="headline small"><?php the_title(); ?></h2>
						
						<div class="excerpt"><?php the_excerpt(); ?></div>
						
						<a class="more-link" href="<?php the_permalink(); ?>"><?php _e("Learn More", 'organicthemes'); ?></a>

					<!-- END .text-holder -->
					</div>
				
				<!-- END .six columns -->
				</div>
			
			<!-- END .content -->
			</div>
			
			<?php } ?>
		
		<!-- END .information -->
		</div>
		
	<?php } else { ?>
	
		<!-- BEGIN .information -->
		<div class="information vertical-center">
			
			<?php if ( get_post_meta($post->ID, 'featurevid', true) ) { ?>
			
			<!-- BEGIN .content -->
			<div class="content">
				
				<!-- BEGIN .sixteen columns -->
				<div class="sixteen columns">
	
					<div class="feature-vid radius-full"><?php echo get_post_meta($post->ID, 'featurevid', true); ?></div>
				
				<!-- END .sixteen columns -->
				</div>
			
			<!-- END .content -->
			</div>
			
			<?php } ?>
		
		<!-- END .information -->
		</div>
		
		<?php } ?>
	
	<!-- END .row -->
	</div>

</li>