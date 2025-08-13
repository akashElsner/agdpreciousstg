<?php
    function rest_all_page_ids_callback( $request ){
        global $rest_api_pages;
        $finalArr = array();
        $all_pages_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'page',
        );
        
        $all_pages_query = new WP_Query( $all_pages_args );
        
        if( $all_pages_query->have_posts() ){
            while( $all_pages_query->have_posts() ){
                $all_pages_query->the_post();
                $page_id = get_the_ID();
                $rest_api_pages[$page_id]['slug'] = basename(get_permalink($page_id));
                $rest_api_pages[$page_id]['name'] = get_the_title($page_id);
                $rest_api_pages[$page_id]['permalink'] = get_the_permalink($page_id);
                $rest_api_pages[$page_id]['template'] = 'default';
                if( !empty(get_page_template_slug($page_id)) ){
                    $template_slug = get_page_template_slug($page_id);
                    if( $template_slug == 'page-templates/full-width-template.php' ){
                        $rest_api_pages[$page_id]['template'] = 'default';
                    }else{
                        $temp_template_slug = explode('/', $template_slug);
                        $rest_api_pages[$page_id]['template'] = $temp_template_slug[0];
                        if( isset($temp_template_slug[1]) && !empty($temp_template_slug[1]) ){
                            $rest_api_pages[$page_id]['template'] = $temp_template_slug[1];
                        }
                    }
                }
            }
        }
        
        if( $rest_api_pages ){
            $i=1;
            foreach ($rest_api_pages as $key => $value) {
                $finalArr[$key]['id'] = $key;
                $finalArr[$key]['name'] = $value['name'];
                $finalArr[$key]['slug'] = $value['slug'];
                $finalArr[$key]['permalink'] = $value['permalink'];
                if( isset($value['template']) ){
                $finalArr[$key]['template'] = $value['template'];
                }
            }
        }
        $response1 = new WP_REST_Response($finalArr);
        $response1->set_data($finalArr);
        return $response1;
    }
?>