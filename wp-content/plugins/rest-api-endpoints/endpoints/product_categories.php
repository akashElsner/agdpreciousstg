<?php
    function rest_product_categories_callback( $request  ){
        $hide_empty = $request->get_param( 'hide_empty' );
        
        $data['status'] = 'Failed';
        $data['msg'] = "Something went wrong!";
        
        $taxonomy     = 'product_cat';
        $orderby      = 'name';
        $empty        = ($hide_empty) ? $hide_empty : 0;
        
        $args = array(
             'taxonomy'     => $taxonomy,
             'orderby'      => $orderby,
             'hide_empty'   => $empty
        );
        $prod_categories = get_categories( $args );
        $product_cat = array();
        
        if( $prod_categories ){
            foreach ($prod_categories as $cat) {
                $product_cat[] = [
                    "id" => $cat->term_id,
                    "name" => htmlspecialchars_decode($cat->name),
                    "slug" => $cat->slug,
                    "count" => $cat->count,
                ];
            }
            
            $data['status'] = 'Success';
            $data['msg'] = "";
        }
        
        $data['product_categories'] = $product_cat;
        
        $response1 = new WP_REST_Response($data);
        $response1->set_data($data);
        return $response1;
    }
?>