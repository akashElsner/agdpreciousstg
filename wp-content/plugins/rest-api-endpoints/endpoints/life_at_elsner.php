<?php

    function rest_life_at_elsner_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 6112;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        $finalArr['life_at_elsner']['title'] = get_field('life_at_elsner_title', $page_id);
        $finalArr['life_at_elsner']['subtitle'] = get_field('life_at_elsner_subtitle', $page_id);
        $finalArr['life_at_elsner']['content'] = get_field('life_at_elsner_content', $page_id);  
        
        if( have_rows('image_album', $page_id) ){
        $j=1;
        while( have_rows('image_album', $page_id) ){
        the_row();
            $finalArr['lifeatelsner'][$j]['event_name'] = get_sub_field('title', $page_id);
            $finalArr['lifeatelsner'][$j]['event_main_image'] = get_sub_field('main_image', $page_id);
            $finalArr['lifeatelsner'][$j]['event_year'] = get_sub_field('year', $page_id);
            $finalArr['lifeatelsner'][$j]['event_type'] = get_sub_field('categories', $page_id);
                if( have_rows('popup_album_images', $page_id) ){
                $k=1;
                while( have_rows('popup_album_images', $page_id) ){
                the_row();
                    $finalArr['lifeatelsner'][$j]['child'][$k]['all_image'] = get_sub_field('album_image', $page_id);
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