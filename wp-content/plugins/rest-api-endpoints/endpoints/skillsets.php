<?php

    function rest_skillsets_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 16982;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        $finalArr['skillsets']['title'] = get_field('skill_title', $page_id);
        $finalArr['skillsets']['subtitle'] = get_field('skill_subtitle', $page_id);
        $finalArr['skillsets']['content'] = get_field('skill_content', $page_id);  
        
        if( have_rows('skill_logo_content', $page_id) ){
        $j=1;
        while( have_rows('skill_logo_content', $page_id) ){
        the_row();
            $finalArr['skillsets'][$j]['heading'] = get_sub_field('skill_name', $page_id);
            if( have_rows('skill_logo_and_title', $page_id) ){
            $k=1;
            while( have_rows('skill_logo_and_title', $page_id) ){
            the_row();
                $finalArr['skillsets'][$j]['child'][$k]['logo'] = get_sub_field('skill_logo', $page_id);
                $finalArr['skillsets'][$j]['child'][$k]['label'] = get_sub_field('skill_logo_title', $page_id);
                $finalArr['skillsets'][$j]['child'][$k]['link'] = get_sub_field('skill_logo_link', $page_id);
                $k++;
            }
            }
            $j++;
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