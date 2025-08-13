<?php
/**
Template Name: Calculator
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header('calculator'); ?>


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
		
        			<?php /*?><?php if(is_user_logged_in()){ ?><?php */?>
					<?php get_template_part( 'agd-price-calculator/agd-price', 'calculator' ); ?>
                    <?php /*?><?php }else{ ?>
                    	<h4>Precious Metals Calculator is Currently Offline for Maintenance</h4>
                    <?php } ?><?php */?>
		
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