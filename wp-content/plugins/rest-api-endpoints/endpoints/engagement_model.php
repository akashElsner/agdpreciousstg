<?php

    function rest_engagement_model_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 1749;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        // $finalArr['engagement_model']['seo_title'] = get_post_meta($page_id,'_aioseop_title',true);
        // $finalArr['engagement_model']['seo_description'] = get_post_meta($page_id,'_aioseop_description',true);
        $finalArr['engagement_model']['title'] = get_field('engagement_banner_title', $page_id);
        $finalArr['engagement_model']['content'] = get_field('engagement_banner_content', $page_id);  
        
        if( have_rows('engagement_models', $page_id) ){
        $j=1;
        while( have_rows('engagement_models', $page_id) ){
        the_row();
            $finalArr['engagement_models'][$j]['tab_title'] = get_sub_field('title', $page_id);
            $finalArr['engagement_models'][$j]['tab_slug'] = get_sub_field('slug', $page_id);
            $finalArr['engagement_models'][$j]['tab_content'] = get_sub_field('tab_content', $page_id);
            $finalArr['engagement_models'][$j]['main_content'] = get_sub_field('content', $page_id);
            $finalArr['engagement_models'][$j]['content_list'] = get_sub_field('content_list', $page_id);
            if (have_rows("icon_repeater", $page_id)) {
                $k = 1;
                while (have_rows("icon_repeater", $page_id)) {
                    the_row();
                    $finalArr["engagement_models"][$j]['links'][$k]["icon"] = get_sub_field("icon", $page_id);
                    $finalArr["engagement_models"][$j]['links'][$k]["label"] = get_sub_field("label", $page_id);
                    $k++;
                }
            }
            $finalArr['engagement_models'][$j]['pros'] = get_sub_field('pros', $page_id);
            $finalArr['engagement_models'][$j]['cons'] = get_sub_field('cons', $page_id);
            $j++;
            }
        }
    
        $technology_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'technologies',
        );
        $$technology_query = new WP_Query( $technology_args );
        
        if( $$technology_query->have_posts() ){
            $i=1;
            while( $$technology_query->have_posts() ){
                $$technology_query->the_post();
                $finalArr['technology'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                // $finalArr[$i]['link'] = get_the_permalink();
                
                $terms = get_the_terms( get_the_ID() , array( 'technologies_categories') );
                
                if( !isset($terms->errors) ){
                    $finalArr['technology'][$i]['terms'] = $terms;
                }
                $finalArr['technology'][$i]['title'] = get_the_title();
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