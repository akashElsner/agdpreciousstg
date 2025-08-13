<?php
    function rest_template_services_list_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_slug = $request->get_param( 'slug' );
        
        $args = array(
          'p'         => $id, // ID of a page, post, or custom type
          'post_type' => 'page'
        );
        
        if( $page_slug && !empty($page_slug) ){
            $args = array();
            $args = array(
              'name'         => $page_slug, // slug of a page, post, or custom type
              'post_type' => 'page'
            );
        }
        
        $page_id = 0;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $the_query = get_posts( $args );
        
        if( $the_query && $the_query[0] && $the_query[0]->ID ){
            $page_id = $the_query[0]->ID;
        }
        
        $finalArr = array();
        
        if( $page_id > 0 ){
            $template_slug = get_page_template_slug($page_id);
            $temp_template_slug = explode('/', $template_slug);
            $finalArr['page_id'] = $page_id;
            if( isset($temp_template_slug[1]) && $temp_template_slug[1] == 'services-list.php' ){
            if( have_rows('banner_section', $page_id) ){
            while( have_rows('banner_section', $page_id) ){
                the_row();
                $finalArr['banner']['image'] = get_sub_field('banner_image', $page_id);
                $finalArr['banner']['backgroundimage'] = get_sub_field('banner_background_image', $page_id);
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
                            $sluglink = get_sub_field('all__project_link', $page_id);
                            $finalArr['recent_project_section']['slug'] = basename($sluglink);
                            
                            if( $portfolio_query->have_posts() ){
                                $i=1;
                                while( $portfolio_query->have_posts() ){
                                    
                                    $portfolio_query->the_post();
                                     $postID = get_the_id();
                                      $slug = basename(get_permalink());
                                    $finalArr['project_section']['projects'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                                    $finalArr['project_section']['projects'][$i]['link'] = get_the_permalink();
                                    $finalArr['project_section']['projects'][$i]['slug'] = $slug;
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
        


        //blog section
                     
         if( have_rows('blog_section', $page_id) ){
             while( have_rows('blog_section', $page_id) ){
                the_row();

                $catId = get_sub_field('blog_category',$page_id);
            }
        }
        
        if(!empty($catId)){
            $blog_args = array(
                        'posts_per_page'   => 2,
                        'post_type'        => 'post',
                        'cat'=> $catId[0]
                    );
        } else {
            $blog_args = array(
                        'posts_per_page'   => 2,
                        'post_type'        => 'post',
                    );

        }
        
        $blog_query = new WP_Query( $blog_args );
        if( have_rows('blog_section', $page_id) ){
                     while( have_rows('blog_section', $page_id) ){
                        the_row();
                        
                        if( $blog_query->have_posts() ){
                                $i=1;
                                while( $blog_query->have_posts() ){
                                    
                                    $blog_query->the_post();
                                     $postID = get_the_id();
                                     $slug = basename(get_permalink());
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
                            $finalArr['dedicated_section']['hours'] = get_field('dedicated_section_dedicated_hours', $page_id); 
                            $finalArr['dedicated_section']['experience'] = get_field('dedicated_section_dedicated_experience', $page_id);
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
                
        if( have_rows('content_section', $page_id) ){
                     while( have_rows('content_section', $page_id) ){
                        the_row();
                            $finalArr['content_section']['title'] = get_sub_field('content_heading', $page_id);
                            $finalArr['content_section']['content'] = get_sub_field('content_description', $page_id);
                                if( have_rows('content_counter', $page_id) ){
                                 $k=1;
                                 while( have_rows('content_counter', $page_id) ){
                                 the_row();
                                    $finalArr['content_section']['links'][$k]['number'] = get_sub_field('content_number', $page_id);
                                    $finalArr['content_section']['links'][$k]['title'] = get_sub_field('content_title', $page_id);
                                    $finalArr['content_section']['links'][$k]['desc'] = get_sub_field('content_desc', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
        
        if( have_rows('migration_process_section', $page_id) ){
                     while( have_rows('migration_process_section', $page_id) ){
                        the_row();
                            $finalArr['migration_process_section']['title'] = get_sub_field('migration_process_title', $page_id);
                            $finalArr['migration_process_section']['image'] = get_sub_field('migration_process_image', $page_id);
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
            }
        }
        
    if( have_rows('quality_banner_section', $page_id) ){
        while( have_rows('quality_banner_section', $page_id) ){
                        the_row();

                $finalArr['quality_banner']['image'] = get_sub_field('quality_image', $page_id);
               
                $finalArr['quality_banner']['title'] = get_sub_field('quality_banner_title', $page_id);
                $finalArr['quality_banner']['content'] = get_sub_field('quality_banner_content', $page_id);
                
                $finalArr['quality_banner']['buttontext'] = get_sub_field('quality_banner_button_text', $page_id);
                     }
    }
    
   
    if( have_rows('quality_contact_section', $page_id) ){
        while( have_rows('quality_contact_section', $page_id) ){
                        the_row();
               
                
               
                $finalArr['contact_section']['title'] = get_sub_field('quality_left_side_title', $page_id);
                $finalArr['contact_section']['content'] = get_sub_field('quality_left_side_content', $page_id);
                $finalArr['contact_section']['buttontext'] = get_sub_field('quality_left_side_button', $page_id);
                 $finalArr['contact_section']['buttonlink'] = get_sub_field('quality_left_side_button_link', $page_id);

        }
    }
    
    if( have_rows('quality_reason_to_choose_us_section', $page_id) ){
        while( have_rows('quality_reason_to_choose_us_section', $page_id) ){
                        the_row();
              $finalArr['quality_banner2']['title'] = get_sub_field('quality_reason_title', $page_id);
              $finalArr['quality_banner2']['image'] = get_sub_field('quality_reason_bulb_image', $page_id);
              if( have_rows('quality_reason_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('quality_reason_content', $page_id) ){
                                 the_row();
                                    $finalArr['quality_banner2']['links'][$k]['content'] = get_sub_field('reason_sub_content', $page_id);
                                   
                                    $k++;
                                }
                            }                  

        }
    }
        
        if( have_rows('quality_faq_section', $page_id) ){
                     while( have_rows('quality_faq_section', $page_id) ){
                        the_row();
                            $finalArr['faq_section']['title'] = get_sub_field('quality_faq_title', $page_id);
                                if( have_rows('quality_faq', $page_id) ){
                                 $k=1;
                                 while( have_rows('quality_faq', $page_id) ){
                                 the_row();
                                    $finalArr['faq_section']['links'][$k]['question'] = get_sub_field('qu_faq_question', $page_id);
                                    $finalArr['faq_section']['links'][$k]['answer'] = get_sub_field('qu_faq_answer', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
                
        if( have_rows('process_section', $page_id) ){
                     while( have_rows('process_section', $page_id) ){
                        the_row();
                            $finalArr['process_section']['title'] = get_sub_field('process_title', $page_id);
                            $finalArr['process_section']['image'] = get_sub_field('process_image', $page_id);
                            if( have_rows('process_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('process_content', $page_id) ){
                                 the_row();
                                    $finalArr['process_section']['links'][$k]['content'] = get_sub_field('process_title', $page_id);
                                   
                                    $k++;
                                }
                            }                  

                     }
                }
                
        if( have_rows('testing_section', $page_id) ){
                     while( have_rows('testing_section', $page_id) ){
                        the_row();
                            $finalArr['testing_section']['title'] = get_sub_field('testing_title', $page_id);
                            if( have_rows('testing_icon_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('testing_icon_content', $page_id) ){
                                 the_row();
                                    $finalArr['testing_section']['links'][$k]['image'] = get_sub_field('testing_image', $page_id);
                                    $finalArr['testing_section']['links'][$k]['sub_title'] = get_sub_field('testing_sub_title', $page_id);
                                    $k++;
                                }
                            }                  

                     }
                }
        if( have_rows('business_section', $page_id) ){
                     while( have_rows('business_section', $page_id) ){
                        the_row();
                            $finalArr['business_section']['title'] = get_sub_field('business_title', $page_id);
                            $finalArr['business_section']['content'] = get_sub_field('business_content', $page_id);
                            $finalArr['business_section']['image'] = get_sub_field('business_image', $page_id); 
                     }
                }
                
        if( have_rows('api_section', $page_id) ){
                     while( have_rows('api_section', $page_id) ){
                        the_row();
                            $finalArr['api_section']['title'] = get_sub_field('api_title', $page_id);
                            if( have_rows('api_icon_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('api_icon_content', $page_id) ){
                                 the_row();
                                    $finalArr['api_section']['links'][$k]['image'] = get_sub_field('api_image', $page_id);
                                    $finalArr['api_section']['links'][$k]['sub_title'] = get_sub_field('api_sub_title', $page_id);
                                    $k++;
                                }
                            }                  

                     }
                }
                
        if( have_rows('automation_section', $page_id) ){
                     while( have_rows('automation_section', $page_id) ){
                        the_row();
                            $finalArr['automation_section']['title1'] = get_sub_field('automation_title', $page_id);
                            $finalArr['automation_section']['title2'] = get_sub_field('automation_sub_title1', $page_id);
                            $finalArr['automation_section']['title3'] = get_sub_field('automation_sub_title2', $page_id);
                            $finalArr['automation_section']['title4'] = get_sub_field('automation_sub_title3', $page_id);
                            if( have_rows('automation_icon_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('automation_icon_content', $page_id) ){
                                 the_row();
                                    $finalArr['automation_section']['links'][$k]['image'] = get_sub_field('automation_image', $page_id);
                                    $finalArr['automation_section']['links'][$k]['sub_title1'] = get_sub_field('automation_sub_title2_content', $page_id);
                                    $finalArr['automation_section']['links'][$k]['sub_title2'] = get_sub_field('automation_sub_title3_content', $page_id);
                                    $k++;
                                }
                            }                  

                     }
                }
        
        
        if( have_rows('additional_seo_services', $page_id) ){
                     while( have_rows('additional_seo_services', $page_id) ){
                        the_row();
                            $finalArr['additional_seo_services']['title1'] = get_sub_field('additional_title', $page_id);
                            $finalArr['additional_seo_services']['content'] = get_sub_field('additional_content', $page_id);
                            if( have_rows('additional_repeater', $page_id) ){
                                 $k=1;
                                 while( have_rows('additional_repeater', $page_id) ){
                                 the_row();
                                    $finalArr['additional_seo_services']['links'][$k]['image'] = get_sub_field('additional_icon', $page_id);
                                    $finalArr['additional_seo_services']['links'][$k]['sub_title1'] = get_sub_field('icon_title', $page_id);
                                    $k++;
                                }
                            }                  

                     }
                }
                
                
        if( have_rows('plan_package_section', $page_id) ){
                     while( have_rows('plan_package_section', $page_id) ){
                        the_row();
                            $finalArr['plan_package_section']['title1'] = get_sub_field('plan_title', $page_id);
                            $finalArr['plan_package_section']['content'] = get_sub_field('plan_subtitle', $page_id);
                            if( have_rows('package_repeater', $page_id) ){
                                 $k=1;
                                 while( have_rows('package_repeater', $page_id) ){
                                 the_row();
                                    $finalArr['plan_package_section']['links'][$k]['heading'] = get_sub_field('package_heading', $page_id);
                                    $finalArr['plan_package_section']['links'][$k]['sub_heading'] = get_sub_field('package_subheading', $page_id);
                                    $finalArr['plan_package_section']['links'][$k]['description'] = get_sub_field('package_description', $page_id);
                                    $finalArr['plan_package_section']['links'][$k]['button_text'] = get_sub_field('package_button_text', $page_id);
                                    $k++;
                                }
                            }                  

                     }
                }
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }