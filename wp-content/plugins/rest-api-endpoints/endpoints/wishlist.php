<?php
    function rest_wishlist_callback( $request  ){
        $user_id = $request->get_param( 'user_id' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
    
        if( $user_id && $user_id > 0 ){
            global $wpdb;
            // Current site prefix
            $prefix = $wpdb->prefix;
            $table_name = $prefix.'yith_wcwl';
            $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id = $user_id");
            if( $results ){
                foreach ($results as $key => $value) { 
                   if($value->user_id == $user_id){
                        $product_id = $value->prod_id;
                        //$product = get_post( $product_id );
                        
                        $product_obj = wc_get_product( (int)$product_id );
                        //$product = $product_obj->get_data();
                        if( $product_obj ){
                            $product = get_product((int)$product_id)->get_data();
                            
                            $product_images_id = $product_obj->get_gallery_image_ids(); 
                            $product_type = $product_obj->get_type(); 
                            $product['type'] = $product_type;
                            array_unshift($product_images_id, get_post_thumbnail_id($product_id));
                            
                            foreach( $product_images_id as $attachment_id ){
                                $product_image_url = wp_get_attachment_url( $attachment_id );
                                $image_details = array();
                                $image_details['id'] = $attachment_id;
                                $image_details['src'] = $product_image_url;
                                $image_details['name'] = get_the_title($attachment_id);
                                
                                $product['images'][] = $image_details;
                            }
                            
                            
                            if( $value->on_sale ){
                                $value->on_sale = true;
                            }else{
                                $value->on_sale = false;
                            }
                            
                            $product_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
                            $img = $product_image_url[0];
                            $value->product_data = $product;
                            $value->product_image_url = $img;
                            $data['wishlist'][] = $value;
                        }
                    }
                }
                
                $data['status'] = 'Success';
                $data['msg'] = '';
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }