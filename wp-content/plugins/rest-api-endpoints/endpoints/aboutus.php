<?php
    function rest_aboutus_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 30;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
    //    $finalArr['page_slug'] = get_the_slug($page_id);
        $finalArr['about_us']['banner']['image'] = get_field('about_us_banner_image',$page_id);
        $finalArr['about_us']['banner']['title'] = get_field('about_us_banner_title',$page_id);
        $finalArr['about_us']['banner']['content'] = get_field('about_us_banner_content',$page_id);
        $finalArr['about_us']['banner']['video'] = get_field('about_us_video',$page_id);
        $finalArr['about_us']['who_are_we']['title'] = get_field('who_are_we_title',$page_id);
        $finalArr['about_us']['who_are_we']['content'] = get_field('who_are_we_content',$page_id);
        if( have_rows('who_are_we_repeater', $page_id) ){
            $j=1;
            while( have_rows('who_are_we_repeater', $page_id) ){
                the_row();
                $finalArr['about_us']['who_are_wes'][$j]['subtitle'] = get_sub_field('who_are_we_subtitle',$page_id);
                 $finalArr['about_us']['who_are_wes'][$j]['subcontent'] = get_sub_field('who_are_we_subcontent',$page_id);
                 $j++;
            }
        }
        
        
        $finalArr['about_us']['our_value']['title'] = get_field('our_value_title',$page_id);
        $finalArr['about_us']['our_value']['content'] = get_field('our_value_content',$page_id);
        if( have_rows('our_value_repeater', $page_id) ){
            $j=1;
            while( have_rows('our_value_repeater', $page_id) ){
                the_row();
                $finalArr['about_us']['our_values'][$j]['icon'] = get_sub_field('our_value_icon',$page_id);
                $finalArr['about_us']['our_values'][$j]['subtitle'] = get_sub_field('our_value_subtitle',$page_id);
                $finalArr['about_us']['our_values'][$j]['subcontent'] = get_sub_field('our_value_subcontent',$page_id);
                 $j++;
            }
        }
        
        $finalArr['about_us']['things_achieved']['title'] = get_field('things_achieved_title',$page_id);
        $finalArr['about_us']['things_achieved']['content'] = get_field('things_achieved_content',$page_id);
        if( have_rows('counter_repeater', $page_id) ){
            $j=1;
            while( have_rows('counter_repeater', $page_id) ){
                the_row();
                $finalArr['about_us']['things_achieve'][$j]['number'] = get_sub_field('counter_number',$page_id);
                $finalArr['about_us']['things_achieve'][$j]['title'] = get_sub_field('counter_title',$page_id);
                 $j++;
            }
        }
        
        $finalArr['about_us']['our_leader']['title'] = get_field('our_leader_title',$page_id);
        $finalArr['about_us']['our_leader']['content'] = get_field('our_leader_content',$page_id);
        if( have_rows('our_leader_repeater', $page_id) ){
            $j=1;
            while( have_rows('our_leader_repeater', $page_id) ){
                the_row();
                $finalArr['about_us']['leader'][$j]['image'] = get_sub_field('leader_image',$page_id);
                $finalArr['about_us']['leader'][$j]['name'] = get_sub_field('leader_name',$page_id);
                $finalArr['about_us']['leader'][$j]['position'] = get_sub_field('leader_position',$page_id);
                $finalArr['about_us']['leader'][$j]['quote'] = get_sub_field('leader_quote',$page_id);
                $finalArr['about_us']['leader'][$j]['content'] = get_sub_field('leader_content',$page_id);
                $finalArr['about_us']['leader'][$j]['button_text'] = get_sub_field('leader_button_text',$page_id);
                $finalArr['about_us']['leader'][$j]['button_link'] = get_sub_field('leader_button_link',$page_id);
                 $j++;
            }
        }
        
        $finalArr['about_us']['our_ventures']['title'] = get_field('our_ventures_title',$page_id);
        $finalArr['about_us']['our_ventures']['content'] = get_field('our_ventures_content',$page_id);
        if( have_rows('our_ventures_repeater', $page_id) ){
            $j=1;
            while( have_rows('our_ventures_repeater', $page_id) ){
                the_row();
                $finalArr['about_us']['ventures'][$j]['image'] = get_sub_field('our_ventures_image',$page_id);
                $finalArr['about_us']['ventures'][$j]['name'] = get_sub_field('our_ventures_name',$page_id);
                 $j++;
            }
        }
        
        $json_url = get_field('get_in_touch_animation', $page_id);
        $json = file_get_contents($json_url);
    
        $finalArr['get_in_touch']['animation'] = $json;
        $finalArr['get_in_touch']['title'] = get_field('get_in_touch_title', $page_id);
        $finalArr['get_in_touch']['subtitle'] = get_field('get_in_touch_subtitle', $page_id);
        $finalArr['get_in_touch']['buttontext'] = get_field('get_in_touch_button_text', $page_id);
        $finalArr['get_in_touch']['buttonlink'] = get_field('get_in_touch_button_link', $page_id);
        
        
        
        $enable_acknowledgements_section = get_post_meta( $page_id, 'enable_acknowledgements_section', true );
        $finalArr['enable_acknowledgements_section'] = 'false';
        if( $enable_acknowledgements_section == 1 ){
            $finalArr['enable_acknowledgements_section'] = 'true';
        }
        
        // $enable_another_cta = get_post_meta( $page_id, 'enable_another_cta', true );
        // $finalArr['enable_another_cta'] = 'false';
        // if( $enable_another_cta == 1 ){
        //     $finalArr['enable_another_cta'] = 'true';
        // }
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }
?>