<?php
    function rest_default_page_callback( $request ){
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
            // $template_slug = get_page_template_slug($page_id);
            // // echo $template_slug;
            // $temp_template_slug = explode('/', $template_slug);
            $finalArr['page_id'] = $page_id;
            // if( !empty($temp_template_slug[1]) && $temp_template_slug[1] == 'full-width-template.php' ){
                $finalArr['pages']['link'] = get_the_permalink($page_id);
                $finalArr['pages']['title'] = get_the_title($page_id);
                
                $postsss= get_post($page_id);
                $page_content = $postsss->post_content ;
                $page_content = apply_filters('the_content', $page_content);
                
                $finalArr['pages']['content'] = $page_content;
               
            // }
        }
        
        
        if( have_rows('industry_banner_section', $page_id) ){
            while( have_rows('industry_banner_section', $page_id) ){
                the_row();
                $finalArr['banner']['title'] = get_sub_field('banner_title', $page_id);
                $finalArr['banner']['subtitle'] = get_sub_field('banner_subtitle', $page_id);
                $finalArr['banner']['button_link'] = get_sub_field('banner_button_link', $page_id);
                $finalArr['banner']['button_text'] = get_sub_field('banner_button_text', $page_id);
                $finalArr['banner']['image'] = get_sub_field('banner_image', $page_id);
                
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
        
        if( have_rows('healthcare_software_solution', $page_id) ){
            while( have_rows('healthcare_software_solution', $page_id) ){
            the_row();
                $finalArr['healthcare_software_solution']['main_title'] = get_sub_field('healthcare_software_solution_title', $page_id);
                $finalArr['healthcare_software_solution']['main_content'] = get_sub_field('healthcare_software_solution_content', $page_id);
                if( have_rows('healthcare_software_solution_repeater', $page_id) ){
                 $k=1;
                while( have_rows('healthcare_software_solution_repeater', $page_id) ){
                the_row();
                $finalArr['healthcare_software_solution']['links'][$k]['title'] = get_sub_field('healthcare_title', $page_id);
                $finalArr['healthcare_software_solution']['links'][$k]['content'] = get_sub_field('healthcare_content', $page_id);
                // $finalArr['healthcare_software_solution']['links'][$k]['content'] = get_sub_field('solution_content', $page_id);
                $k++;
                }
            }
            
          }
        }
        
        if( have_rows('grow_sales_section', $page_id) ){
            while( have_rows('grow_sales_section', $page_id) ){
            the_row();
                $json_url1 = get_sub_field('grow_sales_animation', $page_id);
                $json1 = file_get_contents($json_url1);
                $finalArr['grow_sales_section']['animation'] = $json1;
                $finalArr['grow_sales_section']['buttontext'] = get_sub_field('grow_sales_button_text', $page_id);
                $finalArr['grow_sales_section']['buttonlink'] = get_sub_field('grow_sales_button_link', $page_id);
                if( have_rows('grow_sales_repeater', $page_id) ){
                 $k=1;
                while( have_rows('grow_sales_repeater', $page_id) ){
                the_row();
                $finalArr['grow_sales_section']['links'][$k]['icon'] = get_sub_field('grow_sales_icon', $page_id);
                $finalArr['grow_sales_section']['links'][$k]['title'] = get_sub_field('title', $page_id);
                $finalArr['grow_sales_section']['links'][$k]['content'] = get_sub_field('content', $page_id);
                $k++;
                }
            }
            
          }
        }
        
        if( have_rows('why_elsner_section', $page_id) ){
            while( have_rows('why_elsner_section', $page_id) ){
            the_row();
                $finalArr['why_elsner_section']['main_title'] = get_sub_field('why_elsner_section_title', $page_id);
                $finalArr['why_elsner_section']['main_content'] = get_sub_field('why_elsner_section_content', $page_id);
                if( have_rows('why_elsner_section_repeater', $page_id) ){
                 $k=1;
                while( have_rows('why_elsner_section_repeater', $page_id) ){
                the_row();
                $finalArr['why_elsner_section']['links'][$k]['icon'] = get_sub_field('why_elsner_icon', $page_id);
                $finalArr['why_elsner_section']['links'][$k]['title'] = get_sub_field('why_elsner_title', $page_id);
                $finalArr['why_elsner_section']['links'][$k]['content'] = get_sub_field('why_elsner_content', $page_id);
                $k++;
                }
            }
            
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
        if( have_rows('counter_section_class', $page_id) ){
            while( have_rows('counter_section_class', $page_id) ){
                the_row();
                if( have_rows('counter_repeat', $page_id) ){
                    $i=1;
                    while( have_rows('counter_repeat', $page_id) ){
                        the_row();
                        $finalArr['counter_section'][$i]['number'] = get_sub_field('counter_number', $page_id);
                        $finalArr['counter_section'][$i]['title'] = get_sub_field('counter_title', $page_id);
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

        $finalArr["hire_faq_section"]["faqtitle"] = get_field("faq_title",$page_id);

        if (have_rows("faq_repeater", $page_id)) {
            $i = 1;
            while (have_rows("faq_repeater", $page_id)) {
                the_row();
                $finalArr["hire_faq_section"]["links"][$i]["question"] = get_sub_field("faq_question", $page_id);
                $finalArr["hire_faq_section"]["links"][$i]["answer"] = get_sub_field("faq_answer", $page_id);
                $i++;
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