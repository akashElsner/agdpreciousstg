<?php
/**
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
.BannerShopImg a, .AuctionImg a{background:#d7bf89; border-radius:6px; padding:10px 30px; color:#31373e; text-transform:uppercase; font-weight:100; width:auto;}

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
           <div class="LiveGoldPrice">
<?php
$xml = simplexml_load_file("https://agdglobal.com.au/app/xmlparsedcontent.php") or die("Error: Cannot create object");

foreach ($xml->children() as $metals){
	if($metals['Name'] == 'Gold'):
		$gd_aud_bid = $metals->Price[1]['Bid'];
	endif;
}
$spotPrice = $gd_aud_bid;
$spotPrice = number_format($spotPrice*1.00, 2);
$gd_aud_troyounce = $gd_aud_bid*0.92;
$gd_aud_gram = $gd_aud_troyounce/31.1035;

?>	

<h3 class="LivePrice_Title">Live Gold price</h3>
<div class="spotPrice_title">Current Spot Price:</div>
<div class="spotPrice"><?php echo $spotPrice; ?> AUD</div>
<div class="spotPrice_title">We are buying today @:</div>
<ul class="GoldPriceList">
	<li>9k : <?php echo '$'.number_format($gd_aud_gram*0.375, 2); ?> Per Gram</li>
	<li>10k : <?php echo '$'.number_format($gd_aud_gram*0.417, 2); ?> Per Gram</li>
	<li>14k : <?php echo '$'.number_format($gd_aud_gram*0.5833333, 2); ?> Per Gram</li>
	<li>18k : <?php echo '$'.number_format($gd_aud_gram*0.75, 2); ?> Per Gram</li>
	<li>21k : <?php echo '$'.number_format($gd_aud_gram*0.875, 2); ?> Per Gram</li>
	<li>22k : <?php echo '$'.number_format($gd_aud_gram*0.9166666, 2); ?> Per Gram</li>
	<li>24k : <?php echo '$'.number_format($gd_aud_gram*1.00, 2); ?> Per Gram</li>
</ul>
<div class="BannerNotice"><h6> PLEASE NOTE: The above prices are trade prices.</h6> </div>
<!--<div class="BannerNotice">No Hidden Fee’s. Most trusted in Melbourne, <a href="tel:0396501758">speak to us</a> today</div>-->
</div>
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