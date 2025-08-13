<?php
    function rest_add_wishlist_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        $prod_id = $request->get_param( 'prod_id' );
        $quantity = $request->get_param( 'quantity' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
    
        if( $user_id && $user_id > 0 ){
            global $wpdb;
            $prefix = $wpdb->prefix;
            $wishlist_id = get_wishlist_id($user_id);
            
            if (empty($wishlist_id)) {
                $yith_wcwl_lists = $prefix . 'yith_wcwl_lists';
                $result = $wpdb->insert($yith_wcwl_lists, array(
                    'user_id' => $user_id,
                    'session_id' => NULL,
                    'wishlist_slug' => '',
                    'wishlist_name' => NULL,
                    'wishlist_token' => generate_wishlist_token(),
                    'wishlist_privacy' => 0,
                    'is_default' => 1,
                    'dateadded' =>  current_time('Y-m-d H:i:s'),
                    'expiration' => Null,
                ));
                
                if( $result == 1 ){
                    $data['status'] = 'Failed';
                    $data['msg'] = "404 Not found!"; 
                }else{
                    $wishlist_id = get_wishlist_id($user_id);
                }
            }
            
            if( $prod_id > 0 && $quantity > 0 ){
                $product = wc_get_product($prod_id);
                $original_price = $product->get_price();
                $original_price = number_format((float)$original_price, 3, '.', '');
                $original_currency = 'AUD';
                $dateadded = current_time('Y-m-d H:i:s');
                
                $table_name = $wpdb->prefix . 'yith_wcwl';
                $result = $wpdb->insert($table_name, array(
                    'prod_id' => $prod_id,
                    'quantity' => $quantity,
                    'user_id' => $user_id,
                    'wishlist_id' => $wishlist_id,
                    'position' => 0,
                    'original_price' => $original_price,
                    'original_currency' => $original_currency,
                    'dateadded' => $dateadded,
                    'on_sale' => 0,
                ));
                
                $data['status'] = 'Success';
                $data['msg'] = "Product added to wishlist"; 
                $data['result'] = $result; 
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }