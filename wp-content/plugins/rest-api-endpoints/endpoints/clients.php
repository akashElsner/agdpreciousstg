<?php

    function rest_clients_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 24863;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        $client_args = array(
            'post_type' => 'client',
            'posts_per_page' => -1,
        
        );
        $client_query = new WP_Query( $client_args );
        
        $finalArr['client_section']['title'] = get_field('client_title', $page_id);
        $finalArr['client_section']['subtitle'] = get_field('client_subtitle', $page_id);
        $finalArr['client_section']['content'] = get_field('client_content', $page_id);   
        $finalArr['client_section']['buttontext'] = get_field('client_button_text', $page_id);
        $finalArr['client_section']['buttonlink'] = get_field('client_button_link', $page_id);
        
        if( $client_query->have_posts() ){
            $i=1;
            while( $client_query->have_posts() ){
                $client_query->the_post();
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