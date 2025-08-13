<?php

    function rest_portfoliocategories_callback( $request ){
        $finalArr = array();
        $categories = get_terms( array(
        'taxonomy' => 'portfolio-technology',
        'hide_empty' => false,
        ) );
        
        foreach($categories as $category) {
            if ($category->count > 0) {
            $i = $category->term_id; 
            $finalArr['category']['links'][$i]['name'] = $category->name; 
            $finalArr['category']['links'][$i]['slug'] = $category->slug; 
            }
        }   
                           
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }