<?php
    function rest_testimonials_callback( $request ){
         $id = $request->get_param( 'id' );
         $page_id = 128;
        
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        $finalArr['testimonials_main']['bannertitle'] = get_field('banner_title', $page_id);
        $finalArr['testimonials_main']['bannersubtitle'] = get_field('banner_subtitle', $page_id);
        $finalArr['testimonials_main']['bannercontent'] = get_field('banner_content', $page_id);
        $testimonials_args = array(
            'post_type' => 'testimonial',
            'posts_per_page' => -1,
        
        );
        $testimonials_query = new WP_Query( $testimonials_args );
        
        if( $testimonials_query->have_posts() ){
            $i=1;
            while( $testimonials_query->have_posts() ){
                $testimonials_query->the_post();
                $finalArr['testimonials'][$i]['image'] = get_field('client_photo', get_the_ID());
                $finalArr['testimonials'][$i]['title'] = get_the_title(get_the_ID());
                $finalArr['testimonials'][$i]['content'] = get_the_content(get_the_ID());
                $finalArr['testimonials'][$i]['client_position'] = get_field('client_position', get_the_ID());
                $finalArr['testimonials'][$i]['video_button_text'] =  get_field('video_button_text',get_the_ID());
                $finalArr['testimonials'][$i]['video_url'] =  get_field('video_url',get_the_ID());
                
                $i++;
            }
        }
  
        global $wpdb;
        $rows = $wpdb->get_results( "SELECT * FROM et_grp_google_review");
        $i=1;
        foreach ( $rows as $row ) 
        {
 
        $finalArr['goggle_review'][$i]['rating'] = $row->rating; 
        $finalArr['goggle_review'][$i]['review'] = $row->text; 
        $finalArr['goggle_review'][$i]['author_name'] = $row->author_name;; 
        $finalArr['goggle_review'][$i]['author_url'] = $row->author_url;; 
        $finalArr['goggle_review'][$i]['profile_photo'] = $row->profile_photo_url; 
        $i++;  
       
         }
     
        //  $finalArr['goggle_review'] = do_shortcode('[google-reviews-pro place_photo=https://maps.gstatic.com/mapfiles/place_api/icons/generic_business-71.png place_name="Elsner Technologies - Wordpress & Magento Development Company" place_id=ChIJf45Lzc-EXjkRqM4Y2EbKzN4 auto_load=true sort=1 min_filter=4 write_review=true text_size=120 view_mode=list open_link=true]');
        // $finalArr['goggle_review'] = $gogglereview;
        
        
        $clutch_args = array(
            'post_type' => 'clutch_review',
            'posts_per_page' => -1,
        
        );
        $clutch_query = new WP_Query( $clutch_args );
        
        if( $clutch_query->have_posts() ){
            $i=1;
            while( $clutch_query->have_posts() ){
                $clutch_query->the_post();
                $finalArr['clutch_review'][$i]['image'] = get_the_post_thumbnail_url(get_the_ID());
                $finalArr['clutch_review'][$i]['title'] = get_the_title(get_the_ID());
                $finalArr['clutch_review'][$i]['content'] = get_the_content(get_the_ID());
                $finalArr['clutch_review'][$i]['the_reviewer'] = get_field('the_reviewer', get_the_ID());
                $finalArr['clutch_review'][$i]['reviewer_position'] =  get_field('reviewer_position',get_the_ID());
                $i++;
            }
        }
        
        $json_url = get_field("get_in_touch_animation", $page_id);
        $json = file_get_contents($json_url);
        $finalArr["get_in_touch"]["animation"] = $json;
        $finalArr["get_in_touch"]["title"] = get_field(
            "get_in_touch_title",
            $page_id
        );
        $finalArr["get_in_touch"]["subtitle"] = get_field(
            "get_in_touch_subtitle",
            $page_id
        );
        $finalArr["get_in_touch"]["buttontext"] = get_field(
            "get_in_touch_button_text",
            $page_id
        );
        $finalArr["get_in_touch"]["buttonlink"] = get_field(
            "get_in_touch_button_link",
            $page_id
        );
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }