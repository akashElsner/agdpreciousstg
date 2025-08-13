<?php
    function rest_clear_wishlist_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!"; 
        
        global $wpdb;
        $prefix = $wpdb->prefix;
        $table_name = $prefix . 'yith_wcwl';
                
        if( $user_id > 0 ){
            $result = $wpdb->query("DELETE  FROM {$table_name} WHERE user_id = '{$user_id}'");
            
            if ($result == 1) {
                $data['status'] = 'Success';
                $data['msg'] = "Wishlist cleared"; 
            }
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }