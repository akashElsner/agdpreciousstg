<?php
    function rest_product_variation_id_callback( $request  ){
        $record = $request->get_body();
        
        $verification = wp_verify_nonce("redeem-prize-".$user_id);
        
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        if( $record ){
            $temp_record = json_decode($record);
            $data['status'] = 'Success';
            $product_id = $temp_record->product_id;
            
            $product = wc_get_product( $product_id ); 
            $attributes = $product->get_variation_attributes(); 
            
            $data['attributes'] = $attributes;
            $data['product_id'] = $temp_record->product_id;
            $match_attributes = $temp_record->variations;
            $data['variations'] = $temp_record->variations;
            
            /*$data_store   = WC_Data_Store::load( 'product' );
            $variation_id = (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new \WC_Product($product_id),
        $match_attributes
    );*/
            
            $data['variation_id'] = $variation_id;
        }
        
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }