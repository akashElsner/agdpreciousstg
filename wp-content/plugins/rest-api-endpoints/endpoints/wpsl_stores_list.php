<?php
    function rest_wpsl_stores_list_callback( $request  ){
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!";
        
        $args = array(
            'post_type' => 'wpsl_stores',
            'posts_per_page' => -1,
        );

        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) {
            $i = 0;
        	while ( $the_query->have_posts() ) {
        		$the_query->the_post();
        		$store_id = get_the_ID();
        		
        		$data['status'] = 'Success';
                unset($data['msg']);
        		
        		$data['wpsl_store'][$i]['id'] = $store_id;
        		$data['wpsl_store'][$i]['store_name'] = html_entity_decode(get_the_title());
        		$data['wpsl_store'][$i]['store_desc'] = get_the_content();
        		$data['wpsl_store'][$i]['wpsl_address'] = html_entity_decode(get_post_meta($store_id, 'wpsl_address', true));
        		$data['wpsl_store'][$i]['wpsl_address2'] = html_entity_decode(get_post_meta($store_id, 'wpsl_address2', true));
        		$data['wpsl_store'][$i]['wpsl_city'] = get_post_meta($store_id, 'wpsl_city', true);
        		$data['wpsl_store'][$i]['wpsl_zip'] = get_post_meta($store_id, 'wpsl_zip', true);
        		$data['wpsl_store'][$i]['wpsl_country'] = get_post_meta($store_id, 'wpsl_country', true);
        		$data['wpsl_store'][$i]['wpsl_lat'] = get_post_meta($store_id, 'wpsl_lat', true);
        		$data['wpsl_store'][$i]['wpsl_lng'] = get_post_meta($store_id, 'wpsl_lng', true);
        		$data['wpsl_store'][$i]['wpsl_hours'] = get_post_meta($store_id, 'wpsl_hours', true);
        		$data['wpsl_store'][$i]['wpsl_phone'] = html_entity_decode(get_post_meta($store_id, 'wpsl_phone', true));
        		$data['wpsl_store'][$i]['wpsl_email'] = get_post_meta($store_id, 'wpsl_email', true);
        		$data['wpsl_store'][$i]['wpsl_url'] = get_post_meta($store_id, 'wpsl_url', true);
        		$data['wpsl_store'][$i]['wpsl_state'] = html_entity_decode(get_post_meta($store_id, 'wpsl_state', true));
        		$data['wpsl_store'][$i]['wpsl_fax'] = html_entity_decode(get_post_meta($store_id, 'wpsl_fax', true));
        		$i++;
        	}
        	wp_reset_postdata();
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }