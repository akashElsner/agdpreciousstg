<?php
    function rest_career_callback( $request ){
        $id = $request->get_param( 'id' );
         $page_id = 38;
        
        if( isset($id) && $id > 0 ){
            $page_id = $id;
        }
        
        $finalArr = array();
        $finalArr['page_id'] = $page_id;
        
        if( have_rows('banner_section', $page_id) ){
            // while( have_rows('banner_section', $page_id) ){
                // the_row();
                $finalArr['banner']['title'] = get_field('banner_section_banner_section_title', $page_id);
                $finalArr['banner']['subtitle'] = get_field('banner_section_banner_section_subtitle', $page_id);
                $finalArr['banner']['button_link'] = get_field('banner_section_banner_section_button_link', $page_id);
                $finalArr['banner']['button_text'] = get_field('banner_section_banner_section_button_text', $page_id);
                $finalArr['banner']['image'] = get_field('banner_section_banner_section_image', $page_id);
            // }
        }
        
        if( have_rows('were_hiring_section', $page_id) ){
            while( have_rows('were_hiring_section', $page_id) ){
                 the_row();
                $finalArr['were_hiring_section']['title'] = get_sub_field('were_hiring_title', $page_id);
                $finalArr['were_hiring_section']['content'] = get_sub_field('were_hiring_content', $page_id);
               
                if( have_rows('job_profile_repeater', $page_id) ){
                    $i=1;
                    while( have_rows('job_profile_repeater', $page_id) ){
                        the_row();
                        $finalArr['job_profile_section']['links'][$i]['title'] = get_sub_field('job_profile_title', $page_id);
                        $finalArr['job_profile_section']['links'][$i]['city'] = get_sub_field('job_profile_city', $page_id);
                        $finalArr['job_profile_section']['links'][$i]['buttontext'] = get_sub_field('apply_job_button', $page_id);
                        $finalArr['job_profile_section']['links'][$i]['buttonlink'] = get_sub_field('apply_job_button_link', $page_id);
                        $i++;
                    }
                }
            }
        }
        
         if( have_rows('work_hard_section', $page_id) ){
            while( have_rows('work_hard_section', $page_id) ){
                 the_row();
                $finalArr['work_hard_section']['title'] = get_sub_field('work_hard_title', $page_id);
                $finalArr['work_hard_section']['content'] = get_sub_field('work_hard_subtitle', $page_id);
               
                $images = get_sub_field('work_section_gallery');
                if( $images ): {
                    $i=1;
                    foreach( $images as $image ): 
                    $finalArr['work_hard_section']['links'][$i]['gallery'] = esc_url($image['url']); 
                    $i++;
                    endforeach; 
                }
                endif;
                $finalArr['work_hard_section']['buttontext'] = get_sub_field('work_button_text', $page_id);
                $finalArr['work_hard_section']['buttonlink'] = get_sub_field('work_button_link', $page_id);
            }
        }
       
        if( have_rows('perks_and_benefit_section', $page_id) ){
            while( have_rows('perks_and_benefit_section', $page_id) ){
                 the_row();
                $finalArr['perks_and_benefit_section']['title'] = get_sub_field('perks_and_benefit_title', $page_id);
                $finalArr['perks_and_benefit_section']['content'] = get_sub_field('perks_and_benefit_content', $page_id);
               
                if( have_rows('perks_and_benefit_repeater', $page_id) ){
                    $i=1;
                    while( have_rows('perks_and_benefit_repeater', $page_id) ){
                        the_row();
                        $finalArr['perks_and_benefit_section']['links'][$i]['icon'] = get_sub_field('perks_and_benefit_icon', $page_id);
                        $finalArr['perks_and_benefit_section']['links'][$i]['label'] = get_sub_field('perks_and_benefit_label', $page_id);
                        $i++;
                    }
                }
            }
        }
        
        if( have_rows('recruitment_section', $page_id) ){
            while( have_rows('recruitment_section', $page_id) ){
                 the_row();
                $finalArr['recruitment_section']['title'] = get_sub_field('recruitment_title', $page_id);
                $finalArr['recruitment_section']['content'] = get_sub_field('recruitment_content', $page_id);
               
                if( have_rows('recruitment_repeater', $page_id) ){
                    $i=1;
                    while( have_rows('recruitment_repeater', $page_id) ){
                        the_row();
                        $finalArr['recruitment_section']['links'][$i]['label'] = get_sub_field('recruitment_label', $page_id);
                        $finalArr['recruitment_section']['links'][$i]['process'] = get_sub_field('recruitment_process', $page_id);
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
        
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }
    
?>