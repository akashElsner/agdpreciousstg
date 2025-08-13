<?php
    add_action("rest_api_init", function () {

        $meta = array();
        $meta[] = 'mobile';
        $meta[] = 'insta_handle';
        $meta[] = 'tshirt_size';
        $meta[] = 'display_name';
        $meta[] = 'business_name_abn';
        $meta[] = 'business_abn_num';
        
        $meta[] = 'billing_first_name';
        $meta[] = 'billing_last_name';
        $meta[] = 'billing_company';
        $meta[] = 'billing_address_1';
        $meta[] = 'billing_address_2';
        $meta[] = 'billing_city';
        $meta[] = 'billing_state';
        $meta[] = 'billing_postcode';
        $meta[] = 'billing_phone';
        
        $meta[] = 'shipping_first_name';
        $meta[] = 'shipping_last_name';
        $meta[] = 'shipping_company';
        $meta[] = 'shipping_address_1';
        $meta[] = 'shipping_address_2';
        $meta[] = 'shipping_city';
        $meta[] = 'shipping_state';
        $meta[] = 'shipping_postcode';
        $meta[] = 'shipping_phone';
        
        $meta[] = 'wholesale_group';
        $meta[] = 'wholesale_branch';
        
        
        foreach ($meta as $key => $value) {
            register_rest_field(
                  "user", $value,
                [
                    "get_callback" => function ($user, $field_name, $request, $object_type) {
        
                        return get_user_meta($user["id"], $field_name, TRUE);
        
                    },
                    "update_callback" => function ($value, $user, $field_name, $request, $object_type) {
                        
                        if( $field_name == 'display_name' ){
                            $userdata = array( 
                                'ID' => $user->ID, 
                                'display_name' => $value, 
                            ); 
                             
                            wp_update_user($userdata);
                        }else{
                            update_user_meta($user->ID, $field_name, $value);
                        }
        
                    },
                ]
            );
        }
    
    
    });
    
    function get_wishlist_products( $user_id ){
        global $wpdb;
        // Current site prefix
        $prefix = $wpdb->prefix;
        $table_name = $prefix.'yith_wcwl';
        $results = $wpdb->get_results( "SELECT prod_id FROM $table_name WHERE user_id = $user_id");
        
        $data = array();
        
        foreach ($results as $key => $value) {
            $data[] = $value->prod_id;
        }
        
        return $data;
    }
    
    function get_wishlist_id($user_id){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $yith_wcwl_lists = $prefix . 'yith_wcwl_lists';
        $results_list = $wpdb->get_results("SELECT * FROM $yith_wcwl_lists WHERE user_id = $user_id");
        
        if (!empty($results_list)) {
            foreach ($results_list as $key => $value) {
                if ($user_id == $value->user_id) {
                    $wishlist_id = $value->ID;
                    return $wishlist_id;
                }
            }
        }
    }
    
    function generate_wishlist_token(){
        global $wpdb;
    
        $sql = "SELECT COUNT(*) FROM `{$wpdb->yith_wcwl_wishlists}` WHERE `wishlist_token` = %s";
    
        do {
            $dictionary = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $nchars     = 12;
            $token      = '';
    
            for ($i = 0; $i <= $nchars - 1; $i++) {
                $token .= $dictionary[wp_rand(0, strlen($dictionary) - 1)];
            }
    
            $count = $wpdb->get_var($wpdb->prepare($sql, $token)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        } while ($count);
    
        return $token;
    }


    function get_profile_details( $user_id ) {
        $user_info = get_user_by( 'id',  $user_id );
        
        $profile = array (
            'id' => $user_info->id,
            'first_name' => $user_info->first_name,
            'last_name' => $user_info->last_name,
            'email' => $user_info->user_email,
            'mobile' => get_user_meta( $user_id, 'mobile', true ),
            'insta_handle' => get_user_meta( $user_id, 'insta_handle', true ),
            'tshirt_size' => get_user_meta( $user_id, 'tshirt_size', true ),
            'nicename' => $user_info->user_nicename,
            'display_name' => $user_info->display_name,
        );
        return $profile;
    }

    function get_business_details( $user_id ) {
        
        $business = array (
            'business_name' => get_user_meta($user_id,'business_name_abn',true),
            'business_abn' => get_user_meta($user_id,'business_abn_num',true),
            'billing_address' => get_billing_address($user_id),
            'shipping_address' => get_shipping_address($user_id),
            
            'wholesaler_group' => get_user_meta($user_id,'wholesale_group',true),
            'wholesale_branch' => get_user_meta($user_id,'wholesale_branch',true),
        );
        return $business;
    }

    function get_wholesaler_details( $user_id ) {
        
        $wholesaler = array (
            'group' => get_user_meta($user_id,'wholesale_group',true),
            'branch' => get_user_meta($user_id,'wholesale_branch',true),
        );
        return $wholesaler;
    }
    
    function get_billing_address( $user_id ){
        $billing_address = array();
        
        $billing_array = array();
        $temp_billing_array = array();
        
        if( get_user_meta($user_id,'billing_address_1',true) ){
            $billing_array[] = get_user_meta($user_id,'billing_address_1',true);
        }
        if( get_user_meta($user_id,'billing_city',true) ){
            $temp_billing_array[] = get_user_meta($user_id,'billing_city',true);
        }
        if( get_user_meta($user_id,'billing_state',true) ){
            $temp_billing_array[] = get_user_meta($user_id,'billing_state',true);
        }
        if( get_user_meta($user_id,'billing_postcode',true) ){
            $temp_billing_array[] = get_user_meta($user_id,'billing_postcode',true);
        }
        
        $billing_address_text = '';
        if( $billing_array ){
            $billing_address_text = implode(', ', $billing_array);
            if( $temp_billing_array ){
                $billing_address_text .= ', '.implode(' ', $temp_billing_array);
            }
        }
        
        $billing_address['billing_full_address'] = $billing_address_text;
        $billing_address['billing_address_1'] = get_user_meta($user_id,'billing_address_1',true);
        $billing_address['billing_address_2'] = get_user_meta($user_id,'billing_address_2',true);
        $billing_address['billing_city'] = get_user_meta($user_id,'billing_city',true);
        $billing_address['billing_state'] = get_user_meta($user_id,'billing_state',true);
        $billing_address['billing_postcode'] = get_user_meta($user_id,'billing_postcode',true);
        $billing_address['billing_country'] = get_user_meta($user_id,'billing_country',true);
        
        return $billing_address;
    }
    
    function get_shipping_address( $user_id ){
        $shipping_array = array();
        $temp_shipping_array = array();
        
        if( get_user_meta($user_id,'shipping_address_1',true) ){
            $shipping_array[] = get_user_meta($user_id,'shipping_address_1',true);
        }
        if( get_user_meta($user_id,'shipping_city',true) ){
            $temp_shipping_array[] = get_user_meta($user_id,'shipping_city',true);
        }
        if( get_user_meta($user_id,'shipping_state',true) ){
            $temp_shipping_array[] = get_user_meta($user_id,'shipping_state',true);
        }
        if( get_user_meta($user_id,'shipping_postcode',true) ){
            $temp_shipping_array[] = get_user_meta($user_id,'shipping_postcode',true);
        }
        
        $shipping_address_text = '';
        if( $shipping_array ){
            $shipping_address_text = implode(', ', $shipping_array);
            if( $temp_shipping_array ){
                $shipping_address_text .= ', '.implode(' ', $temp_shipping_array);
            }
        }
        
        $shipping_address['shipping_full_address'] = $shipping_address_text;
        $shipping_address['shipping_address_1'] = get_user_meta($user_id,'shipping_address_1',true);
        $shipping_address['shipping_address_2'] = get_user_meta($user_id,'shipping_address_2',true);
        $shipping_address['shipping_city'] = get_user_meta($user_id,'shipping_city',true);
        $shipping_address['shipping_state'] = get_user_meta($user_id,'shipping_state',true);
        $shipping_address['shipping_postcode'] = get_user_meta($user_id,'shipping_postcode',true);
        $shipping_address['shipping_country'] = get_user_meta($user_id,'shipping_country',true);
        
        return $shipping_address;
    }