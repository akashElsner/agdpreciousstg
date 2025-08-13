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