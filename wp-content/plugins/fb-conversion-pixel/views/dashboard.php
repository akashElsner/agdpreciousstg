<?php
if (!defined('ABSPATH')) {exit;}
?>
<div class="email-to-users-compose-section">
<h1>Wordpress Facebook Conversion Pixel Settings</h1>
<p>To create facebook pixel scroll down and follow steps</p>

<form action="options.php" method="post">

    <?php settings_fields( 'fb_conversion_pixel_group'); ?>
    <?php do_settings_sections( 'fb_conversion_pixel_group' ); ?>
<table>
    <tbody>
	
    <tr  height="100">
        <td><label for="start_tracking"><?php echo __("Hide code",'fb_conversion_pixel_td');?></label> </td>
        <td><input id="start_tracking" style="float:left;margin-top:13px" name="fbcp_code_hide_btn" type="checkbox" value="false" <?php checked( 'false', get_option( 'fbcp_code_hide_btn' ) ); ?>  />
			<a class="fbp_btn" style="float:right;margin:0" href="https://www.facebook.com/ads/manager/pixel/facebook_pixel" target="_blank">Show Tracking Result</a>
		</td>
    </tr>

    <tr>
        <td><label for="fb_code_pix"><?php echo __("FB Conversion Pixel code",'fb_conversion_pixel_td');?></label></td>
        <td><textarea rows="15" cols="80" id="fb_code_pix" name="fbcp_code_pix"><?php echo get_option( 'fbcp_code_pix' ); ?></textarea> </td>
    </tr>
    </tbody>
    </table>
    <p><span style="color: red"><?php echo __("Note: ",'fb_conversion_pixel_td');?></span><?php echo __("Above code is common for all pages/posts. You also add tracking code on particular post/page",'fb_conversion_pixel_td');?></p>
    <table>
    <tbody>
    <tr><td><h2><?php echo __("Enable Fb Conversion Pixel field on below post types:",'fb_conversion_pixel_td');?></h2></td></tr>

    <?php
    $all_post = get_post_types();
    unset($all_post['post']);
    unset($all_post['page']);

    foreach($all_post as $post_type){
        $post_name = $post_type;
        $post_type =  'fbcp_'.$post_type;
        ?>

        <tr height="50">
            <td><label for="<?php echo $post_type ?>"><?php echo $post_name; ?></label></td>
            <td><input name="<?php echo $post_type ?>" id="<?php echo $post_type ?>" type="checkbox"
                       value="<?php echo $post_type ?>" <?php checked($post_type, get_option($post_type )); ?> /></td>
        </tr>

    <?php  } ?>


    <tr>
        <td> <div class="col-sm-10"><?php submit_button(); ?></td>

    </tr>

    </tbody>




</table>

</form>
<h3>To create your Facebook pixel:</h3>
<ul>
    <li>1.Go to your <a target="_blank" href="https://www.facebook.com/ads/manager/pixel/facebook_pixel"> Facebook Pixel tab</a> in Ads Manager.</li>
    <li>2.Click Create a Pixel.</li>
    <li>3.Enter a name for your pixel. You can have only one pixel per ad account, so choose a name that represents your business.</li>
    <li>Note: You can change the name of the pixel later from the Facebook Pixel tab.</li>
    <li>4.Check the box to accept the terms.</li>
    <li>5.Click Create Pixel.</li>
    <li>6.Copy code and paste</li>
</ul>
</div>
<div class="email-to-users-sidebar sidefix update-nag">
<h2>Pro Features:</h2>
<ul>
   <li><div class="dashicons dashicons-yes"></div> Create Powerful Custom Audiences</li>
   <li><div class="dashicons dashicons-yes"></div> Track Conversions To The Penny</li>
   <li><div class="dashicons dashicons-yes"></div> 1-Click WooCommerce Pixel Setup</li>
   <li><div class="dashicons dashicons-yes"></div> WooCommerce Dynamic Ads &amp; Product Catalog</li>
   <li><div class="dashicons dashicons-yes"></div> Full Event Builder</li>
   <li><div class="dashicons dashicons-yes"></div> Priority Email Support</li>
  </ul>
<div class="fbp_links">
<a class="fbp_btn" href="https://fatcatapps.com/pixelcat/premium/?id=6769" target="_blank">Demo</a>
<a class="fbp_btn" href="https://fatcatapps.com/pixelcat/premium/?id=6769" target="_blank">Buy Pro Version</a>
</div>
</div>
<div style="clear:both"></div>

<h3>You can visit our tutorial to setup Facebook pixel</h3>
<iframe width="560" height="315" src="https://www.youtube.com/embed/lArY5II8e_8" frameborder="0" allowfullscreen></iframe>

