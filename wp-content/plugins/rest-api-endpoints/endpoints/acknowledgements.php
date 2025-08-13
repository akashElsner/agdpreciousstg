<?php
    function rest_acknowledgements_callback( $request ){
        $finalArr = array();
        
        $finalArr['acknowledgements']['title'] = get_field('our_acknowledgements_title', 'option');
        $finalArr['acknowledgements']['content'] = get_field('our_acknowledgements_content', 'option');
        
        if( have_rows('our_acknowledgements_logo_repeater', 'option') ){
            $i=1;
            while( have_rows('our_acknowledgements_logo_repeater', 'option') ){
                the_row();
                $finalArr['acknowledgements']['logos'][$i] = get_sub_field('our_acknowledgements_logos', 'option');
                $i++;
            }
        }
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }
?>