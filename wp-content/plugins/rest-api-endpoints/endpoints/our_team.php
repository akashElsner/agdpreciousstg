<?php

    function rest_our_team_callback( $request ){
         $id = $request->get_param( 'id' );
         $page_id = 13819;
        
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        //echo $page_id;
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        $finalArr['Our_team']['title'] = get_field('team_title',$page_id);
        $finalArr['Our_team']['subtitle'] = get_field('team_subtitle',$page_id);
        $finalArr['Our_team']['subcontent'] = get_field('team_subcontent',$page_id);
       if( have_rows('team', $page_id) ){
                    $j=1;
                    while( have_rows('team', $page_id) ){
                        the_row();
                        $finalArr['team'][$j]['member_name'] = get_sub_field('member_name', $page_id);
                        $finalArr['team'][$j]['member_position'] = get_sub_field('member_position', $page_id);
                        $finalArr['team'][$j]['member_info'] = get_sub_field('member_info', $page_id);
                        $finalArr['team'][$j]['member_photo'] = get_sub_field('member_photo', $page_id);
                        $finalArr['team'][$j]['facebook_link'] = get_sub_field('facebook_link', $page_id);
                        $finalArr['team'][$j]['linkedin_link'] = get_sub_field('linkedin_link', $page_id);
                        $finalArr['team'][$j]['twitter_link'] = get_sub_field('twitter_link', $page_id);
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