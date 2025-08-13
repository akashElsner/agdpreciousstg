<?php
    function rest_receipt_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
    
        if( $user_id && $user_id > 0 ){
            global $wpdb;
            // Current site prefix
            $prefix = $wpdb->prefix;
            $table_name = $prefix.'customer_receipts';
            $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY `upload_date` DESC");
            if( $results ){
                foreach ($results as $key => $value) { 
                    $receipt_arr = array();
                   if($value->user_id == $user_id){
                        $image_id = $value->image_id;
                        $image_url = wp_get_attachment_url($image_id, 'large');
                       
                        $receipt_id = $value->ID;
                        $status = $value->status;
                        $comment = $value->comment;
                        $upload_date = $value->upload_date;
                        $modified_date = $value->modified_date;
                        
                        $receipt_arr['receipt_id'] = $receipt_id;
                        
                        $invoice_check_result = $wpdb->get_results( "SELECT meta_value FROM {$wpdb->prefix}customer_receipts_meta WHERE `meta_key` LIKE 'receipt_number' AND `receipt_id` = ".$receipt_id." LIMIT 1", ARRAY_A );
                        $invoice_number = $invoice_check_result[0]['meta_value'];
                        
                        $receipt_arr['invoice_number'] = ($invoice_number) ? $invoice_number : '';
                        $receipt_arr['receipt_name'] = 'Invoice_'.$receipt_id;
                        $receipt_arr['image_id'] = $image_id;
                        $thumb_url = wp_get_attachment_image_src( $image_id, 'full' );
                        $receipt_arr['image_url'] = (isset($thumb_url[0])) ? $thumb_url[0] : '';
                        $receipt_arr['formatted_date'] = date('d.m.Y', $upload_date);
                        $receipt_arr['upload_date'] = $upload_date;
                        $receipt_arr['modified_date'] = $modified_date;
                        $receipt_arr['status'] = $status;
                        $receipt_arr['comment'] = $comment;
                        //$receipt_arr['points'] = ($value->$points) ? $value->$points : '';
                        $receipt_arr['vouchers_count'] = ($value->points) ? (int)$value->points : 0;
                        /*if( $vouchers ){
                            $temp_vouchers = explode(',', $vouchers);
                            $receipt_arr['vouchers_count'] = (count($temp_vouchers) > 0) ? count($temp_vouchers) : 0;
                        }*/

                        $data['receipts'][] = $receipt_arr;
                    }
                }
                
                $data['status'] = 'Success';
                $data['msg'] = "";
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }