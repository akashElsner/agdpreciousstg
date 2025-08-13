<?php
function rest_token_reset_callback( $request ){
    $finalArr = array();
    
    $finalArr['status'] = 'Failed';
    $finalArr['msg'] = "Incorrect token.";
    
    if( 1 == 1 ){
        $meta_key = 'rest_login_token';
        $user = get_users(array(
            'meta_key'      => $meta_key,
            'fields'        => 'ids',
        ));
        
        if( $user && count($user) > 0 ){
            foreach( $user as $key => $value ){
                delete_user_meta( $value, $meta_key );
            }
            
            $finalArr['status'] = 'Success';
            $finalArr['msg'] = "All Token has been reset !";
        }
    }
    
    $response1 = new WP_REST_Response($finalArr);
    $response1->set_data($finalArr);
    return $response1;
}