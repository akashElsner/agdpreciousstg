<?php
    function rest_product_variations_callback( $request  ){
        $product_id = $request->get_param('product_id');
        
        $verification = wp_verify_nonce("redeem-prize-".$user_id);

        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        $attributes_label = array();
        $attributes_variations = array();
        
        $attributes_label_slug = array();
        $attributes_variations_slug = array();
        
        if( $product_id ){
            $data['status'] = 'Success';
            $data['msg'] = "";
            
            $product = wc_get_product( $product_id ); 
            $attributes = $product->get_variation_attributes(); 
            
            foreach($attributes as $key => $val){
                $attributes_label[$key] = wc_attribute_label($key);
                $attributes_label_slug[wc_attribute_label($key)] = $key;
                
                foreach( $val as $k => $v ){
                    $temp_val = ( $term = get_term_by( 'slug', $v, $key ) ) ? $term->name : $v;
                    $attributes_variations[$key][$v] = $temp_val;
                    $attributes_variations_slug[wc_attribute_label($key)][$temp_val] = $v;
                }
            }
            
            $data['attributes'] = $attributes;
            $data['attributes_label'] = $attributes_label;
            $data['attributes_label_slug'] = $attributes_label_slug;
            $data['attributes_variations'] = $attributes_variations;
            $data['attributes_variations_slug'] = $attributes_variations_slug;
            $available_variations = $product->get_available_variations();
            
            if($available_variations){
                foreach($available_variations as $key => $val){
                    $data['variation_ids'][implode('-', $val['attributes'])] = $val['variation_id'];
                    $data['variation_ids_images'][$val['variation_id']] = (get_post_meta($val['variation_id'], '_thumbnail_id', true));
                }
            }
            //$data['available_variations'] = $product->get_available_variations();;
            
        }
        
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }