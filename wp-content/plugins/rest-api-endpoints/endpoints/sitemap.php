<?php
function rest_sitemap_callback( $request ){
        
    $sitemap = array(
                'https://www.elsner.com/',
                'https://www.elsner.com/services/magento-development/',
                'https://www.elsner.com/magento-2-migration-services/',
                'https://www.elsner.com/services/magento-ecommerce-development/',
                'https://www.elsner.com/services/magento-enterprise-development/',
                'https://www.elsner.com/magento-support-plan/',
                'https://www.elsner.com/magento-upgrade-service/',
                'https://www.elsner.com/services/php-web-development/',
                'https://www.elsner.com/services/laravel-development/',
                'https://www.elsner.com/services/node-js-development/',
                'https://www.elsner.com/services/angular-js-development/',
                'https://www.elsner.com/services/reactjs-development/',
                'https://www.elsner.com/services/custom-web-design/',
                'https://www.elsner.com/services/seo-services/',
                'https://www.elsner.com/services/ppc-management-services/',
                'https://www.elsner.com/services/social-media-marketing-services/',
                'https://www.elsner.com/services/content-marketing-services/',
                'https://www.elsner.com/services/online-reputation-management/',
                'https://www.elsner.com/services/aso-services/',
                'https://www.elsner.com/services/wordpress-development/',
                'https://www.elsner.com/services/learning-management-system-development/',
                'https://www.elsner.com/services/responsive-web-design/',
                'https://www.elsner.com/services/salesforce-development-company/',
                'https://www.elsner.com/services/shopify-development/',
                'https://www.elsner.com/services/woocommerce-development/',
                'https://www.elsner.com/services/zoho-development-services/',
                'https://www.elsner.com/services/shopware-development/',
                'https://www.elsner.com/services/bigcommerce-development/',
                'https://www.elsner.com/services/android-app-development/',
                'https://www.elsner.com/services/iphone-app-development/',
                'https://www.elsner.com/services/beacon-app-development/',
                'https://www.elsner.com/services/enterprise-mobile-app-development/',
                'https://www.elsner.com/services/mobile-ui-design/',
                'https://www.elsner.com/services/ecommerce-development/',
                'https://www.elsner.com/hire-magento-developer/',
                'https://www.elsner.com/hire-php-developer/',
                'https://www.elsner.com/hire-shopify-developer/',
                'https://www.elsner.com/hire-wordpress-developer/',
                'https://www.elsner.com/hire-ios-developer/',
                'https://www.elsner.com/hire-android-developer/',
                'https://www.elsner.com/services/automation-testing-services/',
                'https://www.elsner.com/services/performance-testing-services/',
                'https://www.elsner.com/services/mobile-testing-services/',
                'https://www.elsner.com/services/security-assessment-and-testing/',
                'https://www.elsner.com/about-us/',
                'https://www.elsner.com/awards-and-accolades/',
                'https://www.elsner.com/skillsets/',
                'https://www.elsner.com/clientele-and-testimonials/',
                'https://www.elsner.com/life-at-elsner/',
                'https://www.elsner.com/our-clients/',
                'https://www.elsner.com/team/',
                'https://www.elsner.com/our-portfolio/',
                'https://www.elsner.com/solutions/',
                'https://www.elsner.com/blog/',
                'https://www.elsner.com/career/',
                'https://www.elsner.com/privacy-policy/',
                'https://www.elsner.com/engagement-models/',
                'https://www.elsner.com/terms-and-conditions/',
                'https://www.elsner.com/partners-and-alliances/',
                'https://www.elsner.com/contact-us/',
                'https://www.elsner.com/refund-policy/',
                'https://www.elsner.com/delivery-policy/',
                'https://www.elsner.com/our-nda/',
                'https://www.elsner.com/our-sla/',
                'https://www.elsner.com/hire-php-expert/',
                'https://www.elsner.com/hire-wordpress-expert/',
                'https://www.elsner.com/wordpress-support-plan/',
                'https://www.elsner.com/php-support-plan/',
                'https://www.elsner.com/services/magento-extension-development/',
                'https://www.elsner.com/services/custom-logo-design/',
                'https://www.elsner.com/hire-laravel-developer/',
                'https://www.elsner.com/services/marketplace-management-services/',
                'https://www.elsner.com/mcommerce/',
                'https://www.elsner.com/ecomva/',
                'https://www.elsner.com/hire-bigcommerce-developer/',
                'https://www.elsner.com/hire-shopware-developer/',
                'https://www.elsner.com/partners-and-alliances/',
                'https://www.elsner.com/local-seo-services/',
                'https://www.elsner.com/ecommerce-seo-services/',
                'https://www.elsner.com/klaviyo-integration-service/',
                'https://www.elsner.com/hire-ecommerce-developer/',
                'https://www.elsner.com/hire-agora-developer/',
                'https://www.elsner.com/our-portfolio/wordpress/',
                'https://www.elsner.com/our-portfolio/magento/',
                'https://www.elsner.com/our-portfolio/mobile/',
                'https://www.elsner.com/our-portfolio/opencart/',
                'https://www.elsner.com/our-portfolio/seo/',
                'https://www.elsner.com/our-portfolio/php/',
                'https://www.elsner.com/our-portfolio/shopify/',
                'https://www.elsner.com/our-portfolio/salesforce/',
                'https://www.elsner.com/our-portfolio/mean-stack/',
                'https://www.elsner.com/our-portfolio/asp-net/',
                'https://www.elsner.com/seo-packages/',
                'https://www.elsner.com/ppc-packages/',
                'https://www.elsner.com/smo-packages/',
                'https://www.elsner.com/digital-marketing-packages/',
                'https://www.elsner.com/aso-packages/',
                'https://www.elsner.com/digital-marketing-booster-packages/',
                'https://www.elsner.com/smm-packages/',
                'https://www.elsner.com/local-seo-packages/',
                'https://www.elsner.com/magento-extension/',
                ' https://www.elsner.com/adobe-commerce-development/',
        );
        
    //blog posts
    $args = array(
      'posts_per_page' => -1,
      'post_type'   => 'post',
      'fields' => 'ids'
    );
     
    $posts = get_posts( $args );
   
   foreach($posts as $post){
       $permalink = str_replace(array('https://office.elsner.com'), '', get_permalink($post));
       $sitemap[] = 'https://www.elsner.com'.$permalink;
   }
   
   //portfolio
   $args = array(
      'posts_per_page' => -1,
      'post_type'   => 'portfolio',
      'fields' => 'ids'
    );
     
    $posts = get_posts( $args );
   
   foreach($posts as $post){
       $permalink = str_replace(array('https://office.elsner.com'), '', get_permalink($post));
       $sitemap[] = 'https://www.elsner.com'.$permalink;
   }
   
   //echo '<pre>';print_r($posts);
    return json_encode($sitemap, JSON_UNESCAPED_SLASHES);
}

?>