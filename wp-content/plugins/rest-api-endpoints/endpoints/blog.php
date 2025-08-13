<?php
function reading_time() {
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    $readingtime = ceil($word_count / 200);
    if ($readingtime == 1) {
        $timer = " minute";
    } else {
        $timer = " minutes";
    }
        $totalreadingtime = $readingtime . $timer;
        return $totalreadingtime;
}
    function rest_blog_callback( $request ){
        $id = $request->get_param( 'id' );
        $category = $request->get_param( 'category' );
        $recent_post = $request->get_param( 'recent_post' );
        $search = $request->get_param( 's' );
        
        $number_arr = 0;
        $number = 10;
        $number_load = $number+10;
        
        if( $request->get_param( 'number' ) ){
            $number_arr = $request->get_param( 'number' ); //560
            $number =  ($number_arr+10);  //570
            $number_load = $number+10;  //580
        }
        $page_id = 8;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
    
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        $finalArr['maintitle'] = get_field('blog_banner_title',$page_id);
     
        $post_args = array(
            // 'name'        => $the_slug,
            'orderby' => 'date',
            'posts_per_page' => '10',
            'order' => 'DESC',
            'post_type' => 'post',
            'offset' => $number_arr,
        );
       
        if( isset($search) && !empty($search) ){
            $post_args['s'] = $search;
        }
        if( isset($category) && !empty($category) ){
            $post_args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $category
                ),
            );
        }
    
    $post_query = new WP_Query( $post_args );
 /*
    echo '<pre>';
    print_r($post_query);
    echo '</pre>';*/
 
    if( $post_query->have_posts() ){
        $i = 0;
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
                $categories_arr[$cvalue->term_id]['id'] = $cvalue->term_id;
                $categories_arr[$cvalue->term_id]['name'] = $cvalue->name;
                $categories_arr[$cvalue->term_id]['slug'] = $cvalue->slug;
            }
            
            if( $i == 0 && $recent_post == 'true' ){
                $finalArr['recentblogposts']['id'] = get_the_ID();
                $slug = basename(get_permalink());
                $finalArr['recentblogposts']['title'] = get_the_title();
                $finalArr['recentblogposts']['content'] = get_the_content();
                $finalArr['recentblogposts']['image'] = get_the_post_thumbnail_url();
                $finalArr['recentblogposts']['category'] = $categories_arr;
                $finalArr['recentblogposts']['date'] = get_the_date('j F, Y');
                $finalArr['recentblogposts']['author'] = get_the_author();
                $finalArr['recentblogposts']['views'] = do_shortcode('[views id="'.get_the_ID().'"]'); 
                $finalArr['recentblogposts']['read_more'] = get_the_permalink(); 
                $finalArr['recentblogposts']['read_time'] =  reading_time();
                $finalArr['recentblogposts']['slug'] =  $slug;
            }else{
                $view = do_shortcode('[views id="'.get_the_ID().'"]');
                
                $finalArr['allblogposts']['links'][$i]['id'] = get_the_ID();
                $slug1 = basename( get_the_permalink() ); 
                $finalArr['allblogposts']['links'][$i]['title'] = get_the_title();
                $finalArr['allblogposts']['links'][$i]['content'] = get_the_content();
                // $finalArr['allblogposts']['links'][$i]['toc'] = $toc;
                $finalArr['allblogposts']['links'][$i]['category'] = $categories_arr;
                $finalArr['allblogposts']['links'][$i]['image'] = get_the_post_thumbnail_url();
                $finalArr['allblogposts']['links'][$i]['date'] = get_the_date('j F, Y');
                $finalArr['allblogposts']['links'][$i]['author'] = get_the_author();
                $finalArr['allblogposts']['links'][$i]['views'] = $view; 
                $finalArr['allblogposts']['links'][$i]['read_more'] = get_the_permalink(); 
                $finalArr['allblogposts']['links'][$i]['read_time'] =  reading_time();
                $finalArr['allblogposts']['links'][$i]['slug'] =  $slug1;
            }
            $i++;
        endwhile;
        wp_reset_query();
    }
    
     /*$categories = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => false,
        ) );
        
        foreach($categories as $category) {
            $i = $category->term_id; 
            $finalArr['category']['links'][$i]['name'] = $category->name; 
            $finalArr['category']['links'][$i]['slug'] = $category->slug; 
        }   
       */                     
    $finalArr['next_blogs'] = $flag_page;
    $json_url = get_field('get_in_touch_animation', $page_id);
    $json = file_get_contents($json_url);

    $finalArr['get_in_touch']['animation'] = $json;
    $finalArr['get_in_touch']['title'] = get_field('get_in_touch_title', $page_id);
    $finalArr['get_in_touch']['subtitle'] = get_field('get_in_touch_subtitle', $page_id);
    $finalArr['get_in_touch']['buttontext'] = get_field('get_in_touch_button_text', $page_id);
    $finalArr['get_in_touch']['buttonlink'] = get_field('get_in_touch_button_link', $page_id);


        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }