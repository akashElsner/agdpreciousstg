<?php
    function rest_ecomva_callback( $request ){
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
            // echo $temp_template_slug;
            $finalArr['page_id'] = $page_id;
            if( isset($temp_template_slug[1]) && $temp_template_slug[1] == 'ecomva.php' ){
            if( have_rows('header', $page_id) ){
            while( have_rows('header', $page_id) ){
                the_row();
                $finalArr['banner']['image'] = get_sub_field('ecomva_banner_image', $page_id);
                $finalArr['banner']['content'] = get_sub_field('ecomva_header_content', $page_id);
                if( have_rows('ecomva_header_logos', $page_id) ){
                 $k=1;
                 while( have_rows('ecomva_header_logos', $page_id) ){
                 the_row();
                  $finalArr['banner']['links'][$k]['logo'] = get_sub_field('ecomva_logo', $page_id);
                  $k++;
                 }
            }
            }
        }
        
        if( have_rows('service_section', $page_id) ){
            while( have_rows('service_section', $page_id) ){
            the_row();
            $finalArr['service_section']['title'] = get_sub_field('ss_main_title', $page_id);
            $finalArr['service_section']['subtitle'] = get_sub_field('ss_sub_title', $page_id);
            $finalArr['service_section']['content'] = get_sub_field('service_content', $page_id);
            $finalArr['service_section']['image'] = get_sub_field('service_image', $page_id);
                                    
            }
        }
        
        
         if( have_rows('benefit', $page_id) ){
            while( have_rows('benefit', $page_id) ){
            the_row();
            $finalArr['benefit_section']['main_title'] = get_sub_field('bs_main_title', $page_id);
            $finalArr['benefit_section']['subitle'] = get_sub_field('bs_sub_title', $page_id);
            if( have_rows('bs_content', $page_id) ){
             $k=1;
             while( have_rows('bs_content', $page_id) ){
             the_row();
              $finalArr['benefit_section']['links'][$k]['title'] = get_sub_field('bs_title', $page_id);
              $finalArr['benefit_section']['links'][$k]['image'] = get_sub_field('bs_icon', $page_id);
              $finalArr['benefit_section']['links'][$k]['content'] = get_sub_field('bs_description', $page_id);
              $k++;
             }
        }
            
                                    
        }
        }
        
        if( have_rows('offers_section', $page_id) ){
            while( have_rows('offers_section', $page_id) ){
            the_row();
            $finalArr['offers_section']['main_title'] = get_sub_field('main_title', $page_id);
            $finalArr['offers_section']['subitle'] = get_sub_field('subitle', $page_id);
            if( have_rows('offers_items', $page_id) ){
             $k=1;
             while( have_rows('offers_items', $page_id) ){
             the_row();
              $finalArr['offers_section']['links'][$k]['title'] = get_sub_field('offer_title', $page_id);
              $finalArr['offers_section']['links'][$k]['image'] = get_sub_field('offer_image', $page_id);
              $finalArr['offers_section']['links'][$k]['content'] = get_sub_field('offer_content', $page_id);
              $k++;
             }
        }
            
                                    
        }
        }
        
        if( have_rows('mobile_app_features', $page_id) ){
            while( have_rows('mobile_app_features', $page_id) ){
            the_row();
            $finalArr['mobile_app_features']['main_title'] = get_sub_field('maf_main_title', $page_id);
            $finalArr['mobile_app_features']['sub_title'] = get_sub_field('maf_sub_title', $page_id);
            $finalArr['mobile_app_features']['image'] = get_sub_field('maf_image', $page_id);
            $finalArr['mobile_app_features']['content'] = get_sub_field('maf_content', $page_id);                        
           }
        }
        
        if( have_rows('learn_more_section', $page_id) ){
                     while( have_rows('learn_more_section', $page_id) ){
                        the_row();
                            $finalArr['learn_more_section']['title'] = get_sub_field('lms_title', $page_id);
                            $finalArr['learn_more_section']['subtitle'] = get_sub_field('lms_sub_title', $page_id);
                            $finalArr['learn_more_section']['category'] = get_sub_field('select_post_category', $page_id);
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
        
        if( have_rows('company_logo_section', $page_id) ){
                     while( have_rows('company_logo_section', $page_id) ){
                        the_row();
                            $finalArr['company_logo_section']['title'] = get_sub_field('cls_main_title', $page_id);
                            $finalArr['company_logo_section']['subtitle'] = get_sub_field('cls_sub_title', $page_id);
                                if( have_rows('cls_logos', $page_id) ){
                                 $k=1;
                                 while( have_rows('cls_logos', $page_id) ){
                                 the_row();
                                    $finalArr['company_logo_section']['links'][$k]['image'] = get_sub_field('cls_image', $page_id);
                                    $k++;
                                }
                            }
                     }
                }
        
        if( have_rows('faq_section', $page_id) ){
                     while( have_rows('faq_section', $page_id) ){
                        the_row();
                            $finalArr['faq_section']['title'] = get_sub_field('faq_main_title', $page_id);
                                if( have_rows('faq_content', $page_id) ){
                                 $k=1;
                                 while( have_rows('faq_content', $page_id) ){
                                 the_row();
                                    $finalArr['faq_section']['links'][$k]['question'] = get_sub_field('question', $page_id);
                                    $finalArr['faq_section']['links'][$k]['answer'] = get_sub_field('answer', $page_id);
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
            }
        }
    
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }