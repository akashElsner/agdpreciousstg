<?php

    function rest_awards_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 102;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        $partners_args = array(
            'post_type' => 'award',
            'posts_per_page' => -1,
        
        );
        $partners_query = new WP_Query( $partners_args );
        
        
        $finalArr['awards_section']['title'] = get_field('partner_title', $page_id);
        $finalArr['awards_section']['subtitle'] = get_field('partner_subtitle', $page_id);
        $finalArr['awards_section']['content'] = get_field('partner_content', $page_id);   
        $finalArr['awards_section']['buttontext'] = get_field('partner_button_text', $page_id);
        $finalArr['awards_section']['buttonlink'] = get_field('partner_button_link', $page_id);
        
        if( $partners_query->have_posts() ){
            $i=1;
            while( $partners_query->have_posts() ){
                $partners_query->the_post();
                $finalArr['links'][$i]['image'] = get_the_post_thumbnail_url(get_the_ID());
                $finalArr['links'][$i]['title'] = get_the_title(get_the_ID());
                $finalArr['links'][$i]['content'] = get_the_content(get_the_ID());
                
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