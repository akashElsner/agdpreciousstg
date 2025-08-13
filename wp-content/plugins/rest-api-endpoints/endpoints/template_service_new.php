<?php
    function rest_template_service_new_callback( $request ){
        $id = $request->get_param( 'id' );
        $page_id = 0;
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $finalArr = array();
        
        if( $page_id > 0 ){
            $template_slug = get_page_template_slug($page_id);
            $temp_template_slug = explode('/', $template_slug);
            
            if( isset($temp_template_slug[1]) && $temp_template_slug[1] == 'template-service-new.php' ){
                if( have_rows('service_banner_section', $page_id) ){
                    while( have_rows('service_banner_section', $page_id) ){
                        the_row();
                        $finalArr['banner']['image'] = get_sub_field('service_image', $page_id);
                        $finalArr['banner']['title'] = get_sub_field('service_banner_title', $page_id);
                        $finalArr['banner']['content'] = get_sub_field('service_banner_content', $page_id);
                        $finalArr['banner']['button_text'] = get_sub_field('service_banner_button_text', $page_id);
                    }
                }
                
                if( have_rows('service_contact_section', $page_id) ){
                    while( have_rows('service_contact_section', $page_id) ){
                        the_row();
                        $finalArr['service_contact_section']['title'] = get_sub_field('service_left_side_title', $page_id);
                        $finalArr['service_contact_section']['content'] = get_sub_field('service_left_side_title', $page_id);
                        $finalArr['service_contact_section']['button_text'] = get_sub_field('service_left_side_button', $page_id);
                        $finalArr['service_contact_section']['form'] = '';
                    }
                }
                
                if( have_rows('service_third_section', $page_id) ){
                    while( have_rows('service_third_section', $page_id) ){
                        the_row();
                        $finalArr['service_third_section']['title'] = get_sub_field('title_1', $page_id);
                        
                        if( have_rows('icon_title_section', $page_id) ){
                            $j=1;
                            while( have_rows('icon_title_section', $page_id) ){
                                the_row();
                                $finalArr['service_third_section']['services'][$j]['image'] = get_sub_field('third_section_icon', $page_id);
                                $finalArr['service_third_section']['services'][$j]['title'] = get_sub_field('third_section_title', $page_id);
                                $j++;
                            }
                        }
                        
                        $i++;
                    }
                }
                
                if( have_rows('service_fourth_section', $page_id) ){
                    while( have_rows('service_fourth_section', $page_id) ){
                        the_row();
                        $finalArr['service_fourth_section']['title'] = get_sub_field('fourth_section_title_1', $page_id);
                        
                        if( have_rows('fourth_section_icon_title_section', $page_id) ){
                            $j=1;
                            while( have_rows('fourth_section_icon_title_section', $page_id) ){
                                the_row();
                                $finalArr['service_fourth_section']['services'][$j]['image'] = get_sub_field('fourth_section_icon', $page_id);
                                $finalArr['service_fourth_section']['services'][$j]['title'] = get_sub_field('fourth_section_title', $page_id);
                                $finalArr['service_fourth_section']['services'][$j]['content'] = get_sub_field('fourth_section_content', $page_id);
                                $j++;
                            }
                        }
                        
                        $finalArr['service_fourth_section']['button_text'] = get_sub_field('fourth_section_button', $page_id);
                    }
                }
            }
        }
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }