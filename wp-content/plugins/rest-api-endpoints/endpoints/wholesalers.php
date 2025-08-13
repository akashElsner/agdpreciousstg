<?php
    function rest_wholesalers_callback( $request  ){
        
        $wholesale_group_branch_arra = func_add_wholesale('wholesale_group_branch_arr');
        ksort($wholesale_group_branch_arra);
        $response1 = new WP_REST_Response($wholesale_group_branch_arra);
        $response1->set_data($wholesale_group_branch_arra);
        return $response1;
        
        $wholesale_group_branch = func_add_wholesale('wholesale_group_branch');
        $wholesalers = array();
        $wholesalers[1] = 'ac';
        
        if( $wholesale_group_branch ){
            foreach ($wholesale_group_branch as $key => $value) {
                $temp_group_branch = explode(',', $value);
                $wholesalers[trim($temp_group_branch[0])][trim($temp_group_branch[1])] = trim($temp_group_branch[1]);
            }
        }

    	$response1 = new WP_REST_Response(array_filter($wholesalers));
        $response1->set_data($wholesalers);
        return $response1;
    }