<?php
   
    
    function rest_blogdetail_callback( $request ){
    $courseArr = array();
    
    if( isset($request['blog_slug']) ){
        $args = array(
          'name'         => $request['blog_slug'], // slug of a page, post, or custom type
          'post_type' => 'post',
    	  'post_status' => 'publish'
        );
    }elseif( isset($request['blog_id']) ){
        $args = array(
          'p'         => $request['blog_id'], // ID of a page, post, or custom type
          'post_type' => 'post',
    	  'post_status' => 'publish'
        );
    }
    
    $the_query = new WP_Query( $args );
     
    if ( $the_query->have_posts() ) {
        
        while ( $the_query->have_posts() ) {
             $the_query->the_post();
            $id = get_the_ID();
            $title = get_the_title();
            $content = get_the_content();
            $schema_content = wp_strip_all_tags($content);
            $content_sec = apply_filters('the_content', $content);
            $image = get_the_post_thumbnail_url();
            $author = get_the_author();
            $prev_link = get_previous_post();
            $next_link = get_next_post();
            $nexturl = $next_link->ID;
            $nextslug = basename(get_permalink($next_link->ID));
            $prevurl = $prev_link->ID;
            $prevslug = basename(get_permalink($prev_link->ID));
            $slug = basename(get_permalink());
            $category = get_the_category();
            $date = get_the_date();
            $read = reading_time();
            // $read = reading_time();
            // $toc = get_the_table_of_contents(); 
            $view = do_shortcode('[views id="'.get_the_ID().'"]'); 
        }
        
    } 
    $courseArr['id']=  $id;
    $courseArr['slug'] = $slug;
    $courseArr['title'] = $title;
    $courseArr['image'] = $image;
    $courseArr['content'] = $content_sec;
    $courseArr['schema_content'] = $schema_content;
    $courseArr['author'] = $author;
    $courseArr['prev_post'] = $prevurl;
    $courseArr['prev_slug'] = $prevslug;
    $courseArr['next_post'] = $nexturl;
    $courseArr['next_slug'] = $nextslug;
    $courseArr['date'] = $date;
    $courseArr['read_time'] = $read;
    
    $courseArr['view'] = $view;
    $courseArr['category'] = $category;
    $courseArr['toc'] = get_the_table_of_contents(); 
    /* Restore original Post Data */
    wp_reset_postdata();
   

    // $courseArr['toc'] = get_the_table_of_contents(); 
	$response = new WP_REST_Response($courseArr);
    $response->set_status(200);

    return $response;
    }