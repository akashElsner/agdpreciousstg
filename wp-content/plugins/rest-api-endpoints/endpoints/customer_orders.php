<?php
    function rest_customer_orders_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        //$order_details_nonce = $request->get_param( 'order_details_nonce' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        //$order_details_nonce = $request->get_param('order_details_nonce');
        //$data['redeem_nonce'] = $request->get_param('order_details_nonce');
        
        //$verification = wp_verify_nonce($order_details_nonce, "order_details-".$user_id);
        $verification = 1;
        $data['verification'] = $verification;
        
        $status_label = array();
        $status_label['on-hold'] = 'New';
        $status_label['pending'] = 'Approved';
        $status_label['processing'] = 'Shipping';
        $status_label['completed'] = 'Delivered';
        
        if( $verification ){
            $customer_orders = get_posts(array(
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_value' => $user_id,
                'post_type' => wc_get_order_types(),
                'post_status' => array_keys( wc_get_order_statuses() ),
            ));
            
            //$data['$customer_orders'] = $customer_orders;
            $Order_Array = []; //
            $i = 0;
            foreach ($customer_orders as $customer_order) {
                $orderq = wc_get_order($customer_order);
                $items = $orderq->get_items();
                $order_status  = $status_label[$orderq->get_status()];
                
                $product_image = '';
                foreach ( $items as $item ) {
                    $product_id = $item->get_product_id();
                    $product_title = get_the_title($item->get_product_id());
                    $product_obj = wc_get_product( (int)$product_id );
                    $variation_id = $item->get_variation_id();
                    $image_id = get_post_thumbnail_id( $product_id );
                    if( isset($variation_id) && $variation_id > 0 ){
                        $variation = new WC_Product_Variation( $variation_id );
                        $product_title = get_the_title($variation_id);
                        $image_id = $variation->get_image_id();
                    }
                    if( $product_obj ){
                        $Order_Array[$i] = [
                            "ID" => $orderq->get_id(),
                            "Value" => $orderq->get_total(),
                            "Date" => $orderq->get_date_created()->date_i18n('d-m-Y'),
                            "Status" => $order_status,
                            "Product Name" => html_entity_decode($product_title),
                            "image_id" => $image_id
                        ];
                        $product = get_product((int)$product_id)->get_data();
                        
                        $product_images_id = $product_obj->get_gallery_image_ids(); 
                        $product_type = $product_obj->get_type(); 
                        $product['type'] = $product_type;
                        $product['quantity'] = $item->get_quantity();  
                    
                        array_unshift($product_images_id, get_post_thumbnail_id($product_id));
                        
                        foreach( $product_images_id as $attachment_id ){
                            $product_image_url = wp_get_attachment_url( $attachment_id );
                            if( empty($product_image) ){
                                $product_image = $product_image_url;
                            }
                            $image_details = array();
                            $image_details['id'] = $attachment_id;
                            $image_details['src'] = $product_image_url;
                            $image_details['name'] = get_the_title($attachment_id);
                            
                            $product['images'][] = $image_details;
                        }
                        
                        $Order_Array[$i]['products'][] = $product;
                    }
                }
                if( $product_image ){
                    $Order_Array[$i]['productimage'] = $product_image;
                }
                
                
                $i++;
            }
            
            $data['orders'] = $Order_Array;
            
            $data['status'] = 'Success';
            $data['msg'] = "List of all the Orders";
        }else{
            $data['status'] = 'Failed';
            $data['msg'] = "Error: Nonce verification!";
        }
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }
?>