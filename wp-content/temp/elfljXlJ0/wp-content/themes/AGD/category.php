<?php
/**
* This template is used to display category post indexes.
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>
	
<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	
    <?php get_template_part( 'content/category', 'banner' ); ?>

	<!-- BEGIN .row -->
	<div class="row">
	
		<!-- BEGIN .content -->
		<div class="content<?php if ( empty( $header_image ) ) { ?> no-thumb<?php } ?>">
        
		
			<?php if ( is_active_sidebar( 'blog-sidebar' ) ) { ?>
			
			<!-- BEGIN .eleven columns -->
			<div class="eleven columns">
	
				<!-- BEGIN .postarea -->
				<div class="postarea clearfix">
				
					<?php get_template_part( 'loop', 'category' ); ?>
			
				<!-- END .postarea -->
				</div>
			
			<!-- END .eleven columns -->
			</div>
			
			<!-- BEGIN .five columns -->
			<div class="five columns">
			
				<?php get_sidebar('blog'); ?>
				
			<!-- END .five columns -->
			</div>
		
		<?php } else { ?>
	
			<!-- BEGIN .sixteen columns -->
			<div class="sixteen columns">
	
				<!-- BEGIN .postarea full -->
				<div class="postarea full clearfix">
		
					<?php get_template_part( 'loop', 'category' ); ?>
				
				<!-- END .postarea full -->
				</div>
			
			<!-- END .sixteen columns -->
			</div>
	
		<?php } ?>
		
		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .post class -->
</div>

<?php get_footer(); ?>