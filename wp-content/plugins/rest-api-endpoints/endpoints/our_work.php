<?php
    function rest_our_work_callback( $request ){
        $id = $request->get_param( 'id' );
        $category = $request->get_param( 'category' );
        $recent_post = $request->get_param( 'recent_post' );
        $number_arr = 0;
        $number = 10;
        $number_load = $number+10;
        
        if( $request->get_param( 'number' ) ){
            $number_arr = $request->get_param( 'number' );
            $number =  ($number_arr+10);  //210
            $number_load = $number+10;  //220
        }
        
        $page_id = 3547;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        if( have_rows('our_work_banner_section', $page_id) ){
            while( have_rows('our_work_banner_section', $page_id) ){
                the_row();
                $finalArr['our_work_banner_section']['title'] = get_sub_field('ow_banner_title', $page_id);
                $finalArr['our_work_banner_section']['content'] = get_sub_field('ow_banner_content', $page_id);
            }
        }
     $post_args = array(
            'orderby' => 'date',
            'posts_per_page' => '10',
            'order' => 'DESC',
            'post_type' => 'portfolio',
            'offset' => $number_arr,
        );
        
        if( isset($category) && !empty($category) ){
            $post_args['tax_query'] = array(
                array(
                    'taxonomy' => 'portfolio-technology',
                    'field'    => 'slug',
                    'terms'    => $category
                ),
            );
        }                       
                    
     
    $post_query = new WP_Query( $post_args );
    if( $post_query->have_posts() ){
        $j = 0;
        $filtered_post = $post_query->posts; 
        $found_posts = $post_query->found_posts;
        $flag_page = 'true';
            
        if( $found_posts > $number_arr){
            if( $found_posts <= $number  ){
                $flag_page = 'false';
            }
        }
    while($post_query->have_posts()) : $post_query->the_post();
    $category_arr = get_the_category();
        $categories_arr = array();
        
            foreach( $category_arr as $ckey => $cvalue ){
                if ($cvalue->count > 0) {
                $categories_arr[$cvalue->term_id]['id'] = $cvalue->term_id;
                $categories_arr[$cvalue->term_id]['name'] = $cvalue->name;
                $categories_arr[$cvalue->term_id]['slug'] = $cvalue->slug;
                }
            }
         $slug = basename(get_permalink());
        //$j = get_the_ID();
        $view = do_shortcode('[views id="'.get_the_ID().'"]');
        $finalArr['portfolio']['links'][$j]['id'] = get_the_id();
        $finalArr['portfolio']['links'][$j]['slug'] = $slug;
        $finalArr['portfolio']['links'][$j]['logo'] = get_field('project_logo', get_the_id());
        $finalArr['portfolio']['links'][$j]['title'] = get_the_title();
        $finalArr['portfolio']['links'][$j]['content'] = get_the_content();
        $terms = wp_get_object_terms( get_the_ID(), 'portfolio-technology', array( 'fields' => 'names' ) );
        
        $finalArr['portfolio']['links'][$j]['category'] = $terms;
        
        $finalArr['portfolio']['links'][$j]['image'] = get_the_post_thumbnail_url();
        $finalArr['portfolio']['links'][$j]['date'] = get_the_date();
        $finalArr['portfolio']['links'][$j]['author'] = get_the_author();
        $finalArr['portfolio']['links'][$j]['views'] = $view; 
        $finalArr['portfolio']['links'][$j]['read_more'] = get_the_permalink(); 
        $j++;
    endwhile;
    wp_reset_query();
    }
        if( have_rows('idea_on_mind_section_api', $page_id) ){
            while( have_rows('idea_on_mind_section_api', $page_id) ){
                the_row();
                $finalArr['idea_on_mind_section']['image'] = get_sub_field('idea_on_mind_image', $page_id);
                $finalArr['idea_on_mind_section']['title'] = get_sub_field('idea_on_mind_title', $page_id);
                $finalArr['idea_on_mind_section']['content'] = get_sub_field('idea_on_mind_content', $page_id);
                $finalArr['idea_on_mind_section']['button_text'] = get_sub_field('idea_on_mind_button_text', $page_id);
                $finalArr['idea_on_mind_section']['button_link'] = get_sub_field('idea_on_mind_button_link', $page_id);
            }
        }
        $json_url = get_field('get_in_touch_animation', $page_id);
        $json = file_get_contents($json_url);
        $finalArr['get_in_touch']['animation'] = $json;
        $finalArr['get_in_touch']['title'] = get_field('get_in_touch_title', $page_id);
        $finalArr['get_in_touch']['subtitle'] = get_field('get_in_touch_subtitle', $page_id);
        $finalArr['get_in_touch']['buttontext'] = get_field('get_in_touch_button_text', $page_id);
        $finalArr['get_in_touch']['buttonlink'] = get_field('get_in_touch_button_link', $page_id);
        $finalArr['next_portfolio'] = $flag_page;

        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }