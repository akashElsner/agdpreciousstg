<?php
    function rest_cta_callback( $request ){
        $finalArr = array();
        
        $finalArr['title'] = get_field('footer_cta', 'option');
        $finalArr['content'] = get_field('footer_cta_content', 'option');
        $finalArr['button_title'] = get_field('footer_cta_button_title', 'option');
        $finalArr['button_link'] = get_field('footer_cta_button_url', 'option');
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }
?>