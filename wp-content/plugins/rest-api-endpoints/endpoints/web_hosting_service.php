<?php

    function rest_web_hosting_service_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 20925;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
                $finalArr['banner']['banner_content'] = get_field('banner_content', $page_id);
                $finalArr['banner']['banner_button'] = get_field('top_cta_title', $page_id);
                $finalArr['banner']['Services_title'] = get_field('services_title', $page_id);
                if( have_rows('services', $page_id) ){
                $k=1;
                while( have_rows('services', $page_id) ){
                the_row();
                        $finalArr['services']['links'][$k]['title'] = get_sub_field('service_title', $page_id);
                        $finalArr['services']['links'][$k]['content'] = get_sub_field('service_description', $page_id);
                
                    $k++;
                    }
                }
                
        
        if( have_rows('reasons_to_choose_us', $page_id) ){
            $i=1;
            while( have_rows('reasons_to_choose_us', $page_id) ){
            the_row();
            $finalArr['reason_to_choose'][$i]['title'] = get_sub_field('reason_text_title', $page_id);
            $finalArr['reason_to_choose'][$i]['content'] = get_sub_field('reason_title', $page_id);
            $finalArr['reason_to_choose'][$i]['icon'] = get_sub_field('reason_icon', $page_id);
            $i++;                        
            }
        }
        $finalArr['other_services']['title'] = get_field('other_services_title', $page_id);
        $finalArr['other_services']['subtitle'] = get_field('other_services_sub_title', $page_id);
                if( have_rows('other_services', $page_id) ){
                 $k=1;
                while( have_rows('other_services', $page_id) ){
                the_row();
                $finalArr['other_services']['links'][$k]['icon'] = get_sub_field('service_icon', $page_id);
                $finalArr['other_services']['links'][$k]['title'] = get_sub_field('service_title', $page_id);
                // $finalArr['healthcare_software_solution']['links'][$k]['content'] = get_sub_field('solution_content', $page_id);
                $k++;
                }
            }
        
        
        if( have_rows('hosting_plans', $page_id) ){
                 $k=1;
                while( have_rows('hosting_plans', $page_id) ){
                the_row();
                $finalArr['hosting_plans']['links'][$k]['title'] = get_sub_field('plan_title', $page_id);
                    if( have_rows('plan_box', $page_id) ){
                    $j=1;
                    while( have_rows('plan_box', $page_id) ){
                    the_row();
                        $finalArr['hosting_plans']['links'][$k]['child'][$j]['vps_title'] = get_sub_field('plan_vps_title', $page_id);
                        $finalArr['hosting_plans']['links'][$k]['child'][$j]['price'] = get_sub_field('plan_price', $page_id);
                        $finalArr['hosting_plans']['links'][$k]['child'][$j]['valid'] = get_sub_field('plan_valid', $page_id);
                        if( have_rows('plan_information', $page_id) ){
                        $i=1;
                        while( have_rows('plan_information', $page_id) ){
                        the_row();
                            $finalArr['hosting_plans']['links'][$k]['child'][$j]['child'][$i]['info_list'] = get_sub_field('info_list', $page_id);
                        $i++;
                        }
                    }
                    $j++;
                    }
                }
        
                $k++;
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