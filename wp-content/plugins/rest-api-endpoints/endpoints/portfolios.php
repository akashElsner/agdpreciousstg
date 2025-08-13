<?php

    function rest_portfolios_callback( $request ){
        $finalArr = array();
        
        $portfolio_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'portfolio',
        );
        $portfolio_query = new WP_Query( $portfolio_args );
        
        if( $portfolio_query->have_posts() ){
            $i=1;
            while( $portfolio_query->have_posts() ){
                $portfolio_query->the_post();
                $finalArr[$i]['thumbnail'] = get_the_post_thumbnail_url();
                $finalArr[$i]['link'] = get_the_permalink();
                
                $terms = get_the_terms( get_the_ID() , array( 'platform') );
                
                if( !isset($terms->errors) ){
                    $finalArr[$i]['terms'] = $terms;
                }
                $finalArr[$i]['title'] = get_the_title();
                $i++;
            }
        }
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }