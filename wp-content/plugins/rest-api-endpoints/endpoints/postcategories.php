<?php

    function rest_postcategories_callback( $request ){
        $finalArr = array();
        $categories = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => false,
        ) );
        
        foreach($categories as $category) {
            $i = $category->term_id; 
            $finalArr['category']['links'][$i]['name'] = $category->name; 
            $finalArr['category']['links'][$i]['slug'] = $category->slug; 
        }   
                           
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }