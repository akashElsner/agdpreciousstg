<?php
/**
Template Name: Page Builder
This template displays the pagebuilder layouts.
*
*
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>

<?php $thumb = ( '' != get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'purpose-featured-large' ) : false; ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">

	<?php get_template_part( 'content/page', 'banner' ); ?>
	
	<!-- BEGIN .row -->
	<div class="row">
	
		<!-- BEGIN .content -->
		<div class="content<?php if ( empty( $thumb ) ) { ?> no-thumb<?php } ?>">
		
			<!-- BEGIN .sixteen columns -->
			<div class="sixteen columns">
		
				<!-- BEGIN .postarea full -->
				<div class="postarea full clearfix no_padding">
		
					<?php get_template_part( 'loop', 'page' ); ?>
		
				<!-- END .postarea full -->
				</div>
			
			<!-- END .sixteen columns -->
			</div>
		
		<!-- END .content -->
		</div>
	
	<!-- END .row -->
	</div>

<!-- END .post class -->
</div>

<?php get_footer(); ?>