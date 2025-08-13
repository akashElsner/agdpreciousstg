<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>
<?php $has_content = get_the_content(); ?>

<li <?php post_class(); ?> id="post-<?php the_ID(); ?>" data-type="background" data-speed="10" <?php if ( has_post_thumbnail()) { ?> style="background-image: url(<?php echo $thumb[0]; ?>);" <?php } ?>>

	<!-- BEGIN .row -->
	<div class="row">

	<?php if ( '' != $has_content ) { ?>
			
		<!-- BEGIN .information -->
		<div class="information vertical-center">
			
			<!-- BEGIN .content -->
			<div class="content text-white clearfix">
				
				<!-- BEGIN .sixteen columns -->
				<div class="sixteen columns">
					
					<!-- BEGIN .text-holder -->
					<div class="text-holder text-center">
					
						<h2 class="headline text-hide"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h2>
						
						<div class="excerpt"><?php the_content(); ?></div>
						
						<?php if ( get_post_meta($post->ID, 'q_author', true) ) { ?>
							<p class="quote-author"><?php echo get_post_meta($post->ID, 'q_author', true); ?></p>
						<?php } ?>
					
					<!-- END .text-holder -->
					</div>
				
				<!-- END .sixteen columns -->
				</div>
			
			<!-- END .content -->
			</div>
		
		<!-- END .information -->
		</div>
		
	<?php } ?>
	
	<!-- END .row -->
	</div>

</li>