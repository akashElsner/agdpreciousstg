<?php
    function rest_ourworkdetail_callback( $request ){
        
if( isset($request['portfolio_slug']) ){
        $args = array(
          'name'         => $request['portfolio_slug'], // slug of a page, post, or custom type
          'post_type' => 'portfolio',
    	  'post_status' => 'publish'
        );
    }elseif( isset($request['portfolio_id']) ){
        $args = array(
          'p'         => $request['portfolio_id'], // ID of a page, post, or custom type
          'post_type' => 'portfolio',
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
    $courseArr['category'] = wp_get_object_terms($posts['data'][0]->ID, 'portfolio-technology', $args);
    $courseArr['platform'] = wp_get_object_terms($posts['data'][0]->ID, 'platform', $args);
    
   
    if( have_rows('project_images_slider', $posts['data'][0]->ID) ){
    $j=1;
    while( have_rows('project_images_slider', $posts['data'][0]->ID) ){
    the_row();
    $courseArr['solution_provided']['slider'][$j]['image'] = get_sub_field('project_images', $posts['data'][0]->ID);
    $j++;
    }
    }
       
     if( have_rows('team_who_served_section', $posts['data'][0]->ID) ){
                    while( have_rows('team_who_served_section', $posts['data'][0]->ID) ){
                        the_row();
                        $courseArr['team_who_served_section']['title'] = get_sub_field('team_who_served_title', $posts['data'][0]->ID);
                        $courseArr['team_who_served_section']['subtitle'] = get_sub_field('team_who_served_subtitle', $posts['data'][0]->ID);
                        if( have_rows('team_repeater', $posts['data'][0]->ID) ){
                            $j=1;
                            while( have_rows('team_repeater', $posts['data'][0]->ID) ){
                                the_row();
                               $courseArr['team_repeater']['team'][$j]['image'] = get_sub_field('team_member_image', $posts['data'][0]->ID);
                               $courseArr['team_repeater']['team'][$j]['label'] = get_sub_field('team_member_position', $posts['data'][0]->ID);
                                $j++;
                            }
                        }
                        
                    }
                }
    
    
    $courseArr['portfolio']['logo'] = get_field('project_logo', $posts['data'][0]->ID);                
    $courseArr['portfolio']['project_challenges'] = get_field('project_highlight', $posts['data'][0]->ID);
    $courseArr['portfolio']['makingof'] = get_field('project_making_of', $posts['data'][0]->ID);
    $courseArr['portfolio']['solution_provided'] = get_field('solution_provided', $posts['data'][0]->ID);
	$courseArr['content'] = $content_sec;
    $courseArr['date'] = get_the_date();
    $courseArr['author'] = get_the_author();
    $courseArr['views'] = do_shortcode('[views id="'.get_the_ID().'"]'); 
	
 
	$response = new WP_REST_Response($courseArr);
    $response->set_status(200);

    return $response;
    }