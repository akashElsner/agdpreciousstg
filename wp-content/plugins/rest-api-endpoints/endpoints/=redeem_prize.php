<?php

    /*{
    	"user_id": 1,
    	"product_id": 5971,
        "variation_id": 5976,
    	"redeem_nonce": "94b5797d7c",
    	"variations":
    	{
    		"attribute_pa_color": "grey-camo",
    		"attribute_freight": "Regional"
    	}
    }*/
    function rest_redeem_prize_callback( $request  ){
        // $user_id = $request->get_param( 'user_id' );
        // $redeem_nonce = $request->get_param( 'redeem_nonce' );
        // $product_id = $request->get_param( 'product_id' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        $body = json_decode($request->get_body());
        $data['body'] = $body;
        
        if( $body ){
            $data['status'] = 'Success';
            $user_id = $body->user_id;
            $product_id = $body->product_id;
            $variation_id = 0;
            $data['product_id'] = $body->product_id;
            
            $match_attributes = (array)$body->variations;
            $data['variations'] = $body->variations;
            
            $redeem_nonce = $body->redeem_nonce;
            $data['redeem_nonce'] = $body->redeem_nonce;
            
            $verification = wp_verify_nonce($redeem_nonce, "redeem-prize-".$user_id);
           
            $data['verification'] = $verification;
            if( $verification ){
                if( $match_attributes ){
                    $product = wc_get_product($product_id);
                    $available_variations = $product->get_available_variations();
                    
                    if($available_variations){
                        foreach($available_variations as $key => $val){
                            // $data['attr_check'][$key]['arr'] = $val['attributes'];
                            // $data['attr_check'][$key]['arr_check'] = $match_attributes;
                            // $data['attr_check'][$key]['result'] = array_diff_assoc($val['attributes'], $match_attributes);
                            
                            if ( array_diff_assoc((array)$val['attributes'], (array)$match_attributes) == [] && $val['is_in_stock'] ) {
                                $data['attr'] = $val['attributes'];
                                $data['variation_id'] = $val['variation_id'];
                                $variation_id = $val['variation_id'];
                                break;
                            }
                            //$check_attr = array_diff($val['attributes'], $match_attributes);
                        }
                    }
                }
                
                /*
                REDEEM PRODUCT
                */
			
            }else{
                $data['status'] = 'Failed';
                $data['msg'] = "Nonce verification!";
            }
        }
        
        //$verification = wp_verify_nonce("redeem-prize-".$user_id);
        
        
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }