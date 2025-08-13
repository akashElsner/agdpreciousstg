<?php
    function rest_wishlist_ids_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
    
        if( $user_id && $user_id > 0 ){
            global $wpdb;
            // Current site prefix
            $prefix = $wpdb->prefix;
            $table_name = $prefix.'yith_wcwl';
            $results = $wpdb->get_results( "SELECT prod_id FROM $table_name WHERE user_id = $user_id");
            if( $results ){
                foreach ($results as $key => $value) {
                    $data['wishlist'][] = $value->prod_id;
                }
                
                $data['status'] = 'Success';
                $data['msg'] = '';
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }