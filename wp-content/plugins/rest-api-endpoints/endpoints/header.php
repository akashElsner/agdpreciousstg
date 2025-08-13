<?php
    function rest_header_callback( $request  ){
        
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );

        if( $image )
        $finalArr['logo-url'] = $image[0];
        
        $finalArr['site-title'] = get_bloginfo( 'name' );
        $finalArr['site-url'] = home_url('/');
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
        
        $logo = get_field('light_logo','options');
        $finalArr = array();
        $finalArr['logo'] = $logo;
        $finalArr['logo-url'] = 'https://staging.elsner.com/';
        $primaryNav = wp_get_nav_menu_items('header-menu');
        $secondaryNav = wp_get_nav_menu_items(1508);
        
        $sales_phone_number = get_field('sales_phone_number', 'option');
        $sales_email_address = get_field('sales_email_address', 'option');
        
        $address = get_field('development_center_address', 'option');
        $usa_address = get_field('usa_center_address','option');
        $usa_address2 = get_field('usa_center_second_address','option');
        
        $linkedin = get_field('linkedin', 'option');
        $instagram = get_field('instagram', 'option');
        $twitter = get_field('twitter', 'option');
        $facebook = get_field('facebook', 'option');
        
        //   global $post; // VERY important!
        
        //   // Retrieve title meta data from the SEO Pack
        //   $seotitle = stripslashes(get_post_meta($post->ID, '_aioseop_title', true));
        
        //   // Default description in case none is specified for the page
        //   if (empty($seotitle)) $seotitle = "";
        
        //   // Output the html code
        //   $seotitle_block = "<title>".$seotitle."</title><meta name=\"title\" content=\"".$seotitle."\"/>\n";
        //   echo $seotitle_block;
       
                
        $i=1;
    	foreach($primaryNav as $navItem){
            $slug = basename($navItem->url);
    		// $finalArr['nav']['links'][$i]['url'] = $navItem->url;
    		$finalArr['nav']['links'][$i]['title'] = $navItem->title;
    		$finalArr['nav']['links'][$i]['slug'] = $slug;
    		$j=1;
    		if( have_rows('menu_block', $navItem->ID) ):
                while( have_rows('menu_block', $navItem->ID) ) : the_row();
                    // $finalArr['nav'][$i]['child'][$j]['url'] = get_sub_field('menu_link', $navItem->ID);
                    $finalArr['nav'][$i]['child'][$j]['title'] = get_sub_field('menu_title', $navItem->ID);
                    $finalArr['nav'][$i]['child'][$j]['logo'] = get_sub_field('menu_icon', $navItem->ID);
                    $k=1;
                    if( have_rows('menu_items', $navItem->ID) ):
                        while( have_rows('menu_items', $navItem->ID) ) : the_row();
                            $slugs = get_sub_field('page', $navItem->ID);
            //                 echo $slugs;
                            // $test = trim($slugs,"https://office.elsner.com/");
                            // echo $test;
                            // $finalArr['nav'][$i]['child'][$j]['menu'][$k]['url'] = get_sub_field('page', $navItem->ID);
                            $finalArr['nav'][$i]['child'][$j]['menu'][$k]['slug'] = basename($slugs);
                            $finalArr['nav'][$i]['child'][$j]['menu'][$k]['title'] = get_sub_field('page_title', $navItem->ID);
                            $k++;
                        endwhile;
                    endif;
                    $j++;
                
                endwhile;
            endif;
    		$i++;
    	}
    
        $finalArr['nav']['links'][$i]['url'] = site_url().'/contact-us/';
    	$finalArr['nav']['links'][$i]['title'] = 'Contact Us';
    	$finalArr['nav']['links'][$i]['slug'] = 'contact-us';
    	
    	$finalArr['menu']['title'] ='Menu';
    	 $p=1;
    	foreach($secondaryNav as $navItem){
            $slug = basename($navItem->url);
            // $finalArr['menu']['links'][$p]['url'] = $navItem->url;
    		$finalArr['menu']['links'][$p]['title'] = $navItem->title;
    		$finalArr['menu']['links'][$p]['slug'] = $slug;
    		$q=1;
    		if( have_rows('menu_block', $navItem->ID) ):
                while( have_rows('menu_block', $navItem->ID) ) : the_row();
                    // $finalArr['nav'][$p]['child'][$q]['url'] = get_sub_field('menu_link', $navItem->ID);
                    $finalArr['nav'][$p]['child'][$q]['title'] = get_sub_field('menu_title', $navItem->ID);
                    $finalArr['nav'][$p]['child'][$q]['logo'] = get_sub_field('menu_icon', $navItem->ID);
                    $r=1;
                    if( have_rows('menu_items', $navItem->ID) ):
                        while( have_rows('menu_items', $navItem->ID) ) : the_row();
                            // $finalArr['nav'][$p]['child'][$q]['menu'][$r]['url'] = get_sub_field('page', $navItem->ID);
                            $finalArr['nav'][$p]['child'][$q]['menu'][$r]['title'] = get_sub_field('page_title', $navItem->ID);
                            $k++;
                        endwhile;
                    endif;
                    $q++;
                
                endwhile;
            endif;
    		$p++;
    	}
    	
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
    	
        $finalArr['column_4']['content_title'] = 'Headquarter-India';
    	$finalArr['column_4']['content'] = $address;
        
        $finalArr['column_5']['content_title'] = 'USA';
    	$finalArr['column_5']['content'] = $usa_address;
    	$finalArr['column_5']['content2'] = $usa_address2;
    	
    	global $rest_api_pages;
        $all_pages_args = array(
            'post__in' => array($request['page_id']), 
            'posts_per_page'   => -1,
            'post_type'        => 'any',
        );
        
        $all_pages_query = new WP_Query( $all_pages_args );
        
        if( $all_pages_query->have_posts() ){
            while( $all_pages_query->have_posts() ){
                $all_pages_query->the_post();
                $page_id = get_the_ID();
                $rest_api_pages[$page_id]['slug'] = basename(get_permalink($page_id));
                $rest_api_pages[$page_id]['name'] = get_the_title($page_id);
                $rest_api_pages[$page_id]['permalink'] = get_the_permalink($page_id);
                $rest_api_pages[$page_id]['template'] = 'default';
            }
        }
        
        if( $rest_api_pages ){
            $i=1;
            foreach ($rest_api_pages as $key => $value) {
                $finalArr['id'] = $key;
                $finalArr['seo_title'] = get_post_meta($key,'_aioseop_title',true);
                $finalArr['seo_description'] = get_post_meta($key,'_aioseop_description',true);
                $finalArr['seo_keywords'] = get_post_meta($key,'_aioseop_keywords',true);
                $finalArr['seo_noindex'] = get_post_meta($key,'_aioseop_noindex',true);
                $finalArr['seo_nofollow'] = get_post_meta($key,'_aioseop_nofollow',true);
              
            }
        }
    	
    	$response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }