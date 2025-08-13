<?php
    function rest_delete_wishlist_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        $prod_id = $request->get_param( 'prod_id' );
        $quantity = $request->get_param( 'quantity' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
        
        global $wpdb;
        $prefix = $wpdb->prefix;
        $table_name = $prefix . 'yith_wcwl';
                
        if( $user_id > 0 && $prod_id > 0 ){
            $result = $wpdb->query("DELETE  FROM {$table_name} WHERE user_id = '{$user_id}' AND  prod_id = '{$prod_id}'");
            
            if ($result) {
                $data['status'] = 'Success';
                $data['msg'] = "Wishlist updated"; 
            }
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }