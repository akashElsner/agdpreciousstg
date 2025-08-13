<?php
function rest_logout_callback( $request ){
    $token = $request->get_param( 'token' );
    
    $finalArr = array();
    
    $finalArr['status'] = 'Failed';
    $finalArr['msg'] = "Incorrect token.";
    
    if( !empty($token) ){
        $meta_key = 'rest_login_token';
        $user = get_users(array(
            'meta_key'      => $meta_key,
            'meta_value'    => $token,
            'fields'        => 'ids',
            'number'        => 1
        ));
        
        if( $user && count($user) > 0 ){
            foreach( $user as $key => $value ){
                if ( !delete_user_meta( $value, $meta_key ) ){
                    $finalArr['status'] = 'Failed';
                    $finalArr['msg'] = "Ooops! Error while deleting this information!";
                }else{
                    $finalArr['status'] = 'Success';
                    $finalArr['msg'] = "Logout successfully.";
                }
            }
        }
    }
    
    $response1 = new WP_REST_Response($finalArr);
    $response1->set_data($finalArr);
    return $response1;
}