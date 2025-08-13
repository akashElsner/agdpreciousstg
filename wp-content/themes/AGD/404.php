<?php
/**
* This page template is used to display a 404 error message.
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>
	
<!-- BEGIN .row -->

	<div class="feature-img page-banner" style="background-image: url(<?php echo of_get_option('page_banner'); ?>);">
		<h1 class="headline img-headline"><?php _e("Not Found, Error 404", 'organicthemes'); ?></h1>
		<img src="<?php echo of_get_option('page_banner'); ?>" />
	</div>

<div class="row">

	<!-- BEGIN .content -->
	<div class="content no-thumb">

		<!-- BEGIN .sixteen columns -->
		<div class="sixteen columns">
	
		<div class="postarea full">
			<p><?php _e("The page you are looking for no longer exists.", 'organicthemes'); ?></p>
		</div>
		
		<!-- END .sixteen columns -->
		</div>
		
	
	<!-- END .content -->
	</div>

<!-- END .row -->
</div>

<?php get_footer(); ?>