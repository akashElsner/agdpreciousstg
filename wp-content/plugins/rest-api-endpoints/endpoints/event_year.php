<?php

    function rest_event_year_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 6112;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
    
        if( have_rows('image_album', $page_id) ){
        $j=1;
        while( have_rows('image_album', $page_id) ){
        the_row();
            $array_test = get_sub_field('categories', $page_id, true);
            $finalArr['lifeatelsner'][$array_test] = $array_test;
            $j++;
            }
        }
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }