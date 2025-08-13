<?php
    function rest_total_available_vouchers_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        $account_status = ($request->get_param( 'account_status' ) == 'false') ? 'false' : 'true';
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
        $data['user_id'] = $user_id;
        $data['account_status'] = $account_status;
        $data['promotion_ends_text'] = get_field('promotion_ends_text', 'option');
        $data['promotion_ends_text_upload-invoice'] = get_field('promotion_ends_text_upload-invoice', 'option');
        $data['start_date_of_promotion'] = get_field('start_date_of_promotion', 'option');
        $data['end_date_of_promotion'] = get_field('end_date_of_promotion', 'option');
        //$data['start_date_of_invoice'] = get_field('start_date_of_invoice', 'option');
        //$data['end_date_of_invoice'] = get_field('end_date_of_invoice', 'option');
        $data['start_date_of_invoice'] = get_field('start_date_of_promotion', 'option');
        $data['end_date_of_invoice'] = get_field('end_date_of_promotion', 'option');
        
        if( $user_id && (int)$user_id > 0 ){
        	$meta_key = 'custom_voucher_codes';
            $temp = get_user_meta($user_id, $meta_key,true);
            
            //$data['temp'] = $temp;
            $available_points = hager_current_user_points($user_id);
	        $used_points = hager_current_user_used_points($user_id);
	        
	        if( isset($account_status) ){
	            update_user_meta($user_id, 'account_status', $account_status);
	        }
	        
	        
	        //$data['available_points'] = $available_points;
	        //$data['used_points'] = $used_points;
	        
            /*if( $temp ){
                $data['status'] = 'Success';
                $data['msg'] = 'Vouchers are available';
                $data['available_points'] = $available_points;
                $data['used_points'] = $used_points;
            }*/
            
            if( $available_points && (int)$available_points > 0 ){
                $data['status'] = 'Success';
                $data['msg'] = 'Points are available';
                $data['available_points'] = $available_points;
                $data['used_points'] = $used_points;
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }