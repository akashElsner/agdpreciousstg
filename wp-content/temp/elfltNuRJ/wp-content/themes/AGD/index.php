<?php
/**
* This template displays single post content.
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>

<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		
	<!-- BEGIN .row -->
	<div class="row">
	
		<!-- BEGIN .content -->
		<div class="content">

			<div class="five columns">
            	<?php get_template_part( 'content/page', 'banner' ); ?>
			</div>
            
            <div class="eleven columns">
            
				<!-- BEGIN .postarea full -->
				<div class="postarea full clearfix">
		
					<?php get_template_part( 'loop', 'post' ); ?>
		
				<!-- END .postarea full -->
				</div>

			</div>
		
		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .post class -->
</div>

<?php get_footer(); ?>