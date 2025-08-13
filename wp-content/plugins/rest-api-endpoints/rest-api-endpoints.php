<?php
    /*
     Plugin Name: Rest API Endpoints
     Description: rest api creation with the site-content
     Plugin URI: https://www.elsner.com/
     Author: Elsner Technologies
     Author URI: https://www.elsner.com/
     Version: 0.1
    */
    
    function mod_jwt_auth_token_before_dispatch( $data, $user ) {
        $user_info = get_user_by( 'email',  $user->data->user_email );
        //$account_status = get_user_meta($user_info->id, 'account_status', true);
        $account_status = (get_user_meta($user_info->id, 'account_status', true)) ? get_user_meta($user_info->id, 'account_status', true) : 'true';
        $profile = get_profile_details( $user_info->id );
        $business = get_business_details( $user_info->id );
        $wholesaler = get_wholesaler_details( $user_info->id );
        $wishlist = get_wishlist_products( $user_info->id );
        $redeem_nonce = wp_create_nonce("redeem-prize-".$user_info->id);
        $order_details_nonce = wp_create_nonce("order_details-".$user_info->id);
        
        //'account_status' => $account_status,
        $response = array(
            'data' => $data,
            
            'token' => $data['token'],
            'redeem_nonce' => $redeem_nonce,
            'order_details_nonce' => $order_details_nonce,
            'profile' => $profile,
            'business' => $business,
            'wholesaler' => $wholesaler,
            'wishlist' => $wishlist
        );
        return $response;
    }
    add_filter( 'jwt_auth_token_before_dispatch', 'mod_jwt_auth_token_before_dispatch', 10, 2 );
    
    //add_filter('jwt_auth_expire', 'on_jwt_expire_token',10,1);	
    function on_jwt_expire_token($exp){		
    	$days = 1;
    	$exp = time() + (86400 * $days);			
    	return $exp;
    }
    

    add_action('init', function (){
        global $rest_api_arr;
        global $rest_api_arr_post;
        global $rest_api_pages;
        
        $rest_api_arr[] = 'header';
        $rest_api_arr[] = 'total_available_vouchers';
        $rest_api_arr[] = 'wholesalers';
        $rest_api_arr[] = 'wishlist_ids';
        $rest_api_arr[] = 'wishlist';
        $rest_api_arr[] = 'receipt';
        $rest_api_arr[] = 'wpsl_stores_list';
        $rest_api_arr[] = 'whph_faq';
        $rest_api_arr[] = 'product_categories';
        $rest_api_arr[] = 'customer_orders';
        $rest_api_arr[] = 'notifications';
        
        $rest_api_arr_post[] = 'add_wishlist';
        $rest_api_arr_post[] = 'update_wishlist';
        $rest_api_arr_post[] = 'delete_wishlist';
        $rest_api_arr_post[] = 'clear_wishlist';
        $rest_api_arr_post[] = 'redeem_prize';
        $rest_api_arr_post[] = 'product_variations';
        $rest_api_arr_post[] = 'product_variation_id';
        
        
        $rest_api_arr_post[] = 'add_receipt';

        
        $rest_api_arr[] = 'token_reset';
        //$rest_api_arr_post[] = 'login';
        //$rest_api_arr_post[] = 'logout';
        
        global $rest_api_services_arr;
        
        foreach ($rest_api_arr as $key => $value) {
            if( $value ){
                require(plugin_dir_path( __FILE__ ).'endpoints/'.$value.'.php');
            }
        }
        
        foreach ($rest_api_arr_post as $key => $value) {
            if( $value ){
                require(plugin_dir_path( __FILE__ ).'endpoints/'.$value.'.php');
            }
        }
    });
    
    add_action( 'rest_api_init', function () {
        global $rest_api_arr;
        global $rest_api_arr_post;
        
        if( $rest_api_arr ){
            foreach ($rest_api_arr as $key => $value) {
                if( $value ){
                    $temp_ep = $value.'/';
                    $temp_callback = 'rest_'.$value.'_callback';
                    
                    register_rest_route( 'v1', $temp_ep, array(
                		'methods'  => 'GET',
                		'callback' => $temp_callback,
                		'permission_callback' => '__return_true'
                	) );
                	
                }
            }
        }
        
        if( $rest_api_arr_post ){
            foreach ($rest_api_arr_post as $key => $value) {
                if( $value ){
                    $temp_ep = $value.'/';
                    $temp_callback = 'rest_'.$value.'_callback';
                    
                    register_rest_route( 'v1', $temp_ep, array(
                		'methods'  => 'POST',
                		'callback' => $temp_callback,
                		'permission_callback' => '__return_true'
                	) );
                }
            }
        }
    });

function wprc_add_acf_posts_endpoint( $allowed_endpoints ) {
   
        /*$allowed_endpoints[ 'v1' ][] = 'header';
        $allowed_endpoints[ 'v1' ][] = 'total_available_vouchers';
        $allowed_endpoints[ 'v1' ][] = 'wholesalers';
        $allowed_endpoints[ 'v1' ][] = 'wishlist_ids';
        $allowed_endpoints[ 'v1' ][] = 'wishlist';
        $allowed_endpoints[ 'v1' ][] = 'receipt';
        $allowed_endpoints[ 'v1' ][] = 'wpsl_stores_list';
        $allowed_endpoints[ 'v1' ][] = 'whph_faq';
        $allowed_endpoints[ 'v1' ][] = 'product_categories';
        $allowed_endpoints[ 'v1' ][] = 'customer_orders';*/
        
        $allowed_endpoints[ 'wc/v3' ][] = 'products';
    
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_acf_posts_endpoint', 10, 1);

include 'functions/custom-functions.php';