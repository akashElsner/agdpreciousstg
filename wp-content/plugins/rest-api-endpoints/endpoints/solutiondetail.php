<?php
    function rest_solutiondetail_callback( $request ){
        
if( isset($request['solution_slug']) ){
        $args = array(
          'name'         => $request['solution_slug'], // slug of a page, post, or custom type
          'post_type' => 'solution',
    	  'post_status' => 'publish'
        );
    }elseif( isset($request['solution_id']) ){
        $args = array(
          'p'         => $request['solution_id'], // ID of a page, post, or custom type
          'post_type' => 'solution',
    	  'post_status' => 'publish'
        );
    }        
        
//       $args = array(
//           'post__in' => array($request['portfolio_id']), 
// 		   'post_type' => 'portfolio',
// 			'post_status' => 'publish'
//     );

    $posts['data'] = get_posts($args);
    
   
	$courseArr = array();
	$content = $posts['data'][0]->post_content;
	$content_sec = apply_filters('the_content', $content);
	$courseArr['id']=  $posts['data'][0]->ID;
	$courseArr['slug'] = basename(get_permalink($posts['data'][0]->ID));
	
	$courseArr['title'] = $posts['data'][0]->post_title;
	$courseArr['image'] = get_the_post_thumbnail_url($posts['data'][0]->ID,'large');
    $courseArr['category'] = wp_get_object_terms($posts['data'][0]->ID, 'solution-category', $args);
    
  
	$courseArr['content'] = $content_sec;
    $courseArr['date'] = get_the_date();
    $courseArr['author'] = get_the_author();
    $courseArr['views'] = do_shortcode('[views id="'.get_the_ID().'"]'); 
	
 
	$response = new WP_REST_Response($courseArr);
    $response->set_status(200);

    return $response;
    }