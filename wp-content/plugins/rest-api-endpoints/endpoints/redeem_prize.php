<?php
    function rest_redeem_prize_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        //$redeem_nonce = $request->get_param( 'redeem_nonce' );
        $product_id = $request->get_param( 'product_id' );
        $variation_id = $request->get_param( 'variation_id' );
        $quantity = $request->get_param( 'quantity' );
        
        $user_voucher_meta = hager_current_user_vouchers($user_id);		
		$available_vouchers = 0;
		if( $user_voucher_meta ){
			$available_vouchers = count($user_voucher_meta);
		}
        $current_time = date('Ymd', current_time('timestamp'));
        $available_vouchers = hager_current_user_points($user_id);
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        $promotion_date = array();
        $promotion_date['start_date'] = ( get_field('start_date_of_promotion','option') ) ? date('Ymd', strtotime(get_field('start_date_of_promotion','option'))) : '';
	    $promotion_date['end_date'] = ( get_field('end_date_of_promotion','option') ) ? date('Ymd', strtotime(get_field('end_date_of_promotion','option'))) : '';
	    
	    $promotion_close = false;
    	if( isset($promotion_date['start_date']) 
    		&& isset($promotion_date['end_date']) 
    		&& $promotion_date['start_date'] == $promotion_date['end_date'] 
    		&& $promotion_date['start_date'] > $current_time ){
    			$promotion_close = true;
    	}else if( isset($promotion_date['start_date']) 
    		&& isset($promotion_date['end_date']) && ($promotion_date['start_date'] > $current_time || $promotion_date['end_date'] < $current_time) ){
    			$promotion_close = true;
    	}
    	
    	if( $promotion_close ){
    	    $response1 = new WP_REST_Response($data);
            $response1->set_data($data);
            return $response1;
    	}
        
        //$redeem_nonce = $request->get_param('redeem_nonce');
        //$data['redeem_nonce'] = $request->get_param('redeem_nonce');
            
        //$verification = wp_verify_nonce($redeem_nonce, "redeem-prize-".$user_id);
        $verification = 1;
           
        $data['verification'] = $verification;
        if( $verification ){
            $customer = new WC_Customer( $user_id );
            
            $user_email   = $customer->get_email(); // Get account email
            $first_name   = $customer->get_first_name();
            $last_name    = $customer->get_last_name();
            
            $product = wc_get_product($product_id);
            $voucher_price = $product->get_price();
            
            if( $variation_id && $variation_id > 0 ){
        	    $varProduct = new WC_Product_Variation($variation_id);
        	    $voucher_price = $varProduct->get_price();
        	}
        	
        	$required_vouchers = $quantity * $voucher_price;
        	if( $available_vouchers >= $required_vouchers ){
        	    
        	    if( empty($user_email) ){
            		$user_email = $current_user->user_email;
            	}
            	if( empty($first_name) ){
            		$first_name = $current_user->user_firstname;
            	}
            	if( empty($last_name) ){
            		$last_name = $current_user->user_lastname;
            	}
            	
            	$address = array(
            		'first_name' => esc_html( $current_user->user_firstname ),
            		'last_name'  => esc_html( $current_user->user_lastname ),
            		'customer_id'	=>	$user_id,
            		'email'      => esc_html( $current_user->user_email ),
            		'phone'      => get_user_meta($user_id, 'billing_phone', true),
            		'address_1'  => $customer->get_billing_address_1(),
            		'address_2'  => $customer->get_billing_address_2(),
            		'city'       => $customer->get_billing_city(),
            		'state'      => $customer->get_billing_state(),
            		'postcode'   => $customer->get_billing_postcode(),
            		'country'    => $customer->get_billing_country()
            	);
        
            	$temp_shipping_address = array();
        		$temp_shipping_address[]  = $customer->get_shipping_address_1();
        		$temp_shipping_address[]  = $customer->get_shipping_address_2();
        		$temp_shipping_address[]  = $customer->get_shipping_city();
        		$temp_shipping_address[]  = $customer->get_shipping_state();
        		$temp_shipping_address[]  = $customer->get_shipping_postcode();
        		$ship_address = array_filter($temp_shipping_address);
        
            	$shipping_address = array(
            		'first_name' => esc_html( $current_user->user_firstname ),
            		'last_name'  => esc_html( $current_user->user_lastname ),
            		'address_1'  => $customer->get_shipping_address_1(),
            		'address_2'  => $customer->get_shipping_address_2(),
            		'city'       => $customer->get_shipping_city(),
            		'state'      => $customer->get_shipping_state(),
            		'postcode'   => $customer->get_shipping_postcode(),
            		'country'    => $customer->get_shipping_country()
            	);
                
                
                $membershipProduct = new WC_Product_Variable($product_id);
            	$theMemberships = $membershipProduct->get_available_variations();
            	$variationsArray = array();
            	
            	foreach ($theMemberships as $membership) {
            	    if ($membership['variation_id'] == $variation_id) {
            	        $variationID = $membership['variation_id'];
            	        $variationsArray['variation'] = $membership['attributes'];
            	    }
            	}
        	    
        	    $order = wc_create_order();
        	    
        	    if( $variation_id && $variation_id > 0 ){
            		$varProduct = new WC_Product_Variation($variation_id);
            		$order->add_product($varProduct, $quantity, $variationsArray);
            	}else{
            		$order->add_product( get_product($product_id), $quantity); // This is an existing SIMPLE product
            	}
            	$order->set_address( $address, 'billing' );
        
            	if( $ship_address && count($ship_address) > 0 ){
            		$order->set_address( $shipping_address, 'shipping' );
            	}
        
            	$order->set_customer_id($user_id);
            	
            	$available_points = hager_current_user_points($user_id);
        	    $used_points = hager_current_user_used_points($user_id);
            
                $available_points = ((int)$available_points - (int)$required_vouchers);
                $used_points = ((int)$used_points + (int)$required_vouchers);
                update_user_meta($user_id, 'whph_available_points', $available_points);
                update_user_meta($user_id, 'whph_used_points', $used_points);
                
                $total_redeemed = hager_total_redeemed_points();
                $total_redeemed = ((int)$total_redeemed + (int)$required_vouchers);
                update_option('whph_total_redeemed_points', $total_redeemed);
                $order->add_meta_data('meta_used_points', $required_vouchers);
            	
            	/*$meta_key = 'custom_voucher_codes';
            	$meta_used_key = 'custom_used_voucher_codes';
            	$user_voucher_meta = get_user_meta($user_id, $meta_key, true);
            	$user_voucher_meta_output = array_slice($user_voucher_meta, $required_vouchers);
        
            	$meta_used_key_with_date = 'custom_used_voucher_codes_with_date';
            	$user_used_vouchers_with_date = get_user_meta($user_id, $meta_used_key_with_date, true);
        
            	if ( ! array($user_used_vouchers_with_date) || !$user_used_vouchers_with_date ) {
        	        $user_used_vouchers_with_date = array();
        	    }
        	    
        	    $user_used_voucher_codes = hager_current_user_used_vouchers( $user_id );
    
            	$user_used_voucher_meta_output = array_slice($user_voucher_meta, 0, ($required_vouchers));
        
            	$current_time = current_time( 'timestamp' );
            	foreach ($user_used_voucher_meta_output as $key => $value) {
            		$user_used_vouchers_with_date = array_merge($user_used_vouchers_with_date, array($value=>$current_time));
            	}
            	
            	$order->add_meta_data('meta_used_key', $user_used_voucher_meta_output);
            	$data['used_voucher_in_order'] = $user_used_voucher_meta_output;
    
            	if( $user_used_voucher_codes != 0 && $user_used_voucher_codes ){
            		$user_used_voucher_codes = array_merge($user_used_voucher_codes, $user_used_voucher_meta_output);
            	}else{
            		$user_used_voucher_codes = $user_used_voucher_meta_output;
            	}
            
            	update_user_meta($user_id, $meta_key, $user_voucher_meta_output);
            	update_user_meta($user_id, $meta_used_key_with_date, $user_used_vouchers_with_date);
            	update_user_meta($user_id, $meta_used_key, array_filter($user_used_voucher_codes));*/
            
            
            	$order->calculate_totals();
            	$order->update_status("on-hold", 'Redeem Points', TRUE);
            	
            	$data['status'] = 'Success';
                $data['msg'] = "Order confirm";
        	}else{
        	    $data['status'] = 'Failed';
                $data['msg'] = "Error: don't have enough points to redeem";
        	}
        	
        }else{
            $data['status'] = 'Failed';
            $data['msg'] = "Error: Nonce verification!";
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }