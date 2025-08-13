<?php
    function array_flatten($array){
      $result = [];
      foreach ($array as $element) {
        if (is_array($element)) {
          $result = array_merge($result, array_flatten($element));
        } else {
          $result[] = $element;
        }
      }
      return $result;
    }
    
    function rest_notifications_callback( $request ){
        $user_id = ($request->get_param( 'user_id' )) ? $request->get_param( 'user_id' ) : 0;
        $number = ($request->get_param( 'number' )) ? $request->get_param( 'number' ) : 10;
        $notification_type = ($request->get_param( 'type' )) ? $request->get_param( 'type' ) : 'all'; //  all  OR  new
        
        $data['status'] = 'Failed';
        $data['msg'] = "404 Not found!"; 
        $data['notifications'] = array();
        //$data['TEST'] = "1"; 
    
        if( 1==1 ){
            global $wpdb;
            
            if( $user_id > 0 && $notification_type == 'new' ){
                $meta_key = 'new_receipt_notification';
                $user_notification = get_user_meta($user_id, $meta_key, true);
                $data['type'] = $notification_type;
                $data['user_id'] = $user_id;
                if( $user_notification ){
                    krsort($user_notification);
                    
                    //$temp_notifications = array_slice($user_notification, 0, $number, true);
                    $temp_notifications = $user_notification;
                    $temp = array();
                    foreach( $temp_notifications as $key => $val ){
                        $temp[] = $val;
                    }
                    $data['notifications'] = $temp;
                    
                    if( $temp_notifications ){
                        $data['status'] = 'Success';
                        $data['msg'] = "";
                    }else{
                        $data['status'] = 'Success';
                        $data['msg'] = "404 Not found!";
                    }
                    $user_notification = delete_user_meta($user_id, $meta_key);
                }else{
                    $data['status'] = 'Success';
                }
                $response1 = new WP_REST_Response($data);
                $response1->set_data($data);
                return $response1;
            }
            
            $data['TEST'] = "1";
            
            // Current site prefix
            $prefix = $wpdb->prefix;
            $table_name = $prefix.'notifications';
            $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY `date` DESC");
            $user_notification = array();
            
            if( $user_id > 0 && $notification_type == 'all' ){
                $meta_key = 'receipt_notification';
                $user_notification = get_user_meta($user_id, $meta_key, true);
                $data['user_id'] = $user_id;
            }
            
            /*if( $user_id > 0 && $notification_type == 'new' ){
                $meta_key = 'new_receipt_notification';
                $user_notification = array();
                $user_notification = get_user_meta($user_id, $meta_key, true);
                $data['user_id'] = $user_id;
            }*/
            
            $n1 = array();
            if( $results ){
                foreach ($results as $key => $value) { 
                    $notifications_arr = array();
                       
                   $notifications_id = $value->id;
                   $notifications_title = $value->title;
                   $notifications_message = $value->message;
                   $notifications_date = $value->date;
                   
                    $notifications_arr['notifications_id'] = $notifications_id;
                    $notifications_arr['notifications_title'] = $notifications_title;
                    $notifications_arr['notifications_message'] = $notifications_message;
                    
                    $notifications_arr['notifications_date'] = (int)$notifications_date;

                    $data['notifications'][$notifications_date] = $notifications_arr;
                }
                
                $data['status'] = 'Success';
                $data['msg'] = "";
            }
            /*$data['Merge'] = $data['notifications'] + $user_notification;
            $data['$user_notification'] = $user_notification;
            $data['notifications_test'] = $data['notifications'];
            unset($data['notifications']);*/
            
            if( $user_id > 0 ){
                if( $user_notification && $data['notifications'] ){
                    //$temp_notification = array_merge($data['notifications'], $user_notification);
                    $temp_notification = $data['notifications'] + $user_notification;
                    $data['notifications'] = array();
                    $data['notifications'] = $temp_notification;
                }elseif( $user_notification && !$data['notifications'] ){
                    $data['notifications'] = array();
                    $data['notifications'] = $user_notification;
                }
            }
                
            if( $data['notifications'] ){
                krsort($data['notifications']);
                $temp = array();
                $temp_notifications = array_slice($data['notifications'], 0, $number, true);
                foreach( $temp_notifications as $key => $val ){
                    $temp[] = $val;
                }
                $data['notifications'] = $temp;
            }
            
            if( $user_id > 0 && $notification_type == 'new' ){
                $meta_key = 'new_receipt_notification';
                //delete_user_meta($user_id, $meta_key);
            }
        }
        
    	$response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }