<?php
/**
Template Name: Blog
*
* This template is used to display a blog. The content is displayed in post formats.
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>


<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">
	
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
		
					<?php get_template_part( 'loop', 'blog' ); ?>
		
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