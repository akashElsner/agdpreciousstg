<?php
/**
* Template Name: Home Test
* This template is used to display the home page.
*
* @package Purpose
* @since Purpose 1.0
*
*/
get_header(); ?>
<style>
.BannerShopImg, .AuctionImg{
	border: 4px solid #d7bf89;
	text-align:center;
	margin-bottom:20px;
	background-position:right bottom;
	padding:20px 0 40px;
}
.BannerShopImg h2, .AuctionImg h2 {margin-bottom:20px; font-size:30px;}
.BannerShopImg a, .AuctionImg a{background:#d7bf89; border-radius:6px; padding:10px 30px; color:#31373e; text-transform:uppercase; font-weight:900; width:auto;}

@media only screen and (min-width: 990px){
	.BannerShopImg, .AuctionImg{width:48%;}
	.BannerShopImg{float:left;}
	.AuctionImg {float:right;}
}

@media only screen and (min-width: 1366px){
	.BannerShopImg h2, .AuctionImg h2 {font-size:30px;}
}


</style>
<div class="BannerWrap">
	<div class="content">
		<div class="BannerTable">
			<div class="BannerImg" style="background-image:url(<?php echo of_get_option('banner_img'); ?>);">
				<!--div class="BannerShopImg"><? //php echo do_shortcode('[metaslider id="32960"]');?></div -->
            	<div>
                    <div class="BannerShopImg" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/home-page-online-store-banner-bg.jpg);">
                        <h2>Shop Online</h2>
                        <a href="https://shop.agdpreciousmetals.com/">Shop Now</a>
                    </div>
                    <div class="AuctionImg" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/home-page-online-auction-banner-bg.jpg);">
                        <h2>Online Auctions</h2>
                        <a href="https://auctions.kearnsauctions.com.au/" target="_blank">View Now</a>
                    </div>
                </div>
				<?php echo apply_filters('meta_content', of_get_option('banner_content')); ?>
				<?php if ( of_get_option('banner_button') && of_get_option('banner_button_link')){ ?>
				<br />
				<!--a class="arrowButton" href="<? //php echo get_the_permalink(of_get_option('banner_button_link')); ?>">
					<? //php echo of_get_option('banner_button'); ?> <span class="longArrow"></span>
				</a-->
				<p>
				<a class="arrowButton" href="<?php echo get_the_permalink(of_get_option('banner_button_link_2')); ?>">
					<?php echo of_get_option('banner_button_2'); ?> <span class="longArrow"></span>
				</a>
				</p>
				<?php } ?>
			</div>
			<div class="PriceCalc">
				<div id="LiveGoldPriceDiv" class="LiveGoldPriceWarp">
                	<style type="text/css">
                    	.loader {
							border: 8px solid #31373e; /* Light grey */
							border-top: 8px solid #d7bf89; /* Blue */
							border-radius: 50%;
							width: 60px;
							height: 60px;
							animation: spin 2s linear infinite;
							-webkit-animation: spin 2s linear infinite;
							position:absolute;
							top:50%;
							left:50%;
							transform:translate(-50%,-50%);
							-webkit-transform:translate(-50%,-50%);
						}
						
						@keyframes spin {
							0% { transform: rotate(0deg); }
							100% { transform: rotate(360deg); }
						}
						@-webkit-keyframes spin {
							0% { transform: rotate(0deg); }
							100% { transform: rotate(360deg); }
						}
                    </style>
                    <div class="loader"></div>
                </div>
			</div>
		</div>
	</div>
</div>

<?php if ( of_get_option('section_1_display') && of_get_option('section_1_page')) {?>
<?php $query = new WP_Query( array('page_id'=>of_get_option('section_1_page') )); while ( $query->have_posts() ) : $query->the_post(); ?>
<div class="row" id="section1">
    <div class="content">
    	<?php the_content(); ?>
    </div>
</div>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
<?php } ?>


<?php if ( of_get_option('section_2_display') && of_get_option('section_2_page')) {?>
<?php $query = new WP_Query( array('page_id'=>of_get_option('section_2_page') )); while ( $query->have_posts() ) : $query->the_post(); ?>
<div class="row" id="section2">
    <div class="content">
    	<?php the_content(); ?>
    </div>
</div>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
<?php } ?>

<?php if ( of_get_option('section_3_display') && of_get_option('section_3_page')) {?>
<?php $query = new WP_Query( array('page_id'=>of_get_option('section_3_page') )); while ( $query->have_posts() ) : $query->the_post(); ?>
<div class="row" id="section3">
    <div class="content">
    	<?php the_content(); ?>
    </div>
</div>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
<?php } ?>

<?php if ( of_get_option('section_4_display') && of_get_option('section_4_page')) {?>
<?php $query = new WP_Query( array('page_id'=>of_get_option('section_4_page') )); while ( $query->have_posts() ) : $query->the_post(); ?>
<div class="row" id="section4">
    <div class="content">
    	<?php the_content(); ?>
    </div>
</div>
<?php endwhile; ?>
<?php wp_reset_query(); ?>
<?php } ?>


<?php get_footer(); ?>