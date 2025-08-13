<?php
    function rest_whph_faq_callback( $request ){
        $number = ($request->get_param( 'number' )) ? $request->get_param( 'number' ) : -1;
        $search_txt = ($request->get_param( 's' )) ? $request->get_param( 's' ) : '';
        $offset = ($request->get_param( 'offset' )) ? $request->get_param( 'offset' ) : 0;
        $category = ($request->get_param( 'category' )) ? $request->get_param( 'category' ) : 0;
        $state = ($request->get_param( 'state' )) ? $request->get_param( 'state' ) : '';
        $hide_empty = ($request->get_param( 'hide_empty' )) ? true : false;
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
    
        $args = array(
            'offset'            => $offset,
            'posts_per_page'    => $number,
            'post_type'         => 'faqs',
        );
        
        if( $search_txt && !empty($search_txt) ){
            $args['s'] = $search_txt;
        }
        
        $terms = get_terms( array(
            'taxonomy'   => 'faq_category',
            'hide_empty' => $hide_empty,
        ) );
        
        if( $category > 0 ){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'faq_category',
                    'field' => 'id',
                    'terms' => $category,
                ),
            );
        }
        
        if( strtolower($state) == 'wa' ){
            $args['tax_query'][] = array(
                'taxonomy' => 'state',
                'field' => 'slug',
                'terms' => strtolower($state),
            );
        }else{
            $args['tax_query'][] = array(
                'taxonomy' => 'state',
                'field' => 'slug',
                'terms' => array('wa'),
                'operator' => 'NOT IN',
            );
        }
        
        $faq_query = new WP_Query( $args );
        
        if( $faq_query->have_posts() ){
            $data['faq_category'] = array();
            foreach($terms as $key => $val){
                $faq_terms_arr = array();
                $faq_terms_arr['id'] = $val->term_id;
                $faq_terms_arr['name'] = $val->name;
                $faq_terms_arr['slug'] = $val->slug;
                $faq_terms_arr['count'] = $val->count;
                $data['faq_category'][] = $faq_terms_arr;
            }
            while( $faq_query->have_posts() ){
                $faq_arr = array();
                $faq_query->the_post();
                $faq_arr['id'] = get_the_ID();
                $faq_arr['title'] = html_entity_decode(get_the_title());
                $faq_arr['content'] = html_entity_decode(get_the_content());
                
                $faq_terms = get_the_terms( get_the_ID(), 'faq_category' );
                foreach($faq_terms as $key => $val){
                    $faq_term_arr = array();
                    $faq_term_arr['category']['id'] = $val->term_id;
                    $faq_term_arr['category']['name'] = $val->name;
                    $faq_term_arr['category']['slug'] = $val->slug;
                    $faq_state_arr['category']['count'] = $val->count;
                    
                    $faq_arr['category'][] = $faq_term_arr['category'];
                    //$data['faq_category'][$val->term_id] = $faq_term_arr['category'];
                }
                
                $faq_state = get_the_terms( get_the_ID(), 'state' );
                foreach($faq_state as $key => $val){
                    $faq_state_arr = array();
                    $faq_state_arr['state']['id'] = $val->term_id;
                    $faq_state_arr['state']['name'] = $val->name;
                    $faq_state_arr['state']['slug'] = $val->slug;
                    $faq_state_arr['state']['count'] = $val->count;
                    
                    $faq_arr['state'][] = $faq_state_arr['state'];
                }
                
                $data['faqs'][] = $faq_arr; 
            }
            wp_reset_postdata();
            $data['faq_category'] = array_values(array_filter($data['faq_category']));
            $data['status'] = 'Success';
            $data['msg'] = "";
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
        
        /*$portfolio_args = array(
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
        return $response1;*/
    }