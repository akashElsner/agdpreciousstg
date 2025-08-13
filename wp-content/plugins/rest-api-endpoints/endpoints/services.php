<?php
 function rest_services_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 4180;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        if( have_rows('banner_section', $page_id) ){
            while( have_rows('banner_section', $page_id) ){
                the_row();
                $finalArr['banner']['image'] = get_sub_field('banner_image', $page_id);
                $finalArr['banner']['title'] = get_sub_field('banner_title', $page_id);
                $finalArr['banner']['content'] = get_sub_field('banner_content', $page_id);
                $finalArr['banner']['secondimage'] = get_sub_field('banner_second_image', $page_id);
                $finalArr['banner']['buttonlink'] = get_sub_field('banner_button_link', $page_id);
                $finalArr['banner']['buttontext'] = get_sub_field('banner_button_text', $page_id);
            }
        }
        
        if( have_rows('form_section', $page_id) ){
            while( have_rows('form_section', $page_id) ){
            the_row();
            $finalArr['form_section']['title'] = get_sub_field('form_section_title', $page_id);
            $finalArr['form_section']['content'] = get_sub_field('form_section_content', $page_id);
            $finalArr['form_section']['heading'] = get_sub_field('form_heading', $page_id);
                                    
            }
        }
        
        if( have_rows('solution_section', $page_id) ){
            while( have_rows('solution_section', $page_id) ){
            the_row();
            $finalArr['solution_section']['main_title'] = get_sub_field('solution_main_title', $page_id);
            if( have_rows('solution_repeater', $page_id) ){
             $k=1;
             while( have_rows('solution_repeater', $page_id) ){
             the_row();
               $finalArr['solution_section']['links'][$k]['title'] = get_sub_field('solution_title', $page_id);
               $finalArr['solution_section']['links'][$k]['image'] = get_sub_field('solution_image', $page_id);
               $finalArr['solution_section']['links'][$k]['content'] = get_sub_field('solution_content', $page_id);
               $k++;
             }
        }
            
                                    
        }
        }
        
        if( have_rows('services_section', $page_id) ){
            while( have_rows('services_section', $page_id) ){
            the_row();
            $finalArr['services_section']['main_title'] = get_sub_field('services_main_title', $page_id);
            if( have_rows('service_repeater', $page_id) ){
             $k=1;
             while( have_rows('service_repeater', $page_id) ){
             the_row();
               $finalArr['services_section']['links'][$k]['title'] = get_sub_field('service_title', $page_id);
               $finalArr['services_section']['links'][$k]['content'] = get_sub_field('service_content', $page_id);
               $k++;
             }
        }
            
                                    
        }
        }
        
        if( have_rows('why_choose_section', $page_id) ){
                     while( have_rows('why_choose_section', $page_id) ){
                        the_row();
                            $finalArr['why_choose_section']['title'] = get_sub_field('why_choose_section_title', $page_id);
                            $finalArr['why_choose_section']['content'] = get_sub_field('why_choose_section_content', $page_id);
                            $finalArr['why_choose_section']['image'] = get_sub_field('why_choose_section_image', $page_id);
                            $finalArr['why_choose_section']['buttontext'] = get_sub_field('why_choose_section_button_text', $page_id);
                            $finalArr['why_choose_section']['buttonlink'] = get_sub_field('why_choose_section_button_link', $page_id);
                                if( have_rows('counter_repeater', $page_id) ){
                                 $k=1;
                                 while( have_rows('counter_repeater', $page_id) ){
                                 the_row();
                                    $finalArr['why_choose_section']['links'][$k]['number'] = get_sub_field('counter_number', $page_id);
                                    $finalArr['why_choose_section']['links'][$k]['label'] = get_sub_field('counter_label', $page_id);
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
                                     $slug = basename(get_permalink());
                                    
                                    $portfolio_query->the_post();
                                     $postID = get_the_id();
                                     
                                    $finalArr['project_section']['projects'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                                    $finalArr['project_section']['projects'][$i]['link'] = get_the_permalink();
                                    $finalArr['project_section']['projects'][$i]['slug']  = $slug;
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
        
        $blog_args = array(
                        'posts_per_page'   => 2,
                        'post_type'        => 'post',
                              
                    );
        $blog_query = new WP_Query( $blog_args );

        if( have_rows('blog_section', $page_id) ){
                     while( have_rows('blog_section', $page_id) ){
                        the_row();
                        
                        if( $blog_query->have_posts() ){
                                $i=1;
                                while( $blog_query->have_posts() ){
                                    $slug = basename(get_permalink());
                                    $blog_query->the_post();
                                     $postID = get_the_id();
                                    $finalArr['blog_section']['blog'][$i]['id'] = get_the_id();
                                    $finalArr['blog_section']['blog'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                                    $finalArr['blog_section']['blog'][$i]['link'] = get_the_permalink();
                                    $finalArr['blog_section']['blog'][$i]['slug'] = $slug;
                                     $terms = wp_get_object_terms( $postID, 'category', array( 'fields' => 'names' ) );
                                      if( !isset($terms->errors) ){
                                        $finalArr['blog_section']['blog'][$i]['terms'] = $terms;
                                    }
                                   
                                    
                                    $finalArr['blog_section']['blog'][$i]['author'] = get_the_author();
                                    $finalArr['blog_section']['blog'][$i]['date'] = get_the_date();
                                    $finalArr['blog_section']['blog'][$i]['title'] = get_the_title();
                                    $finalArr['blog_section']['blog'][$i]['content'] = get_the_content();
                                    $i++;
                                }
                            wp_reset_query();
                            }
                            $finalArr['blog_section']['title'] = get_sub_field('blog_title', $page_id);
                            $finalArr['blog_section']['buttontext'] = get_sub_field('blog_button_text', $page_id);
                            $finalArr['blog_section']['buttonlink'] = get_sub_field('blog_button_link', $page_id);
                     }
        }
        
        if( have_rows('dedicated_section', $page_id) ){
                     while( have_rows('dedicated_section', $page_id) ){
                        the_row();
                            $json_url1 = get_field('dedicated_section_dedicated_animation', $page_id);
                            $json1 = file_get_contents($json_url1);
                            $finalArr['dedicated_section']['title'] = get_field('dedicated_section_dedicated_title', $page_id);
                            $finalArr['dedicated_section']['service_hours'] = get_field('dedicated_section_dedicated_hours', $page_id); 
                            $finalArr['dedicated_section']['service_experience'] = get_field('dedicated_section_dedicated_experience', $page_id);
                            $finalArr['dedicated_section']['animation'] = $json1;
                            $finalArr['dedicated_section']['content'] = get_field('dedicated_section_dedicated_content', $page_id);
                            $finalArr['dedicated_section']['buttontext'] = get_field('dedicated_section_dedicated_button_text', $page_id);
                            $finalArr['dedicated_section']['buttonlink'] = get_field('dedicated_section_dedicated_button_link', $page_id);
                     }
                }
        
        if( have_rows('solution_section', $page_id) ){
                     while( have_rows('solution_section', $page_id) ){
                        the_row();
                            $finalArr['solution_section']['title'] = get_sub_field('solution_main_title', $page_id);
                                if( have_rows('solution_repeater', $page_id) ){
                                 $k=1;
                                 while( have_rows('solution_repeater', $page_id) ){
                                 the_row();
                                 $json_url2 = get_sub_field('solution_image', $page_id);
                                 $json2 = file_get_contents($json_url2);
                                    $finalArr['solution_section']['links'][$k]['subtitle'] = get_sub_field('solution_title', $page_id);
                                    $finalArr['solution_section']['links'][$k]['animation'] = $json2;
                                    $finalArr['solution_section']['links'][$k]['content'] = get_sub_field('solution_content', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
        
        if( have_rows('faq_section', $page_id) ){
                     while( have_rows('faq_section', $page_id) ){
                        the_row();
                            $finalArr['faq_section']['title'] = get_sub_field('faq_title', $page_id);
                                if( have_rows('faq', $page_id) ){
                                 $k=1;
                                 while( have_rows('faq', $page_id) ){
                                 the_row();
                                    $finalArr['faq_section']['links'][$k]['question'] = get_sub_field('faq_question', $page_id);
                                    $finalArr['faq_section']['links'][$k]['answer'] = get_sub_field('faq_answer', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
        
        
        $json_url = get_field("get_in_touch_animation", $page_id);
    $json = file_get_contents($json_url);
    $finalArr["get_in_touch"]["animation"] = $json;
    $finalArr["get_in_touch"]["title"] = get_field(
        "get_in_touch_title",
        $page_id
    );
    $finalArr["get_in_touch"]["subtitle"] = get_field(
        "get_in_touch_subtitle",
        $page_id
    );
    $finalArr["get_in_touch"]["buttontext"] = get_field(
        "get_in_touch_button_text",
        $page_id
    );
    $finalArr["get_in_touch"]["buttonlink"] = get_field(
        "get_in_touch_button_link",
        $page_id
    );

        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
}

?>