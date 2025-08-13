<?php
    function rest_add_receipt_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        $receipt = $request->get_file_params( 'receipt' );
        $nominated_wholesaler = $request->get_param( 'nominated_wholesaler' );
        $wholesaler = $request->get_param( 'wholesaler' );
        $branch = $request->get_param( 'branch' );
        $invoice_id = $invoice_date = '';
        $amount = 0;
        
        if( $nominated_wholesaler && strtolower($nominated_wholesaler) != 'no' ){
            $wholesaler = get_user_meta($user_id,'wholesale_group',true);
            $branch = get_user_meta($user_id,'wholesale_branch',true);
        }
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
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
    
        if( $user_id && $user_id > 0 && $receipt ){
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $upload = wp_handle_upload( 
                $receipt['receipt'], 
                array( 'test_form' => false ) 
            );
            
            //$data['upload'] = $upload;
            
            if( ! empty( $upload[ 'error' ] ) ) {
                $data['status'] = 'Failed';
                $data['msg'] = "Upload Error: Something went wrong!";    
            }else{
                $receipt_data = array();
                $receipt_data = nanoInvoiceOcr($upload['url'], $user_id);
                
                $receipt_flag = true;
                if( !is_array($receipt_data) ){
                    $data['status'] = 'Failed';
                    $data['msg'] = "Receipt Scan Error: Something went wrong!";
                    $receipt_flag = false;
                }
                
                if( is_array($receipt_data) ){
                    
                    $temp_merchant_name = (trim(strval($receipt_data['buyer_name']))) ? str_replace( ' ', '', trim(strval($receipt_data['buyer_name']))) : '';
                    $current_user_business_name = str_replace( ' ', '', trim(strval(get_user_meta($user_id, 'business_name_abn', true))) );
                    $temp_shipto_name = ($receipt_data['shipto_name']) ? $receipt_data['shipto_name'] : '';
                    
                    $merchant_flag = true;
                    if( $temp_merchant_name && !empty($temp_merchant_name) ){
                        $merchant_name = str_replace('
', ' ', $temp_merchant_name);
                        if( $merchant_name == $current_user_business_name ){
                            $merchant_flag = false;
                        }
                    }else if( $temp_shipto_name ){
                        foreach($temp_shipto_name as $k => $v){
                            $shipto_name = str_replace('
', ' ', $v);
                            if( $shipto_name == $current_user_business_name ){
                                $merchant_flag = false;
                                break;
                            }
                        }
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
                        $data['msg'] = "Error: Please check your Business Name";
                        $receipt_flag = false;
                    }
                    
                    $invoice_number = (isset($receipt_data['invoice_number'])) ? $receipt_data['invoice_number'] : 0;
                    if( $receipt_flag && $invoice_number > 0 &&  invoice_check($invoice_number, $wholesaler, $branch)){
                        $data['status'] = 'Failed';
                        $data['msg'] = "This invoice has already been uploaded.";
                        $receipt_flag = false;
                    }else{
                        update_user_meta($user_id, 'receipt_data_'.$receipt_data['invoice_number'], $receipt_data);
                    }
                    
                    $invoice_date = ($receipt_data['Date-totime']) ? $receipt_data['Date-totime'] : '';
                
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
                        $data['msg'] = "Attachment Error: Something went wrong!";
                    }else{
                        $assigned_points = 0;
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                        wp_update_attachment_metadata(
                            $attachment_id,
                            wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
                        );
                        
                        $data['image_id'] = $attachment_id;
                        $data['image_url'] = wp_get_attachment_url($attachment_id);
    
                        $status = $status_comment = '';
                        if( $receipt_flag ){
                            $status = 'approved'; //approved
                            $status_comment = '';
                        }else{
                            $status = 'declined'; //declined, approved, requested
                            $status_comment = $data['msg'];
                        }
                        $upload_time = ($receipt_data['time']) ? $receipt_data['time'] : current_time( 'timestamp' );
                        $modified_time = $upload_time;
                        
                        $total_assigned_points = get_total_assigned_points($receipt_data['table_data']);
		
                		if( $total_assigned_points == 0 || empty($total_assigned_points) ){
                		    $total_assigned_points = $receipt_data['total_assigned_points'];
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
                            if( $receipt_flag ){
                                $data['status'] = 'Success';
                                $data['msg'] = "Added successfully!";
                            }else{
                                $data['status'] = 'Failed';
                            }
                            
                            $receipt_id = $wpdb->insert_id;
                            
                            add_receipt_meta($receipt_id, 'currency', $receipt_data['currency']);
                		    
                		    if( isset($receipt_data['shipto_name']) ){
                            	add_receipt_meta($receipt_id, 'shipto_name', $receipt_data['shipto_name']);
                            }
                            
                            if( isset($merchant_name) ){
                            	add_receipt_meta($receipt_id, 'merchant_name', $receipt_data['buyer_name']);
                            }
                            if( isset($receipt_data['buyer_address']) ){
                            	add_receipt_meta($receipt_id, 'merchant_address', $receipt_data['buyer_address']);
                            }
                            if( isset($receipt_data['invoice_date']) ){
                            	$invoice_date = str_replace(' ', '', $receipt_data['invoice_date']);
                            	add_receipt_meta($receipt_id, 'receipt_date', $invoice_date);
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
                            
                            
                            if( $receipt_flag ){
                                $available_points = hager_current_user_points($user_id) + (int)$assigned_points;
                                update_user_meta($user_id, 'whph_available_points', $available_points);
                            }
                        }
                    }
                    
                    if( 1==2 && isset($receipt_data['data_key']) ){
                        $data['msg'] .= $receipt_data['data_key'];
                    }
                }
                /*else{
                    $data['status'] = 'Failed';
                    $data['msg'] = "Receipt Scan Error: Something went wrong!";
                }*/
            }
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }