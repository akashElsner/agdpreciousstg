<?php
    function rest_add_receipt_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        $receipt = $request->get_file_params( 'receipt' );
        $nominated_wholesaler = $request->get_param( 'nominated_wholesaler' );
        $wholesaler = trim($request->get_param( 'wholesaler' ));
        $branch = trim($request->get_param( 'branch' ));
        $device_token = $request->get_param( 'device_token' );
        $invoice_id = $invoice_date = '';
        $amount = 0;
        $data = array();
        
        if( $nominated_wholesaler && $user_id > 0 ){
            $wholesaler = get_user_meta($user_id,'wholesale_group',true);
            $branch = get_user_meta($user_id,'wholesale_branch',true);
        }
        
        $wholesale_branch_flag = false;
        $current_time = date('Ymd', current_time('timestamp'));
	
        $promotion_date = array();
    	$promotion_date['start_date'] = ( get_field('start_date_of_promotion','option') ) ? date('Ymd', strtotime(get_field('start_date_of_promotion','option'))) : '';
    	$promotion_date['end_date'] = ( get_field('end_date_of_promotion','option') ) ? date('Ymd', strtotime(get_field('end_date_of_promotion','option'))) : '';
    	array_filter($promotion_date);
    	$upload_close = false;
    	
    	/*if( $user_id == 3998 ){
    	    $promotion_date['end_date'] = 20240421;
    	}*/
    	
    	if( isset($promotion_date['start_date']) 
		&& isset($promotion_date['end_date']) 
		&& $promotion_date['start_date'] == $promotion_date['end_date'] 
		&& $promotion_date['start_date'] > $current_time ){
			$upload_close = true;
    	}else if( isset($promotion_date['start_date']) 
    		&& isset($promotion_date['end_date']) && ($promotion_date['start_date'] > $current_time || $promotion_date['end_date'] < $current_time) ){
    			$upload_close = true;
    	}
        
	    if( !empty($wholesaler) && !empty($branch) ){
	        $args = array(
                'role' => 'wholesaler',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wholesale_group',
                        'value' => $wholesaler,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key' => 'wholesale_branch',
                        'value' => $branch,
                        'compare' => 'LIKE',
                    )
                ),
                'fields' => 'ID',
            );
            $users_with_group = get_users($args);
            
            if( $users_with_group && !empty($users_with_group) ){
                $wholesale_branch_flag = true;
            }
	    }
        
        $params = array(
            'user_id' => $user_id,
            'receipt' => $receipt,
            'nominated_wholesaler' => $nominated_wholesaler,
            'wholesaler' => $wholesaler,
            'branch' => $branch,
            'device_token' => $device_token,
        );
        
        $receipt_name = explode('.', $receipt['receipt']['name']);
        $new_receipt_name = '';
        
        for ($i=0; $i < count($receipt_name)-1; $i++) { 
            $new_receipt_name .= $receipt_name[$i];
        }
	    
	    $data['wholesale_branch_flag'] = $wholesale_branch_flag;
        
        $receipt['receipt']['name'] = $new_receipt_name.'_'.current_time('timestamp').'.'.$receipt_name[count($receipt_name)-1];
        
        if( $user_id && $user_id > 0 && $receipt && $wholesale_branch_flag && !$upload_close ){
            //$data['rc'] = $receipt['receipt'];
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $upload = wp_handle_upload( 
                $receipt['receipt'], 
                array( 'test_form' => false ) 
            );
            
            $data['upload'] = $upload;
            
            if( ! empty( $upload[ 'error' ] ) ) {
                $data['status'] = 'Failed';
                $data['msg'] = "Sorry, please upload the invoice again.";    
            }else{
                /*$data['status'] = 'Success!';
                $data['msg'] = "We will notify you once invoice has been processed.";*/
                $data['status'] = 'Scanning of Invoice complete.';
                $data['msg'] = "You will receive a confirmation message once details of the invoice have been validated.";
                
                $params['upload'] = $upload;
            }
            
        }else{
            $data['status'] = 'Failed';
            $data['msg'] = "Sorry, please scan your invoice again.....";    
        }
        
        if( $upload_close ){
            $data['status'] = 'Failed';
            $promotion_ends_text = (get_field('promotion_ends_text_upload-invoice', 'option')) ? get_field('promotion_ends_text_upload-invoice', 'option') : 'Invoice upload will be available soon.';
            $data['msg'] = "Your invoice was not processed. " . $promotion_ends_text;
        }else if( !$wholesale_branch_flag ){
            $data['status'] = 'Failed';
            $data['msg'] = "Your invoice was declined. Please check the FAQs for the list of this year's participating branches.";   
        }
        
        
        $data['params'] = $params;
        
        $time = time();
        // $cookie_name = "schedule_time";
        // $cookie_value = current_time('timestamp');
        // $_COOKIE[$cookie_name] = $cookie_value;
        // setcookie($cookie_name, $cookie_value, $time + (300), "/"); // 86400 = 1 day
        
        // foreach( $params as $key => $val ){
        //     $_COOKIE[$key] = $val;
        //     setcookie($key, $val, $time + (300), "/"); // 86400 = 1 day
        // }
        
        if( $data['status'] != 'Failed' ){
            //$data['schedult_stats'] = wp_schedule_single_event( $time + 20, 'background_add_receipt_event', $params );
            wp_schedule_single_event( $time + 2, 'background_add_receipt_event', $params );
            $data['extra'] = 'EXTRA';
        }
        
        //background_add_receipt($params['user_id'], $params['receipt'], $params['nominated_wholesaler'], $params['wholesaler'], $params['branch'], $params['device_token'], $params['upload']);
        
        // $to = 'aakif@elsner.com.au';
        // $subject = 'Add Receipt';
        // $body = '----------------';
        // $headers = array('Content-Type: text/html; charset=UTF-8');
        
        // wp_mail( $to, $subject, $body, $headers );
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }
    
    
    
    add_action('background_add_receipt_event', 'background_add_receipt', 10, 7);
    add_action('background_add_receipt_event_email', 'background_add_receipt', 10, 8);
    function background_add_receipt( $user_id, $receipt = array(), $nominated_wholesaler, $wholesaler, $branch, $device_token, $upload, $email_invoice = 0 ){

        $new_email_invoice_user = array();

        /*$msgN = "userId=".$user_id;
        $msgN .= "receipt=".$receipt;
        $msgN .= "nominatedwholesaler=".$nominated_wholesaler;
        $msgN .= "wholesaler=".$wholesaler;
        $msgN .= "branch=".$branch;
        $msgN .= "device_token=".$device_token;
        $msgN .= "upload=".$upload;
       $msgN .= "email_invoice=".$email_invoice;
       $log_file = ABSPATH . 'custom-log1.txt';

            // Format the log entry with timestamp
            $log_entry = date('Y-m-d H:i:s') . ' - ' . $msgN . PHP_EOL;
        
            // Append the log entry to the log file
            file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
            return true;*/
        $return_data = array();
        
        /*$to = 'aakif@elsner.com.au';
        $subject = 'CRON RUN '.$user_id;
        $body = $receipt;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail( $to, $subject, print_r($receipt), $headers );*/
            
        //error_log('Background process is running!');
        //if( isset($_COOKIE['user_id']) && $_COOKIE['user_id'] > 0 ){
            // $to = 'aakif@elsner.com.au';
            // $subject = 'Add Receipt';
            // $body = $user_id.' ---------------- '.$wholesaler;
            // $headers = array('Content-Type: text/html; charset=UTF-8');
            
            // wp_mail( $to, $subject, $body, $headers );
        //}
        
        $user_device_token = $device_token;
        if( empty($device_token) && get_user_meta($user_id, 'device_tokens', true) ){
            $user_device_token = get_user_meta($user_id, 'device_tokens', true);
        }elseif( isset($device_token) && !empty($device_token) ){
            update_user_meta($user_id, 'device_tokens', $device_token);
        }
        
        $user_state = (get_user_meta($user_id,'billing_state',true)) ? get_user_meta($user_id,'billing_state',true) : ( (get_user_meta($user_id,'shipping_state',true) ? get_user_meta($user_id,'shipping_state',true) : '') );
        
        /*$to = 'aakif@elsner.com.au';
        $subject = 'Add Receipt DEVICE TOKEN';
        $body = $user_id.' ---------------- '.$user_device_token;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail( $to, $subject, $body, $headers );
        
        if( $user_device_token && !empty($user_device_token) ){
            $fcm = new FCM();
            
            $dType = '';
            $arrNotification = array();
            $arrNotification["body"] = $data['msg'];
            $arrNotification["title"] = $subject;
            $arrNotification["sound"] = "default";
            $arrNotification["type"] = 1;
            $upload_time = current_time( 'timestamp' );
            
            $result = $fcm->send_notification(array($user_device_token), $arrNotification,$dType);
        }
    }
    
    
    function rest_add_receipt_callback_2( $request  ){*/
        /*$user_id = $request->get_param( 'user_id' );
        $receipt = $request->get_file_params( 'receipt' );
        $nominated_wholesaler = $request->get_param( 'nominated_wholesaler' );
        $wholesaler = $request->get_param( 'wholesaler' );
        $branch = $request->get_param( 'branch' );
        $invoice_id = $invoice_date = '';*/
        $amount = 0;
        
        if( $nominated_wholesaler && strtolower($nominated_wholesaler) != 'no' ){
            $wholesaler = get_user_meta($user_id,'wholesale_group',true);
            $branch = get_user_meta($user_id,'wholesale_branch',true);
        }
        $email_invoice_user = 0;
        $email_wholesaler_branch_status = 0;
        $data['status'] = 'Failed';
        $status = 'declined';
        $data['msg'] = "Sorry, please upload the invoice again.";
        //$data['user_id'] = $user_id;
        
        // $tempp[0]['currency_1'] =  add_receipt_meta(1, 'currency', 111);
        // $tempp[0]['currency_2'] =  add_receipt_meta(1, 'currency', 'currency');
        // return $tempp;
        
        $receipt_name = explode('.', $receipt['receipt']['name']);
        $new_receipt_name = '';
        
        for ($i=0; $i < count($receipt_name)-1; $i++) { 
            $new_receipt_name .= $receipt_name[$i];
        }
        
        $receipt['receipt']['name'] = $new_receipt_name.'_'.current_time('timestamp').'.'.$receipt_name[count($receipt_name)-1];
        //$data['receipt'] = $receipt;
        
        //$data['path'] = ABSPATH . 'wp-admin/includes/file.php';
    
        $assigned_points = 0;
        $receipt_data = array();
        
        if( $user_id && $user_id > 0 && $receipt ){
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            // $upload = wp_handle_upload( 
            //     $receipt['receipt'], 
            //     array( 'test_form' => false ) 
            // );
            
            // //$data['upload'] = $upload;
            
            // if( ! empty( $upload[ 'error' ] ) ) {
            //     $data['status'] = 'Failed';
            //     $data['msg'] = "Upload Error: Something went wrong!";    
            // }else{
            
            if( $upload ){
                $receipt_data = nanoInvoiceOcr($upload['url'], $user_id, $wholesaler);
                
                $receipt_flag = true;
                
                if( isset($receipt_data['invoice_status']) && $receipt_data['invoice_status'] == 'failed' ){
                    $receipt_flag = false;
                    $data['detailed_msg'][] = $receipt_data['invoice_status_msg'];
                }
                
                $manual_verification = false;
                if( !is_array($receipt_data) ){
                    $data['status'] = 'Failed';
                    $data['msg'] = "The invoice was not clear enough. Please upload or scan a better version.";
                    $data['detailed_msg'][] = 'The invoice has not been scanned correctly.';
                    $receipt_flag = false;
                }
                
                if( is_array($receipt_data) && $receipt_data ){
                    
                    $temp_merchant_name = '';
                    if( $receipt_data['buyer_name'] && !empty($receipt_data['buyer_name']) ){
                        $temp_merchant_name = strtolower(str_replace(array("’","'"," "), "", $receipt_data['buyer_name']));
                    }
                    
                    $temp_merchant_address = '';
                    if( $receipt_data['buyer_address'] && !empty($receipt_data['buyer_address']) ){
                        $temp_merchant_address = strtolower(str_replace(array("’","'"," "), "", $receipt_data['buyer_address']));
                    }else{
                        $data['detailed_msg'][] = 'Business address is not scanned.';
                    }
                    
                    $temp_merchant_shipto_address = '';
                    if( $receipt_data['shipto_address'] && !empty($receipt_data['shipto_address']) ){
                        $temp_merchant_shipto_address = strtolower(str_replace(array("’","'"," "), "", $receipt_data['shipto_address']));
                    }else{
                        $data['detailed_msg'][] = 'Shipto address is not scanned.';
                    }
                    
                    $temp_shipto_name = '';
                    if( $receipt_data['shipto_name'] && !empty($receipt_data['shipto_name']) ){
                        if( is_array($receipt_data['shipto_name']) ){
                            $temp_shipto_name = strtolower(str_replace(array("’","'"," "), "", $receipt_data['shipto_name'][0]));
                        }else{
                            $temp_shipto_name = strtolower(str_replace(array("’","'"," "), "", $receipt_data['shipto_name']));
                        }
                    }else{
                        $data['detailed_msg'][] = 'Shipto name is not scanned.';
                    }

                    if( $email_invoice > 0 ){
                        $role__not_in = array('wholesaler', 'sales_representative', 'administrator', 'nsw_sales_representative_managers', 'vic_sales_representative_managers', 'wa_sales_representative_managers', 'qld_sales_representative_managers', 'sa_nt_sales_representative_managers');
                        $meta_key = 'business_name_abn';
                        $meta_val = '';
                        if( isset($receipt_data['buyer_name']) && !empty(trim($receipt_data['buyer_name'])) ){
                            //$meta_val = trim($temp_merchant_name);
                            $meta_val = trim($receipt_data['buyer_name']);
                        }else if( isset($receipt_data['shipto_name']) && !empty(trim($receipt_data['shipto_name'])) ){
                            //$meta_val = trim($temp_shipto_name);
                            if( is_array($receipt_data['shipto_name']) ){
                                $meta_val = trim($receipt_data['shipto_name'][0]);
                            }else{
                                $meta_val = trim($receipt_data['shipto_name']);
                            }
                        }

                        if( !empty($meta_key) && !empty($meta_val) ){
                            $available_user_businessname = get_users(
                                array(
                                    'role__not_in' => $role__not_in,
                                    'meta_query' => array(
                                        array(
                                            'key' => $meta_key,
                                            'value' => $meta_val,
                                            'compare' => 'LIKE'
                                        )
                                    ),
                                    'fields' => 'ID',
                                )
                            );

                            if ($available_user_businessname) {
                                foreach ($available_user_businessname as $business_u_id) {
                                    $user_id = $business_u_id;
                                    $email_invoice_user = $business_u_id;

                                    $new_email_invoice_user[$meta_val] = $user_id;
                                    
                                    /*if( ($nominated_wholesaler && strtolower($nominated_wholesaler) != 'no') ){
                                        $wholesaler = get_user_meta($user_id,'wholesale_group',true);
                                        $branch = get_user_meta($user_id,'wholesale_branch',true);
                                    }*/
                                    /*
                                    $wholesaler = get_user_meta($user_id,'wholesale_group',true);
                                    $branch = get_user_meta($user_id,'wholesale_branch',true);
                                    */
                                    
                                    $user_device_token = get_user_meta($user_id, 'device_tokens', true);
                                    $user_state = (get_user_meta($user_id,'billing_state',true)) ? get_user_meta($user_id,'billing_state',true) : ( (get_user_meta($user_id,'shipping_state',true) ? get_user_meta($user_id,'shipping_state',true) : '') );
                                }
                            }else{
                                $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                                $data['detailed_msg'][] = 'User not found with Business Name';
                                $manual_verification = true;
                                $receipt_flag = false;
                            }
                        }else{
                            $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                            $data['detailed_msg'][] = 'User not found with Business Name';
                            $manual_verification = true;
                            $receipt_flag = false;
                        }
                    }
                    
                    $merchant_flag = true;
                    $current_business_name = trim( strval(get_user_meta($user_id, 'business_name_abn', true)) );
                    $current_user_business_name = strtolower( str_replace(array("’","'"," "), "", strval(get_user_meta($uid, 'business_name_abn', true))) );
                    if( 1==2 ){
                        if( empty(trim($current_user_business_name)) ){
                            $merchant_flag = false;
                            $receipt_flag = false;
                            $manual_verification = true;
                            //$data['msg'] = "Please check your profile. Business name should not be empty.";
                            $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                            $data['detailed_msg'][] = 'User\'s Business name is empty.';
                        }
                        
                        if( $merchant_flag && $temp_merchant_name && !empty($temp_merchant_name) ){
                            $merchant_name = str_replace('
', ' ', $temp_merchant_name);
                            $merchant_name = str_replace(' ', '', trim($merchant_name));
                            if( $merchant_name == $current_user_business_name ){
                                $merchant_flag = false;
                            }else if( strpos(strval($current_user_business_name), strval($merchant_name)) !== false || strpos(strval($merchant_name), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                            }else if( strpos(strval($current_user_business_name), strval(str_replace('&', 'and', $merchant_name))) !== false 
                            || strpos(strval(str_replace('&', 'and', $merchant_name)), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                            }
                        }
                        
                        if( $temp_shipto_name && $merchant_flag ){
                            //foreach($temp_shipto_name as $k => $v){
                                $shipto_name = str_replace('
', ' ', $temp_shipto_name);
                                $shipto_name = str_replace(' ', '', $shipto_name);
                                $shipto_name = strtolower(str_replace( ' ', '', trim(strval($shipto_name)) ));
                                if( $shipto_name == $current_user_business_name ){
                                    $merchant_flag = false;
                                    $receipt_data['buyer_name'] = $current_business_name;
                                    //break;
                                }else if( strpos(strval($current_user_business_name), strval($shipto_name)) !== false || strpos(strval($shipto_name), strval($current_user_business_name)) !== false ){
                                    $merchant_flag = false;
                                    $receipt_data['buyer_name'] = $current_business_name;
                                    //break;
                                }else if( strpos(strval($current_user_business_name), strval(str_replace('&', 'and', $shipto_name))) !== false 
                                || strpos(strval(str_replace('&', 'and', $shipto_name)), strval($current_user_business_name)) !== false ){
                                    $merchant_flag = false;
                                    $receipt_data['buyer_name'] = $current_business_name;
                                    //break;
                                }
                            //}
                        }
                        
                        if( $merchant_flag && $temp_merchant_address && !empty($temp_merchant_address) ){
                            $merchant_address = str_replace('
', ' ', $temp_merchant_address);
                            $merchant_address = str_replace(' ', '', $merchant_address);
                            if( $merchant_address == $current_user_business_name ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }else if( strpos(strval($current_user_business_name), strval($merchant_address)) !== false || strpos(strval($merchant_address), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }else if( strpos(strval($current_user_business_name), strval(str_replace('&', 'and', $merchant_address))) !== false 
                            || strpos(strval(str_replace('&', 'and', $merchant_address)), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }
                        }
                        
                        if( $merchant_flag && $temp_merchant_shipto_address && !empty($temp_merchant_shipto_address) ){
                            $merchant_shipto_address = str_replace('
', ' ', $temp_merchant_shipto_address);
                            $merchant_shipto_address = str_replace(' ', '', $merchant_shipto_address);
                            if( $merchant_shipto_address == $current_user_business_name ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }else if( strpos(strval($current_user_business_name), strval($merchant_shipto_address)) !== false || strpos(strval($merchant_shipto_address), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }else if( strpos(strval($current_user_business_name), strval(str_replace('&', 'and', $merchant_shipto_address))) !== false 
                            || strpos(strval(str_replace('&', 'and', $merchant_shipto_address)), strval($current_user_business_name)) !== false ){
                                $merchant_flag = false;
                                $receipt_data['buyer_name'] = $current_business_name;
                            }
                        }
                    }else{
                        $merchant_flag = false;
                    }
                    if( !isset($receipt_data['buyer_name']) || empty($receipt_data['buyer_name']) ){
                        $data['detailed_msg'][] = 'Business name is not scanned.';
                    }
                    
                    /*$merchant_name = str_replace('
', ' ', $temp_merchant_name);
                    $shipto_name = str_replace('
', ' ', $temp_shipto_name);
    
                    $merchant_flag = true;
                    if( $merchant_name == $current_user_business_name ){
                        $merchant_flag = false;
                    }elseif ( $shipto_name == $current_user_business_name ) {
                        $merchant_flag = false;
                    }*/
    
                    if( $receipt_flag && $merchant_flag ){
                        $data['status'] = 'Failed';
                        $manual_verification = true;
                        $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                        $data['detailed_msg'][] = 'User\'s Business name is wrong.';
                        $receipt_flag = false;
                    }
                    
                    $invoice_number = 0;
                    
                    if( isset($receipt_data['invoice_number']) && !empty($receipt_data['invoice_number']) ){
                        $invoice_number = $receipt_data['invoice_number'];
                    }else{
                        $data['detailed_msg'][] = "Invoice number is not scanned";
                    }
                    
                    $return_data['invoice_number'] = $invoice_number;
                    
                    //if( 1==2 && ((int)$invoice_number != 13021226 && $invoice_number != '677308-701') && $receipt_flag && $invoice_number > 0 &&  invoice_check($invoice_number, $wholesaler, $branch)){
                    //if( 1==2 ){
                    
                    update_user_meta($user_id, 'receipt_data_'.$receipt_data['invoice_number'], $receipt_data);
                    update_user_meta($user_id, 'receipt_data_msg_'.$receipt_data['invoice_number'], $data['msg']);
                    
                    $eligible_products_false_flag = false;
                    
                    if( ((!isset($receipt_data['product_codes']) || empty(array_filter($receipt_data['product_codes']))) && strtolower($user_state) != 'wa') 
                    || ((!isset($receipt_data['product_codes_wa']) || empty(array_filter($receipt_data['product_codes_wa']))) && strtolower($user_state) == 'wa') ){
                        $eligible_products_false_flag = true;
                        $data['status'] = 'Failed';
                        //$data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                        //$manual_verification = true;
                        $data['msg'] = "Your invoice does not include any eligible products";
                        $receipt_flag = false;
                        $data['detailed_msg'][] = 'Product codes not found in the list.';
                    }else if( (!isset($receipt_data['product_codes']) && strtolower($user_state) != 'wa') 
                    && (!isset($receipt_data['product_codes_wa']) && strtolower($user_state) == 'wa') ){
                        $eligible_products_false_flag = true;
                        $data['status'] = 'Failed';
                        //$data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                        //$manual_verification = true;
                        $data['msg'] = "Your invoice does not include any eligible products";
                        $receipt_flag = false;
                        $data['detailed_msg'][] = 'Product codes not scanned.';
                    /*}else if( empty($invoice_number) || (int)$invoice_number == 0 ){*/
                    }else if( empty($invoice_number) || !isset($invoice_number) ){
                        $eligible_products_false_flag = true;
                        $data['status'] = 'Failed';
                        $data['msg'] = "The invoice was not clear enough. Please upload or scan a better version.";
                        $receipt_flag = false;
                    }
                    
                    // else if( $invoice_number == 0 || !isset($receipt_data['product_codes']) || empty( array_filter($receipt_data['product_codes']) ) )
                    
                    $invoice_date = '';
                    
                    if( isset($receipt_data['Date-totime']) && !empty($receipt_data['Date-totime']) ){
                        $invoice_date = $receipt_data['Date-totime'];
                    }
                    
                    if( isset($receipt_data['invoice_date']) && !empty($receipt_data['invoice_date']) ){
                    	$invoice_date = str_replace(' ', '', $receipt_data['invoice_date']);
                    	$invoice_date_tocmpr = date('Ymd', strtotime($invoice_date));
                    	
                    	$start_invoice_date = date('Ymd', strtotime(get_field('start_date_of_invoice', 'option')));
        	            $end_invoice_date = date('Ymd', strtotime(get_field('end_date_of_invoice', 'option')));
        	            
        	            //$invoice_date_msg = 'Purchase Period - 1st Feb – 30th April';
        	            $invoice_date_msg = 'Purchase Period - '.date('jS M', strtotime(get_field('start_date_of_invoice', 'option'))).' – '.date('jS M', strtotime(get_field('end_date_of_invoice', 'option')));
        	            $invoice_date_err_flag = true;
                    	
                    	if( $start_invoice_date == $end_invoice_date && $invoice_date_tocmpr == $start_invoice_date ){
                    	    $invoice_date_err_flag = false;
                    	}else if( !empty($start_invoice_date) && (int)$start_invoice_date > 0
                    	&& !empty($end_invoice_date) && (int)$end_invoice_date > 0
                    	&& $start_invoice_date <= $invoice_date_tocmpr && $end_invoice_date >= $invoice_date_tocmpr ){
                    	    $invoice_date_err_flag = false;
                    	}
                    	
                    	if( $invoice_date_err_flag ){
                    	    $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                    	    $manual_verification = true;
                            $receipt_flag = false;
                            $data['detailed_msg'][] = $invoice_date_msg;
                    	}
                    	
                    }else if( empty($receipt_data['invoice_date']) ){
                        $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                        $manual_verification = true;
                        $receipt_flag = false;
                        $data['detailed_msg'][] = 'Invoice date is not scanned.';
                    }
                
                    $attachment_id = wp_insert_attachment(
                        array(
                            'guid'           => $upload[ 'url' ],
                            'post_mime_type' => $upload[ 'type' ],
                            'post_title'     => basename( $upload[ 'file' ] ),
                            'post_content'   => '',
                            'post_status'    => 'inherit',
                        ),
                        $upload[ 'file' ]
                    );
    
                    if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
                        $data['status'] = 'Failed';
                        $data['msg'] = "Sorry, no file has been selected. Please try again.";
                        $data['detailed_msg'][] = 'Invoice not uploaded as attachment.';
                    }else{
                        $assigned_points = 0;
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                        wp_update_attachment_metadata(
                            $attachment_id,
                            wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
                        );
                        
                        $data['image_id'] = $attachment_id;
                        $data['image_url'] = wp_get_attachment_url($attachment_id);
    
                        $status = 'declined';
                        $status_comment = $data['msg'];
                        if( $receipt_flag ){
                            $status = 'approved'; //approved
                            $status_comment = '';
                        }else{
                            $status = 'declined'; //declined, approved, requested
                            $status_comment = $data['msg'];
                        }
                        $upload_time = ($receipt_data['time']) ? $receipt_data['time'] : current_time( 'timestamp' );
                        $modified_time = $upload_time;
                        
                        $total_assigned_points = 0;
                        $get_total_assigned_points_wa = array();
                        
                        if( strtolower($user_state) == 'wa' ){
                            $get_total_assigned_points_wa = get_total_assigned_points_wa($receipt_data['table_data']);
                            if( $get_total_assigned_points_wa && !empty($get_total_assigned_points_wa) ){
                                $total_assigned_points = $get_total_assigned_points_wa['total_assigned_points'];
                            }
                        }else{
                            $total_assigned_points = get_total_assigned_points($receipt_data['table_data']);
    		
                    		if( $total_assigned_points == 0 || empty($total_assigned_points) ){
                    		    $total_assigned_points = $receipt_data['total_assigned_points'];
                    		}
                        }
                        
                        if( $receipt_flag && !empty($total_assigned_points) && strtolower($user_state) == 'wa' && isset($receipt_data['product_codes_wa']) ){
                            $status = 'approved';
                        }else if( $receipt_flag && !empty($total_assigned_points) ){
                            $status = 'approved';
                        }else if( (empty($total_assigned_points) && strtolower($user_state) != 'wa') || empty($total_assigned_points) && strtolower($user_state) == 'wa' ){
                            $eligible_products_false_flag = true;
                            $status = 'declined'; //declined, approved, requested
                            $data['msg'] = "Your invoice does not include any eligible products";
                            $manual_verification = false;
                            //$manual_verification = true;
                            $status_comment = $data['msg'];
                            //$data['detailed_msg'][] = 'Products are not scanned properly.';
                            $receipt_flag = false;
                        }
                        
                        if( (strtolower($user_state) == 'wa' && (!isset($receipt_data['product_codes_wa']) || empty($receipt_data['product_codes_wa'])))
                        || (strtolower($user_state) != 'wa' && (!isset($receipt_data['product_codes']) || empty($receipt_data['product_codes']))) ){
                            $eligible_products_false_flag = true;
                            $data['msg'] = "Your invoice does not include any eligible products";
                            $status = 'declined'; //declined, approved, requested
                            $manual_verification = false;
                            $status_comment = $data['msg'];
                            $receipt_flag = false;
                        }
                        
                        if( $manual_verification ){
                            $status = 'under_review';
                            $status_comment = $data['msg'];
                        }
                        
                        if( empty($data['msg']) && $status == 'under_review' ){
                            $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                            $status_comment = $data['msg'];
                        }
                        
                        if( $receipt_flag && !empty($invoice_number) && invoice_check($invoice_number)){
                            $data['status'] = 'Failed';
                            $data['msg'] = "Sorry, the invoice has been uploaded already.";
                            $data['detailed_msg'][] = 'Duplicate invoice found.';
                            $receipt_flag = false;
                            $manual_verification = false;
                            $status = 'declined';
                            $status_comment = $data['msg'];
                        }
                        
                        if( empty($wholesaler) || empty($branch) || empty(str_replace(' ', '', $wholesaler)) || empty(str_replace(' ', '', $branch)) ){
                            $email_wholesaler_branch_status = 0;
                        }else{
                            $email_wholesaler_branch_status = 1;
                        }
                        
                        if( isset($receipt_data['invoice_status']) && $receipt_data['invoice_status'] == 'failed' ){
                            $receipt_flag = false;
                            $status = 'under_review';
                            $data['detailed_msg'][] = $receipt_data['invoice_status_msg'];
                        }
                        
                        if( $eligible_products_false_flag ){
                            $eligible_products_false_flag = true;
                            $data['msg'] = "Your invoice does not include any eligible products";
                            $status = 'declined'; //declined, approved, requested
                            $manual_verification = false;
                            $status_comment = $data['msg'];
                            $receipt_flag = false;
                        }else if( $email_invoice > 0 && $email_wholesaler_branch_status == 0 ){
                            $status = 'under_review';
                            $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                            $data['detailed_msg'][] = 'Wholesaler/Branch account not found.';
                            $receipt_flag = false;
                            $status_comment = $data['msg'];
                        }
                        
                        if( $email_invoice > 0 ){
                            //$status = 'under_review';
                            $data['detailed_msg'][] = 'Emailed Invoice.';
                            //$receipt_flag = false;
                        }
    
                        global $wpdb;
                        $tablename = $wpdb->prefix . "customer_receipts";
                        $receipt_values = array(
                            'user_id' => $user_id,
                            'image_id' => $attachment_id,
                            'status' => $status,
                            'upload_date' => $upload_time,
                            'modified_date' => $modified_time,
                            'comment' => $status_comment,
                        );
                        
                        $receipt_values['points'] = $total_assigned_points;
                        
                        $receipt_values['nominated_wholesaler'] = false;
                        if( $nominated_wholesaler && strtolower($nominated_wholesaler) != 'no' ){
                            $receipt_values['nominated_wholesaler'] = true;
                        }
                        
                        if( $wholesaler ){
                            $receipt_values['wholesaler'] = $wholesaler;
                        }
                        if( $branch ){
                            $receipt_values['branch'] = $branch;
                        }
                        $query_status = $wpdb->insert(
                            $tablename,
                            $receipt_values
                        );
                        $data['query_status'] = $query_status;
                        if( $query_status ){
                            $data['insert_id'] = $wpdb->insert_id;
                            
                            //$data['receipt_values'] = $receipt_values;
                            $data['receipt_data'] = $receipt_data['product_codes'];
                            
                            $assigned_points = $total_assigned_points;
                            $data['points'] = $total_assigned_points;
                            $data['receipt_status'] = $status;
                            /*if( $receipt_flag && (!empty($total_assigned_points) || strtolower($user_state) == 'wa') ){
                                $data['status'] = 'Success';
                                $data['msg'] = "Invoice ".$receipt_data['invoice_number']." Uploaded Successfully";
                                $data['msg2'] = "Your invoice was successfully uploaded. ";
                                $data['msg3'] = "We have added ".(int)$assigned_points." points to your account.";
                                $data['detailed_msg'][] = 'Products are not scanned properly.';
                            }else if( empty($total_assigned_points) ){
                                $data['status'] = 'Failed';
                                $data['msg'] = "Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.";
                                $data['detailed_msg'][] = 'Products are not scanned properly.';
                            }*/
                            
                            if( isset($data['detailed_msg']) && !empty($data['detailed_msg']) ){
                                $receipt_data['detailed_msg'] = $data['detailed_msg'];
                            }
                            
                            $receipt_id = $wpdb->insert_id;
                            $return_data['receipt_id'] = $receipt_id;
                            
                            add_receipt_meta($receipt_id, 'currency', $receipt_data['currency']);
                            add_receipt_meta($receipt_id, 'type', 'app');
                            if( $email_invoice ){
                                add_receipt_meta($receipt_id, 'type', 'email_invoice');
                            }
                		    
                		    if( isset($receipt_data['shipto_name']) ){
                            	add_receipt_meta($receipt_id, 'shipto_name', $receipt_data['shipto_name']);
                            }
                            
                            if( isset($merchant_name) ){
                            	add_receipt_meta($receipt_id, 'buyer_name', $receipt_data['buyer_name']);
                            	add_receipt_meta($receipt_id, 'merchant_name', $receipt_data['buyer_name']);
                            }
                            if( isset($receipt_data['buyer_address']) ){
                            	add_receipt_meta($receipt_id, 'merchant_address', $receipt_data['buyer_address']);
                            }
                            if( isset($receipt_data['invoice_date']) && !empty($receipt_data['invoice_date']) ){
                            	$invoice_date = str_replace(' ', '', $receipt_data['invoice_date']);
                            	add_receipt_meta($receipt_id, 'receipt_date', $invoice_date);
                            }else if( empty($receipt_data['invoice_date']) ){
                                //$data['detailed_msg'][] = 'Invoice date not scanned.';
                            }
                            
                            if( isset($receipt_data['invoice_number']) ){
                            	add_receipt_meta($receipt_id, 'receipt_number', $receipt_data['invoice_number']);
                            }
                            
                            if( isset($receipt_data['invoice_amount']) ){
                            	add_receipt_meta($receipt_id, 'total_amount', $receipt_data['invoice_amount']);
                            }
                            
                            if( isset($receipt_data['total_tax']) ){
                            	add_receipt_meta($receipt_id, 'tax_amount', $receipt_data['total_tax']);
                            }
                            
                            if( isset($receipt_data['seller_name']) ){
                            	add_receipt_meta($receipt_id, 'seller_name', $receipt_data['seller_name']);
                            }
                            
                            if( isset($receipt_data['seller_address']) ){
                            	add_receipt_meta($receipt_id, 'seller_address', $receipt_data['seller_address']);
                            }
                            
                            if( isset($receipt_data['seller_phone']) ){
                            	add_receipt_meta($receipt_id, 'seller_phone', $receipt_data['seller_phone']);
                            }
                            
                            add_receipt_meta($receipt_id, 'products', $receipt_data['table_data']);
                            add_receipt_meta($receipt_id, 'wholesaler', $wholesaler);
                            add_receipt_meta($receipt_id, 'branch', $branch);
                            
                            foreach( $receipt_data as $rd_key => $rd_val ){
                                if( $rd_key != 'table_data' ){
                                    update_receipt_meta($receipt_id, $rd_key, $rd_val);
                                }
                            }
                            
                            
                            if( $receipt_flag ){
                                // $available_points = hager_current_user_points($user_id) + (int)$assigned_points;
                                // update_user_meta($user_id, 'whph_available_points', $available_points);
                                // calculate_percentage_point_used_wholesaler($wholesaler, $branch);
                                $whph_incentive_points = get_user_meta($user_id, 'whph_incentive_points', true);
                                if( 1==1 && (int)$assigned_points >= 75 && empty($whph_incentive_points) && $whph_incentive_points == false) {
                                    add_incentive_point($user_id, (int)$assigned_points, $wholesaler, $branch, $receipt_id);
                                }else{
                                    $available_points = hager_current_user_points($user_id) + (int)$assigned_points;
                                    update_user_meta($user_id, 'whph_available_points', $available_points);
                                }
                                calculate_percentage_point_used_wholesaler($wholesaler, $branch);
                            }
                        }
                    }
                    
                    if( 1==2 && isset($receipt_data['data_key']) ){
                        $data['msg'] .= $receipt_data['data_key'];
                    }
                }
                else{
                    $data['status'] = 'Failed';
                    $data['msg'] = "Sorry, please scan your invoice again...";
                    $data['detailed_msg'][] = "Sorry, please scan your invoice again...";
                    $receipt_flag = false;
                }
            }
        }
        
        $user_data = get_user_by('id', $user_id);
        $user_email = $user_data->user_email;
        
        $to = $user_email;
        $title = '';
        $subject = '';
        
        if( $status == 'declined' ){
            $title = 'Invoice has been declined.';
            if( $receipt_data['invoice_number'] ){
                $title = 'Invoice '.$receipt_data['invoice_number'].' has been declined.';
            }
            $subject = $data['msg'];
        }else if($status == 'under_review'){
            $title = 'Invoice is currently under review.';
            //$subject = $data['msg'];
            $subject = 'Your invoice requires a manual validation - if approved, please allow 24 to 48 hours for the points to appear in your account.';
        }else if($status == 'approved'){
            $title = 'Invoice '.$receipt_data['invoice_number'].' has been uploaded successfully.';
            $subject = 'Your invoice has been uploaded successfully. We have added '.(int)$assigned_points.' points to your account.';
        }
        
        $body = $data['msg'];
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        //send status email
        //wp_mail( $to, $title, $body, $headers );
        
        //$user_device_token = get_user_meta($user_id, 'device_tokens');
        
        if( $user_device_token && !empty($user_device_token) ){
            $fcm = new FCM();
            
            /*if( $data['status'] == 'Failed' ){
                $title = 'Invoice under review.';
                if( $receipt_data['invoice_number'] ){
                    $title = 'Invoice '.$receipt_data['invoice_number'].' under review.';
                }
                $subject = $data['msg'];
            }else{
                $title = 'Invoice '.$receipt_data['invoice_number'].' has been uploaded successfully.';
                $subject = 'Your invoice has been uploaded successfully. We have added '.(int)$assigned_points.' points to your account.';
            }*/
            
            $dType = '';
            $arrNotification = array();
            $arrNotification["body"] = $subject;
            $arrNotification["title"] = $title;
            $arrNotification["sound"] = "default";
            $arrNotification["type"] = 1;
            $upload_time = current_time( 'timestamp' );
            
            $result = $fcm->send_notification(array($user_device_token), $arrNotification,$dType);
            
            $meta_key = 'receipt_notification';
            $new_meta_key = 'new_receipt_notification';
            $user_notification = get_user_meta($user_id, $meta_key, true);
            $new_user_notification = get_user_meta($user_id, $new_meta_key, true);
            
            $temp = array();
            $temp['notifications_title'] = $title; 
            $temp['notifications_message'] = $subject; 
            $temp['notifications_date'] = $upload_time;
            
            if( $user_notification ){
                $user_notification[$upload_time] = $temp;
                krsort($user_notification);
                update_user_meta($user_id, $meta_key, $user_notification);
            }else{
                $user_notification = array();
                $user_notification[$upload_time] = $temp;
                update_user_meta($user_id, $meta_key, $user_notification);
            }
            
            if( $new_user_notification ){
                $new_user_notification[$upload_time] = $temp;
                krsort($new_user_notification);
                update_user_meta($user_id, $new_meta_key, $new_user_notification);
            }else{
                $new_user_notification = array();
                $new_user_notification[$upload_time] = $temp;
                update_user_meta($user_id, $new_meta_key, $new_user_notification);
            }
        }
        
        if( $email_invoice ){
           
            $return_data['status'] = $data['status'];
            
            $return_data['msg'] = $data['msg'];
            $return_data['link'] = site_url('user-invoice').'?edit_receipt='.$return_data['receipt_id'];
            $invoice_link = $return_data['link'];
            global $wpdb;
            $table_prefix = $wpdb->prefix;
            $table_name = $table_prefix . 'emailImap_invoice_scan';
            $buyer_name = $receipt_data['buyer_name'];
            $row_data = $wpdb->get_row($wpdb->prepare("SELECT ID, dummy_user_id, from_email_address FROM {$table_name} WHERE email_id_scanned = %s", $email_invoice), ARRAY_A);
            $msgN =  "receiptid=".$receipt_id."&nbsp";
             $msgN .=  "invoice_number=".$invoice_number."&nbsp";
             $msgN .=  "invoice_link=".$invoice_link."&nbsp";
             $fromEmaiAddr='';
            $rowID = $rowDummyUserID = '';
                if ($row_data !== null) {
                    $rowID = $row_data['ID'];
                    $rowDummyUserID = $row_data['dummy_user_id'];
                    $fromEmaiAddr = $row_data['from_email_address'];
                    //update_user_meta( $rowDummyUserID, "business_name_abn",  $buyer_name ) ;
                    $fromEmaiAddr = $row_data['from_email_address'];
                    $buyer_name = explode(" ", $buyer_name);
                    $f_name = 'Email Invoice User';
                    
                    //$f_name = array_shift($buyer_name);
                    $l_name = $invoice_number;
                   $msgN .= "rowdID=".$rowID."&nbsp";
                    $msgN .= "Dummyuser_id=".$rowDummyUserID."&nbsp";
                    $msgN .= "NEWuser_id=".$user_id."&nbsp";
                    update_user_meta( $rowDummyUserID, "first_name",  'Email' ) ;
                    update_user_meta( $rowDummyUserID, "last_name", 'Invoice User'  ) ;
                     $msgN .= "firstname=".$f_name."&nbsp";
                    $msgN .= "lastname=".$l_name."&nbsp";
                     $msgN .= "fromEmail=".$fromEmaiAddr."&nbsp";
                    if( $email_invoice_user > 0 && $rowDummyUserID != $user_id ){
                        $wpdb->update(
                            $table_name,
                            array('dummy_user_id' => $email_invoice_user, 'invoice_id' => $invoice_number, 'receipt_id' => $receipt_id),
                            array('ID' => $row_data['ID']),
                            array('%d', '%s', '%s'),
                            array('%d')
                        );
                        wp_delete_user($rowDummyUserID);
                    }else{
                        $wpdb->update(
                            $table_name,
                            array('invoice_id' => $invoice_number, 'receipt_id' => $receipt_id),
                            array('ID' => $row_data['ID']),
                            array('%s', '%s'),
                            array('%d')
                        );
                    }
                }
                $msg = '';
                if( $invoice_number > 0 ){
                    //echo "yes with 0";
                    $msg = "We have received invoice ".$invoice_number." - the details will be reviewed and if approved, points will be uploaded to the relevant customer's account.";
                    
                }
                else{
                   $msg ="We have received invoice, the details will be reviewed and if approved, points will be uploaded to the relevant customer's account.";
                }
                
                $msg .= '<br/><br/>If the customer does not have a WHPH account, the invoice will not be approved and we will need to be notified once the customer creates an account.';
                
                $to = $fromEmaiAddr;
                $subject = 'WHPH Invoice Received';
                $email_header_img = site_url() . '/wp-content/uploads/2024/02/WHPH24-EDM-Header.jpg';
                if (get_field('whph_email_header_image', 'option') && !empty(get_field('whph_email_header_image', 'option'))) {
                    $email_header_img = get_field('whph_email_header_image', 'option');
                }
    $message = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;UTF-8" />
    </head>
    <body style="margin: 0px; background-color: #F4F3F4; font-family: Helvetica, Arial, sans-serif; font-size:12px;" text="#444444" bgcolor="#F4F3F4" link="#21759B" alink="#21759B" vlink="#21759B" marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">
        <table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#F4F3F4">
            <tbody>
                <tr>
                    <td style="padding: 15px;">
                        <center>
                            <table width="550" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFF">
                                <tbody>
                                    <tr>
                                        <td align="left">
                                            <div style="">
                                                <table id="header" style="line-height: 1.6; font-size: 12px; font-family: Helvetica, Arial, sans-serif;color: #000; width:100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent">
                                                    <tbody>
                                                        <tr>
                                                            <td style="line-height: 32px;" valign="baseline"><span style="font-size: 32px;"><a style="text-decoration: none;" href="' . home_url() . '" target="_blank" rel="noopener"><img src="' . $email_header_img . '" alt="' . get_bloginfo('name') . '" itemprop="logo" style="width: 100%"></a></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <!-- Your dynamic content goes here -->
                                                <table id="content" style="color: #444; line-height: 1.6; font-size: 12px; font-family: Arial, sans-serif; width: 100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-bottom:15px;border-top: solid 1px transparent; padding-left: 40px; padding-right: 40px;" colspan="2">
                                                                '.$msg.'
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <!-- Your footer goes here -->
                                                <table id="footer" style="line-height: 1.5; font-size: 12px; font-family: Arial, sans-serif; width:100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                                    <tbody>
                                                        <tr style="font-size: 11px; color: #999999;">
                                                            <td style="border-top: solid 1px #d9d9d9;  padding-left: 30px; padding-right: 30px;" colspan="2">
                                                                <div style="padding-top: 15px; padding-bottom: 1px;">
                                                                    -- This e-mail was sent from "' . get_bloginfo('name') . '" <a href="' . home_url() . '">"' . home_url() . '"</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color: #ffffff;" colspan="2" height="15">.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </center>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>';
$headers = array('Content-Type: text/html; charset=UTF-8');
                //$headers = "Content-Type: text/html; charset=UTF-8\r\n";
                
                //$message = 'Hi Admin,<br/><br/>';
                //$message .= $msg;
                //$message .= '<a href="' . $invoice_link . '">' . $invoice_link . '</a><br/><br/>';
                //$message  .='Thanks!';
                if( !empty($to) ){
                    wp_mail($to, $subject, $message, $headers);
                }else{
                    $to = 'support@domani.com.au';
                    $message = 'We have received invoice, but from emai-address is not found.<br/>Please check the invoice: '.$upload['url'];
                    wp_mail($to, $subject, $message, $headers);
                }
                
                
                $log_file = ABSPATH . 'custom-log.txt';

            // Format the log entry with timestamp
            $log_entry = date('Y-m-d H:i:s') . ' - ' . $msgN . PHP_EOL;
        
            // Append the log entry to the log file
            file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
            //return $return_data;
        }
        /*$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;*/
    }