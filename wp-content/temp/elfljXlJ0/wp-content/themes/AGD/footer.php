<?php

/**

* The footer for our theme.

* This template is used to generate the footer for the theme.

*

* @package Purpose

* @since Purpose 1.0

*

*/

?>
<div class="clear"></div>
<!-- END .container -->
</div>
<?php /*?><div class="insta_table">
    <div class="insta_cell insta_title_col">
        <div class="insta_title_wrap">
            <h2 class="footer_insta_title"><?php echo of_get_option('insta_title'); ?></h2>
            <?php $footerinsta_username = trim(of_get_option('insta_username')); ?>
            <p class="insta_name"></p>
            <p class="footer_insta_username"><h5><a href="<?php echo 'http://instagram.com/'.$footerinsta_username;?>" target="_blank">@<?php echo $footerinsta_username; ?></a></h5></p>
        </div>
    </div>
    <div class="insta_cell insta_feed_col">
        <div class="insta_feed">
            <?php if (is_active_sidebar('instagram')) { dynamic_sidebar('instagram'); }; ?>
        </div>
    </div>
</div> <?php */?>
<!-- BEGIN .footer -->
<div class="footer">

	<!-- BEGIN .row -->
	<div class="row">
		<!-- BEGIN .footer-information -->
		<div class="footer-information">
			<!-- BEGIN .footer-content -->
			<div class="content">

            	<div class="footer_table">
                	<div class="footer_table_cell footer_left">
                    	<div class="footer_widget fw_left">
                        	<?php if(of_get_option('footer_logo') != ''){ ?>
                            <div class="row">
                                <div class="footer_logo"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo of_get_option('footer_logo'); ?>" /></a></div>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <p class="footer_info">Copyright &copy; <?php echo date(__("Y", 'organicthemes')); ?> <?php bloginfo('name'); ?><br />
                               </p>
                            </div>

                        </div>
                    </div>

                    <div class="footer_table_cell footer_mid">
                    	<div class="footer_widget fw_mid">
                        	<?php if (is_active_sidebar('footer-w1')) {?>
                            	<?php dynamic_sidebar('footer-w1'); ?>
                            <?php } ?>


							<?php if(of_get_option('footer_link_1') || of_get_option('footer_link_2')){ ?>
                            <div class="row">
                            <div class="fw_wrap">
                                <ul class="footer_links">
                                	<?php if(of_get_option('footer_link_1')) {?>
                                	<li><a href="<?php echo get_the_permalink(of_get_option('footer_link_1')); ?>"><?php echo get_the_title(of_get_option('footer_link_1')); ?></a></li>
                                    <?php } ?>
                                    <?php if(of_get_option('footer_link_2')) {?>
                                    <li><a href="<?php echo get_the_permalink(of_get_option('footer_link_2')); ?>"><?php echo get_the_title(of_get_option('footer_link_2')); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>

                    <div class="footer_table_cell footer_right">
                    	<div class="footer_widget fw_right">
												<div class="FooterSocial">
													<?php get_template_part( 'content/social', 'links' ); ?>
												</div>
                        	<?php if (is_active_sidebar('footer-w2')) {?>
                            	<?php dynamic_sidebar('footer-w2'); ?>
                            <?php } ?>

                        </div>
                    </div>

                </div>

                </div>
			<!-- END .footer-content -->
			</div>
		<!-- END .footer-information -->
		</div>
	<!-- END .row -->
	</div>
<!-- END .footer -->
</div>
<!-- END #wrap -->
</div>
<?php wp_footer(); ?>
</body>

</html>
