<?php
    function rest_seo_packages_callback( $request ){
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
            // echo $template_slug;
            $temp_template_slug = explode('/', $template_slug);
            $finalArr['page_id'] = $page_id;
            if( isset($temp_template_slug[1]) && $temp_template_slug[1] == 'new-packages.php' ){
                 if( have_rows('banner_section', $page_id) ){
            while( have_rows('banner_section', $page_id) ){
                 the_row();
                $finalArr['seopackages']['banner']['bannertitle'] = get_field('banner_section_banner_title', $page_id);
                $finalArr['seopackages']['banner']['bannersubtitle'] = get_field('banner_section_banner_subtitle', $page_id);
                $finalArr['seopackages']['banner']['bannercontent'] = get_field('banner_section_banner_content', $page_id);
             }
        }
        if( have_rows('tab_section', $page_id) ){
            while( have_rows('tab_section', $page_id) ){
                 the_row();
                if( have_rows('tab_repeater', $page_id) ){
                    $i=1;
                    while( have_rows('tab_repeater', $page_id) ){
                        the_row();
                        $finalArr['tab_section']['links'][$i]['tab_options'] = get_sub_field('tab_options', $page_id);
                        $i++;
                    }
                }
            }
        }
        if( have_rows('form_section', $page_id) ){
            while( have_rows('form_section', $page_id) ){
                 the_row();
                $finalArr['form_section']['title'] = get_sub_field('form_title', $page_id);
                $finalArr['form_section']['content'] = get_sub_field('form_subtitle', $page_id);
            }
        }
        
        if( have_rows('package_section', $page_id) ){
            while( have_rows('package_section', $page_id) ){
                 the_row();
              
                if( have_rows('package_repeater', $page_id) ){
                    $i=1;
                    while( have_rows('package_repeater', $page_id) ){
                        the_row();
                        $finalArr['package_section']['links'][$i]['icon'] = get_sub_field('package_icon', $page_id);
                        $finalArr['package_section']['links'][$i]['name'] = get_sub_field('package_name', $page_id);
                        $finalArr['package_section']['links'][$i]['get_in_touch'] = get_sub_field('get_in_touch', $page_id);
                            if( have_rows('package_repeater1', $page_id) ){
                            $j=1;
                            while( have_rows('package_repeater1', $page_id) ){
                                the_row();
                                $finalArr['package_section']['links'][$i]['Heading'][$j]['package_heading'] = get_sub_field('package_heading', $page_id);
                                 if( have_rows('package_repeater_2', $page_id) ){
                                    $k=1;
                                    while( have_rows('package_repeater_2', $page_id) ){
                                        the_row();
                                    $finalArr['package_section']['links'][$i]['Heading'][$j]['Keywords'][$k]['package_keywords'] = get_sub_field('package_keywords', $page_id);
                                    
                                    $k++;
                                    }
                                }
                            $j++;
                            }
                        }
                        $i++;
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

    