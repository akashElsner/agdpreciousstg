<?php
function rest_ecommerce_solutions_callback($request)
{
    $id = $request->get_param("id");
    $page_id = 25213;

    if (isset($id) && $id > 0) {
        $page_id = $id;
    }
    $finalArr = [];
    $finalArr["page_id"] = $page_id;

    if (have_rows("banner_section", $page_id)) {
        $finalArr["banner"]["image"] = get_field(
            "banner_section_main_banner_image",
            $page_id
        );
        $finalArr["banner"]["title"] = get_field(
            "banner_section_main_banner_title",
            $page_id
        );
        $finalArr["banner"]["content"] = get_field(
            "banner_section_main_banner_content",
            $page_id
        );
        $finalArr["banner"]["buttonlink"] = get_field(
            "banner_section_main_banner_button_link",
            $page_id
        );
        $finalArr["banner"]["buttontext"] = get_field(
            "banner_section_main_banner_button_text",
            $page_id
        );
        if (have_rows("banner_section_banner_slider", $page_id)) {
            $i = 1;
            while (have_rows("banner_section_banner_slider", $page_id)) {
                the_row();
                $finalArr["banner"]["links"][$i][
                    "banner_slider_image"
                ] = get_sub_field("banner_slider_images", $page_id);
                $i++;
            }
        }
    }
    if (have_rows("form_section", $page_id)) {
        while (have_rows("form_section", $page_id)) {
            the_row();
            $finalArr["form_section"]["title"] = get_sub_field(
                "form_section_title",
                $page_id
            );
            $finalArr["form_section"]["content"] = get_sub_field(
                "form_section_content",
                $page_id
            );
            $finalArr["form_section"]["heading"] = get_sub_field(
                "form_heading",
                $page_id
            );
        }
    }

    if (have_rows("services_section", $page_id)) {
        while (have_rows("services_section", $page_id)) {
            the_row();
            $finalArr["services_section"]["main_title"] = get_sub_field(
                "services_main_title",
                $page_id
            );
            if (have_rows("service_repeater", $page_id)) {
                $k = 1;
                while (have_rows("service_repeater", $page_id)) {
                    the_row();
                    $finalArr["services_section"]["links"][$k][
                        "title"
                    ] = get_sub_field("service_title", $page_id);
                    $finalArr["services_section"]["links"][$k][
                        "content"
                    ] = get_sub_field("service_content", $page_id);
                    $k++;
                }
            }
        }
    }
    
    $technology_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'technologies',
        );
        $$technology_query = new WP_Query( $technology_args );
        
        if( $$technology_query->have_posts() ){
            $i=1;
            while( $$technology_query->have_posts() ){
                $$technology_query->the_post();
                $finalArr['technology'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                // $finalArr[$i]['link'] = get_the_permalink();
                
                $terms = get_the_terms( get_the_ID() , array( 'technologies_categories') );
                
                if( !isset($terms->errors) ){
                    $finalArr['technology'][$i]['terms'] = $terms;
                }
                $finalArr['technology'][$i]['title'] = get_the_title();
                $i++;
            }
        }

    if (have_rows("why_choose_section", $page_id)) {
        while (have_rows("why_choose_section", $page_id)) {
            the_row();
            $finalArr["why_choose_section"]["title"] = get_sub_field("why_choose_section_title",$page_id);
            $finalArr["why_choose_section"]["content"] = get_sub_field("why_choose_section_content",$page_id);
            $finalArr["why_choose_section"]["image"] = get_sub_field("why_choose_section_image",$page_id);
            $finalArr["why_choose_section"]["buttontext"] = get_sub_field("why_choose_section_button_text",$page_id);
            $finalArr["why_choose_section"]["buttonlink"] = get_sub_field("why_choose_section_button_link",$page_id);
            if (have_rows("counter_repeater", $page_id)) {
                $k = 1;
                while (have_rows("counter_repeater", $page_id)) {
                    the_row();
                    $finalArr["why_choose_section"]["links"][$k]["number"] = get_sub_field("counter_number", $page_id);
                    $finalArr["why_choose_section"]["links"][$k]["label"] = get_sub_field("counter_label", $page_id);
                    $k++;
                }
            }
        }
    }
    
    if (have_rows("ecommerce_project_section", $page_id)) {
        while (have_rows("ecommerce_project_section", $page_id)) {
            the_row();
            $finalArr["ecommerce_project_section"]["title"] = get_sub_field("ecommerce_project_title",$page_id);
            $finalArr["ecommerce_project_section"]["content"] = get_sub_field("ecommerce_project_content",$page_id);
            // $finalArr["ecommerce_project_section"]["image"] = get_sub_field("ecommerce_project_image",$page_id); 
            if (have_rows("ecommerce_project_repeater", $page_id)) {
                $k = 1;
                while (have_rows("ecommerce_project_repeater", $page_id)) {
                    the_row();
                    $finalArr["ecommerce_project_section"]["links"][$k]["icon"] = get_sub_field("ecommerce_project_icon", $page_id);
                    $finalArr["ecommerce_project_section"]["links"][$k]["title"] = get_sub_field("ecommerce_project_title", $page_id);
                    $k++;
                }
            }
        }
    }
        
    
    $finalArr["industries_serving_section"]["title"] = get_field(
        "industries_serving_title",
        "option"
    );
    $finalArr["industries_serving_section"]["subtitle"] = get_field(
        "industries_serving_subtitle",
        "option"
    );
    if (have_rows("industries_serving_repeater", "option")) {
        $i = 1;
        while (have_rows("industries_serving_repeater", "option")) {
            the_row();
            $finalArr["industries_serving_section"]["links"][$i][
                "image"
            ] = get_sub_field("industries_serving_image", "option");
            $finalArr["industries_serving_section"]["links"][$i][
                "name"
            ] = get_sub_field("industries_serving_name", "option");
            $i++;
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
                                    $finalArr['blog_section']['blog'][$i]['thumbnail'] = get_the_post_thumbnail_url();
                                    $finalArr['blog_section']['blog'][$i]['link'] = get_the_permalink();
                                    $finalArr['blog_section']['blog'][$i]['date'] = get_the_date();
                                    $finalArr['blog_section']['blog'][$i]['slug'] = $slug;
                                    $finalArr['blog_section']['blog'][$i]['author'] = get_the_author();
                                      
                                     $terms = wp_get_object_terms( $postID, 'category', array( 'fields' => 'names' ) );
                                      if( !isset($terms->errors) ){
                                        $finalArr['blog_section']['blog'][$i]['terms'] = $terms;
                                    }
                                   
                                    
                                    
                                   
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
    
    $finalArr["hire_faq_section"]["faqtitle"] = get_field(
        "faq_title",
        $page_id
    );

    if (have_rows("faq_repeater", $page_id)) {
        $i = 1;
        while (have_rows("faq_repeater", $page_id)) {
            the_row();
            $finalArr["hire_faq_section"]["links"][$i][
                "question"
            ] = get_sub_field("faq_question", $page_id);
            $finalArr["hire_faq_section"]["links"][$i][
                "answer"
            ] = get_sub_field("faq_answer", $page_id);
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
?>
