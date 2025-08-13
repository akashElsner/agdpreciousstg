<?php
function rest_login_callback( $request ){
    $username = $request->get_param( 'username' );
    $password = $request->get_param( 'password' );
    $remember = $request->get_param( 'remember' );
    
    $finalArr = array();
    
    $finalArr['status'] = 'Failed';
    $finalArr['msg'] = "Incorrect username or password."; 
    
    if( !empty($username) && !empty($password) ){
        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember
        );
    
        $user = wp_signon( $creds, false );
        
        if ( is_wp_error( $user ) ){
            $finalArr['status'] = 'Failed';
            $finalArr['msg'] = $user->get_error_message();
        }else{
            // wp_clear_auth_cookie();
            // wp_set_current_user ( $user->ID ); // Set the current user detail
            // wp_set_auth_cookie  ( $user->ID ); // Set auth details in cookie
            $inputString = $username." ".$password." ".current_time('timestamp');
            $md5Hash = md5($inputString);
            
            $finalArr['status'] = 'Success';
            $finalArr['user_id'] = $user->ID; 
            $finalArr['token'] = $md5Hash; 
            $finalArr['current_timestamp'] = current_time('timestamp'); 
            $finalArr['current_time'] = date( 'Y-m-d H:i:s', current_time( 'timestamp')); 
            $finalArr['msg'] = "Logged in successfully"; 
            
            update_user_meta($user->ID, 'rest_login_token', $md5Hash);
        }
    }
    
    $response1 = new WP_REST_Response($finalArr);
    $response1->set_data($finalArr);
    return $response1;
}

?>