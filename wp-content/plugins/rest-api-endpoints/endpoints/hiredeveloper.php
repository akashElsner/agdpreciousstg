<?php
    function rest_hiredeveloper_callback( $request ){
        $id = $request->get_param( 'id' );
         $page_id = 24996;
        
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        //echo $page_id;
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
       // $finalArr['page_slug'] = get_the_slug($page_id);
        
        if( have_rows('hire_banner_section', $page_id) ){
            while( have_rows('hire_banner_section', $page_id) ){
                the_row();
                $finalArr['banner']['image'] = get_sub_field('hire_banner_section_image', $page_id);
                $finalArr['banner']['title'] = get_sub_field('hire_banner_title', $page_id);
                $finalArr['banner']['content'] = get_sub_field('hire_banner_content', $page_id);
                $finalArr['banner']['buttontext'] = get_sub_field('hire_banner_button_', $page_id);
                $finalArr['banner']['buttonlink'] = get_sub_field('hire_button_link', $page_id);
                $finalArr['secondbanner']['contacttext'] = get_sub_field('hire_contact_form_text', $page_id);
                $finalArr['secondbanner']['contactform'] = get_sub_field('hire_contact_form_content', $page_id);

               

                if( have_rows('hire_third_section', $page_id) ){
                     while( have_rows('hire_third_section', $page_id) ){
                        the_row();
                            $finalArr['secondbanner']['title'] = get_sub_field('hire_third_section_title', $page_id);
                            $finalArr['secondbanner']['content'] = get_sub_field('hire_third_section_content', $page_id);
                     }
                }
                
                if( have_rows('hire_development_section', $page_id) ){
                     while( have_rows('hire_development_section', $page_id) ){
                        the_row();
                            $finalArr['expertizesection']['title'] = get_sub_field('development_main_title', $page_id);
                                if( have_rows('development_section_services', $page_id) ){
                                 $k=1;
                                 while( have_rows('development_section_services', $page_id) ){
                                 the_row();
                                    $finalArr['expertizesection']['links'][$k]['title'] = get_sub_field('development_title', $page_id);
                                    $finalArr['expertizesection']['links'][$k]['content'] = get_sub_field('development_content', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
                if( have_rows('hire_developer_section', $page_id) ){
                     while( have_rows('hire_developer_section', $page_id) ){
                        the_row();
                            $finalArr['hiredevelopersection']['title'] = get_sub_field('hire_developer_title', $page_id);
                            $finalArr['hiredevelopersection']['content'] = get_sub_field('hire_developer_content', $page_id);
                            $finalArr['hiredevelopersection']['image'] = get_sub_field('hire_developer_right_image', $page_id);
                                if( have_rows('hire_developer_icon_title', $page_id) ){
                                 $k=1;
                                 while( have_rows('hire_developer_icon_title', $page_id) ){
                                 the_row();
                                    $finalArr['hiredevelopersection']['links'][$k]['title'] = get_sub_field('hire_developer_title Api', $page_id);
                                    $finalArr['hiredevelopersection']['links'][$k]['content'] = get_sub_field('hire_developer_content', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
                if( have_rows('clear_pricing_section', $page_id) ){
                     while( have_rows('clear_pricing_section', $page_id) ){
                        the_row();
                            $finalArr['clear_pricing_section']['title'] = get_sub_field('clear_pricing_text', $page_id);
                            $finalArr['clear_pricing_section']['content'] = get_sub_field('clear_pricing_content', $page_id);
                            $finalArr['clear_pricing_section']['image'] = get_sub_field('clear_pricing_image', $page_id);
                            $finalArr['clear_pricing_section']['buttontext'] = get_sub_field('clear_pricing_button', $page_id);
                            $finalArr['clear_pricing_section']['buttonlink'] = get_sub_field('clear_pricing_button_link', $page_id);
                     }
                }
                
                if( have_rows('hiring_process_section', $page_id) ){
                     while( have_rows('hiring_process_section', $page_id) ){
                        the_row();
                            $finalArr['hire_process_section']['title'] = get_sub_field('hiring_process_title', $page_id);
                            $finalArr['hire_process_section']['content'] = get_sub_field('hiring_process_text', $page_id);
                        
                                if( have_rows('hiring_process_repeat', $page_id) ){
                                 $k=1;
                                 while( have_rows('hiring_process_repeat', $page_id) ){
                                 the_row();
                                    $finalArr['hire_process_section']['links'][$k]['title'] = get_sub_field('hiring_process_repeat_title', $page_id);
                                    $finalArr['hire_process_section']['links'][$k]['content'] = get_sub_field('hiring_process_repeat_text', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
                    $portfolio_args = array(
                        'posts_per_page'   => 2,
                        'post_type'        => 'portfolio',
                        
                        
                    );
                    $portfolio_query = new WP_Query( $portfolio_args );
                    
                   
                    
                     if( have_rows('recent_project_section', $page_id) ){
                         while( have_rows('recent_project_section', $page_id) ){
                             the_row();
                           $finalArr['recent_project_section']['title'] = get_sub_field('recent_project_text', $page_id);
                           $finalArr['recent_project_section']['allproject'] = get_sub_field('all__project_text', $page_id);
                            $finalArr['recent_project_section']['allprojectlink'] = get_sub_field('all__project_link', $page_id);
                            if( $portfolio_query->have_posts() ){
                                $i=1;
                                while( $portfolio_query->have_posts() ){
                                    
                                    $portfolio_query->the_post();
                                     $postID = get_the_id();
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
                            
                        }
                     }
                
                if( have_rows('hire_faq_section', $page_id) ){
                     while( have_rows('hire_faq_section', $page_id) ){
                        the_row();
                            $finalArr['hire_faq_section']['title'] = get_sub_field('faq_title', $page_id);
                                if( have_rows('faq', $page_id) ){
                                 $k=1;
                                 while( have_rows('faq', $page_id) ){
                                 the_row();
                                    $finalArr['hire_faq_section']['links'][$k]['question'] = get_sub_field('faq_question', $page_id);
                                    $finalArr['hire_faq_section']['links'][$k]['answer'] = get_sub_field('faq_answer', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
                if( have_rows('counter_section', $page_id) ){
                    $j=1;
                    while( have_rows('counter_section', $page_id) ){
                        the_row();
                        $finalArr['banner']['counter'][$j]['number'] = get_sub_field('counter_number', $page_id);
                        $finalArr['banner']['counter'][$j]['title'] = get_sub_field('counter_title', $page_id);
                        $j++;
                    }
                }
                
            }
        }
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
    
?>