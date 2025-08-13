<?php
    function rest_homepage_callback( $request ){
        $id = $request->get_param( 'id' );
        $homepage_id = get_option('page_on_front');
        // if( isset($id) && $id > 0 ){
        //     $homepage_id = $id;
        // }
        
        $finalArr = array();
        $finalArr['page_id'] = $homepage_id;
        if( have_rows('home_banner_section', $homepage_id) ){
            while( have_rows('home_banner_section', $homepage_id) ){
                the_row();
                
                $finalArr['banner']['title'] = get_sub_field('home_banner_title', $homepage_id);
                $finalArr['banner']['tagline'] = get_sub_field('home_banner_second_title', $homepage_id);
                $finalArr['banner']['content'] = get_sub_field('home_banner_content', $homepage_id);
                $finalArr['banner']['button_link'] = get_sub_field('home_banner_button_link', $homepage_id);
                $finalArr['banner']['button_text'] = get_sub_field('home_banner_button', $homepage_id);
                $finalArr['banner']['upwork_image'] = get_sub_field('upwork_image', $homepage_id);
                $finalArr['banner']['upwork_title'] = get_sub_field('upwork_title', $homepage_id);
                $finalArr['banner']['upwork_content'] = get_sub_field('upwork_content', $homepage_id);
            
            }
        }
        
        if( have_rows('banner_images_and_animations', $homepage_id) ){
                    $i=1;
                    while( have_rows('banner_images_and_animations', $homepage_id) ){
                        the_row();
                        $json_url = get_sub_field("animation", $homepage_id);
                        $json = file_get_contents($json_url);
                       
                        //$finalArr['counter_section'][$i]['icon'] = get_sub_field('counter_icon', $homepage_id);
                        // if( get_sub_field('choose') == 'image'){
                        $finalArr['banner_images_and_animations'][$i]['image'] = get_sub_field('image', $homepage_id);
                        // }
                        // elseif( get_sub_field('choose') == 'animation'){
                        $finalArr['banner_images_and_animations'][$i]['animation'] = $json;
                        // }
                        $i++;
                    }
                }
        
        
        if( have_rows('logo_section', $homepage_id) ){
            while( have_rows('logo_section', $homepage_id) ){
                the_row();
                $finalArr['logo_section']['title'] = get_sub_field('logo_section_text', $homepage_id);
                $i=1;
                if( have_rows('logo_images', $homepage_id) ){
                    while( have_rows('logo_images', $homepage_id) ){
                        the_row();
                        $finalArr['logo_section']['logos'][$i] = get_sub_field('logo', $homepage_id);
                        $i++;
                    }
                }
            }
        }
        
        
        
        if( have_rows('ecommerce_solution_section', $homepage_id) ){
            while( have_rows('ecommerce_solution_section', $homepage_id) ){
                the_row();
                $finalArr['ecommerce_solution_section']['title'] = get_sub_field('ecommerce_title', $homepage_id);
                $finalArr['ecommerce_solution_section']['content'] = get_sub_field('ecommerce_content', $homepage_id);
                if( have_rows('ecommerce_solution_slider', $homepage_id) ){
                    $i=1;
                    while( have_rows('ecommerce_solution_slider', $homepage_id) ){
                        the_row();
                        $myField = strip_tags ( get_sub_field('ecommerce_content', $homepage_id)  );
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['icon'] = get_sub_field('ecommerce_icon', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['content'] = $myField;
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['title'] = get_sub_field('ecommerce_title', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['link'] = get_sub_field('ecommerce_link', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['button_text'] = get_sub_field('ecommerce_button', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['certified_developers'] = get_sub_field('certified_developers', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['number'] = get_sub_field('number', $homepage_id);
                        $finalArr['ecommerce_solution_section']['solutions'][$i]['store_name'] = get_sub_field('store_name', $homepage_id);
                        $i++;
                    }
                }
            }
        }
        
        
        
        if( have_rows('testimonial_section', $homepage_id) ){
            while( have_rows('testimonial_section', $homepage_id) ){
                the_row();
                $finalArr['testimonial_section']['title'] = get_sub_field('testimonial_title', $homepage_id);
                $finalArr['testimonial_section']['totalcount']= wp_count_posts($post_type = 'testimonial');
                $testimonials_args = array(
                    'post_type' => 'testimonial',
                    'posts_per_page' => 6,
                
                );
                $testimonials_query = new WP_Query( $testimonials_args );
                
                if( $testimonials_query->have_posts() ){
                    $i=1;
                    while( $testimonials_query->have_posts() ){
                        $testimonials_query->the_post();
                       
                        $finalArr['testimonial_section']['testimonials'][$i]['image'] = get_field('client_photo', get_the_ID());
                        $finalArr['testimonial_section']['testimonials'][$i]['title'] = get_the_title(get_the_ID());
                        $finalArr['testimonial_section']['testimonials'][$i]['content'] = get_the_content(get_the_ID());
                        $finalArr['testimonial_section']['testimonials'][$i]['client_position'] = get_field('client_position', get_the_ID());
                        $finalArr['testimonial_section']['testimonials'][$i]['video_button_text'] =  get_field('video_button_text',get_the_ID());
                        $finalArr['testimonial_section']['testimonials'][$i]['video_url'] =  get_field('video_url',get_the_ID());
                        $i++;
                    }
                }
                $finalArr['testimonial_section']['link'] = get_sub_field('testimonial_button_url', $homepage_id);
                $finalArr['testimonial_section']['link_text'] = get_sub_field('testimonial_button_text', $homepage_id);
            }
        }
        
       
                $finalArr['industries_serving_section']['title'] = get_field('industries_serving_title', 'option');
                $finalArr['industries_serving_section']['subtitle'] = get_field('industries_serving_subtitle', 'option');
                if( have_rows('industries_serving_repeater', 'option') ){
                    $i=1;
                    while( have_rows('industries_serving_repeater', 'option') ){
                        the_row();
                        $finalArr['industries_serving_section']['links'][$i]['image'] = get_sub_field('industries_serving_image', 'option');
                        $finalArr['industries_serving_section']['links'][$i]['name'] = get_sub_field('industries_serving_name', 'option');
                        $i++;
                    }
                }
           
        if( have_rows('counter_section_class', $homepage_id) ){
            while( have_rows('counter_section_class', $homepage_id) ){
                the_row();
                if( have_rows('counter_repeat', $homepage_id) ){
                    $i=1;
                    while( have_rows('counter_repeat', $homepage_id) ){
                        the_row();
                        //$finalArr['counter_section'][$i]['icon'] = get_sub_field('counter_icon', $homepage_id);
                        $finalArr['counter_section'][$i]['number'] = get_sub_field('counter_number', $homepage_id);
                        $finalArr['counter_section'][$i]['title'] = get_sub_field('counter_title', $homepage_id);
                        $i++;
                    }
                }
            }
        }
        
         $portfolio_args = array(
                        'posts_per_page'   => 2,
                        'post_type'        => 'portfolio',
                        
                        
                    );
                    $portfolio_query = new WP_Query( $portfolio_args );
                    
                   
                    
                    //  if( have_rows('recent_project_section', $page_id) ){
                    //      while( have_rows('recent_project_section', $page_id) ){
                    //          the_row();
                           $finalArr['recent_project_section']['title'] = get_sub_field('recent_project_text', $page_id);
                           $finalArr['recent_project_section']['allproject'] = get_sub_field('all__project_text', $page_id);
                            $finalArr['recent_project_section']['allprojectlink'] = get_sub_field('all__project_link', $page_id);
                            if( $portfolio_query->have_posts() ){
                                $i=1;
                                while( $portfolio_query->have_posts() ){
                                   
                                    $portfolio_query->the_post();
                                     $postID = get_the_id();
                                    $slug = basename(get_permalink());
                                    $finalArr['project_section']['projects'][$i]['id'] = $postID;
                                    $finalArr['project_section']['projects'][$i]['logo'] = get_sub_field('project_logo', $page_id);
                                    $finalArr['project_section']['projects'][$i]['slug'] = $slug;
                                    $finalArr['project_section']['projects'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                                    $finalArr['project_section']['projects'][$i]['link'] = get_the_permalink();
                                    
                                     $terms = wp_get_object_terms( $postID, 'portfolio-technology', array( 'fields' => 'names' ) );
                                      if( !isset($terms->errors) ){
                                        $finalArr['project_section']['projects'][$i]['terms'] = $terms;
                                    }
                                   
                                    
                                    $finalArr['project_section']['projects'][$i]['title'] = get_the_title();
                                    $finalArr['project_section']['projects'][$i]['content'] = get_the_content();
                                    $i++;
                                }
                            }
                            
                    //     }
                    //  }
        
        if( have_rows('business_model_section', $homepage_id) ){
            while( have_rows('business_model_section', $homepage_id) ){
                the_row();
                 $finalArr['business_model_section']['title'] = get_sub_field('business_model_main_title', $homepage_id);
                if( have_rows('business_model_repeater', $homepage_id) ){
                    $i=1;
                    while( have_rows('business_model_repeater', $homepage_id) ){
                        the_row();
                        // $json_url = get_sub_field('business_animation', $homepage_id);
                        // $json = file_get_contents($json_url);
                        // $finalArr['business_model'][$i]['animation'] = $json;
                        $finalArr['business_model'][$i]['title'] = get_sub_field('business_title', $homepage_id);
                        $finalArr['business_model'][$i]['content'] = get_sub_field('business_content', $homepage_id);
                        $finalArr['business_model'][$i]['buttontext'] = get_sub_field('business_button_text', $homepage_id);
                        $finalArr['business_model'][$i]['buttonlink'] = get_sub_field('business_button_link', $homepage_id);
                        $i++;
                    }
                }
            }
        }
       
      
                if( have_rows('acknowlegment_logos', $homepage_id) ){
                    $i=1;
                    while( have_rows('acknowlegment_logos', $homepage_id) ){
                        the_row();
                        
                        $finalArr['acknowlegment_section'][$i]['logo'] = get_sub_field('logos', $homepage_id);
                        $i++;
                    }
                }
       
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }