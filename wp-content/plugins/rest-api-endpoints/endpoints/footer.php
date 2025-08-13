<?php
    function rest_footer_callback( $request  ){
        $finalArr = array();
        $footerNav_1 = wp_get_nav_menu_items(865);
        $footerNav_2 = wp_get_nav_menu_items(589);
        
        $address = get_field('development_center_address', 'option');
        $usa_address = get_field('usa_center_address','option');
        $certified = get_field('footer_secure_image','option');
        $copyrights = get_field('copyright_text','option');
        
        $sales_phone_number = get_field('sales_phone_number', 'option');
        $sales_email_address = get_field('sales_email_address', 'option');
        $skype_id = get_field('skype_id', 'option');
        
        $linkedin = get_field('linkedin', 'option');
        $instagram = get_field('instagram', 'option');
        $twitter = get_field('twitter', 'option');
        $facebook = get_field('facebook', 'option');
        
        /*echo '<pre>';
        print_r($footerNav_1);
        echo '</pre>';*/
        
        $i=1;
    	$finalArr['column_1']['title'] = 'What We Do';
    	foreach($footerNav_1 as $navItem_1){
    	    $finalArr['column_1']['links'][$i]['url'] = $navItem_1->url;
    		$finalArr['column_1']['links'][$i]['title'] = $navItem_1->title;
    		$i++;
    	}
    	
    	$i=1;
    	$finalArr['column_2']['title'] = 'About Elsner';
    	foreach($footerNav_2 as $navItem_2){
    	    $finalArr['column_2']['links'][$i]['url'] = $navItem_2->url;
    		$finalArr['column_2']['links'][$i]['title'] = $navItem_2->title;
    		$i++;
    	}
    	
    	$finalArr['column_3']['title'] = 'Connect';
    	
    	$finalArr['social_media']['phone']['icon'] = '<i class="fas fa-phone"></i>';
    	$finalArr['social_media']['phone']['number'] = $sales_phone_number;
    	
    	$finalArr['social_media']['email']['icon'] = '<i class="fas fa-envelope"></i>';
    	$finalArr['social_media']['email']['address'] = $sales_email_address;
    	
    	$finalArr['social_media']['skype']['icon'] = '<i class="fas fa-skype"></i>';
    	$finalArr['social_media']['skype']['id'] = $skype_id;
    	
    	$finalArr['social_media']['linkedin']['icon'] = '<i class="fab fa-linkedin-in"></i>';
    	$finalArr['social_media']['linkedin']['id'] = $linkedin;
    	
    	$finalArr['social_media']['instagram']['icon'] = '<i class="fab fa-instagram"></i>';
    	$finalArr['social_media']['instagram']['id'] = $instagram;
    	
    	$finalArr['social_media']['twitter']['icon'] = '<i class="fab fa-twitter"></i>';
    	$finalArr['social_media']['twitter']['id'] = $twitter;
    	
    	$finalArr['social_media']['facebook']['icon'] = '<i class="fab fa-facebook-f"></i>';
    	$finalArr['social_media']['facebook']['id'] = $facebook;
        
        $finalArr['column_4']['title'] = 'Address';
        $finalArr['column_4']['content_title'] = 'Headquarter-India';
    	$finalArr['column_4']['content'] = $address;
        
        $finalArr['column_5']['content_title'] = 'USA';
    	$finalArr['column_5']['content'] = $usa_address;
        
        $finalArr['column_6']['certified_logo'] = $certified;
        $finalArr['column_6']['copyrights'] = $copyrights;
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }