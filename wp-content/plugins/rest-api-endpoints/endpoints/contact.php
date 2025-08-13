<?php
function rest_contact_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 4082;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        $finalArr['contact']['image'] = get_field('contact_map_image', $page_id);
        $finalArr['contact']['title'] = get_field('contact_title', $page_id);
        $finalArr['contact']['content'] = get_field('contact_content', $page_id);
        if( have_rows('contact_detail_repeater', $page_id) ){
             $j=1;
             while( have_rows('contact_detail_repeater', $page_id) ){
             the_row();
               $finalArr['contact_details']['links'][$j]['heading'] = get_sub_field('contact_detail_title', $page_id);
               $finalArr['contact_details']['links'][$j]['content'] = get_sub_field('contact_details', $page_id);
               $j++;
             }
        }
        $finalArr['contact']['worldwide'] = get_field('worldwide_title', $page_id);    
        if( have_rows('worldwide_repeater', $page_id) ){
             $k=1;
             while( have_rows('worldwide_repeater', $page_id) ){
             the_row();
               $finalArr['contact']['links'][$k]['flag_image'] = get_sub_field('flag', $page_id);
               $finalArr['contact']['links'][$k]['country_name'] = get_sub_field('country_name', $page_id);
               $finalArr['contact']['links'][$k]['country_address'] = get_sub_field('country_address', $page_id);
               $finalArr['contact']['links'][$k]['country_number'] = get_sub_field('country_number', $page_id);
               $k++;
             }
        }
  $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
}

?>