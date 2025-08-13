<?php
/*
Plugin Name:Wordpress Facebook Conversion Pixel
Plugin URI:http://www.miraclewebsoft.com/facebook-conversion-pixel-2/
Description:Wordpress Facebook Conversion Pixel plugin allow users to embed Facebook conversion pixel script to your website.
Version:1.5
Author:sony7596, miraclewebssoft, reachbaljit
Author URI:http://www.miraclewebsoft.com/karam-singh-web-developer/
License:GPL2
License URI:https://www.gnu.org/licenses/gpl-2.0.html
*/
if (!defined('ABSPATH')) {
    exit;
}

if (!defined("FB_CONVERSION_PIXEL_PLUGIN_VERSION_CURRENT")) define("FB_CONVERSION_PIXEL_PLUGIN_VERSION_CURRENT", '1.0.3');
if (!defined("FB_CONVERSION_PIXEL_PLUGIN_PLUGIN_URL")) define("FB_CONVERSION_PIXEL_PLUGIN_PLUGIN_URL", plugin_dir_url( __FILE__ ) );
if (!defined("FB_CONVERSION_PIXEL_PLUGIN_DIR")) define("FB_CONVERSION_PIXEL_PLUGIN_DIR", plugin_dir_path(__FILE__));
if (!defined("FB_CONVERSION_PIXEL_PLUGIN_NM")) define("FB_CONVERSION_PIXEL_PLUGIN_NM", 'FB Conversion Pixel');


Class FB_CONVERSION_PIXEL
{
    public $pre_name = 'fb_conversion_pixel';
    public function __construct(){
      // Installation and uninstallation hooks
        register_activation_hook(__FILE__, array($this, $this->pre_name . '_activate'));
        register_deactivation_hook(__FILE__, array($this, $this->pre_name . '_deactivate'));
        add_action('admin_menu', array($this, $this->pre_name . '_setup_admin_menu'));
        add_action("admin_init", array($this, $this->pre_name . '_backend_plugin_js_scripts_filter_table'));
        add_action("admin_init", array($this, $this->pre_name . '_backend_plugin_css_scripts_filter_table'));
        add_action('admin_init', array($this, 'fb_conversion_pixel_settings'));
        add_action('admin_init', array($this, 'fb_conversion_pixel_register_meta_box'));
        //ajax
        add_action( 'wp_ajax_fb_cp_save_postdata_ajax',  array($this,'fb_conversion_pixel_save_postdata_ajax_callback'));
        add_action( 'admin_footer',  array($this,'fb_conversion_pixel_ajax')); // Write our JS below here
        //end ajax
        add_action('wp', array($this, 'fb_conversion_pixel_on_load'));
    }
    public function fb_conversion_pixel_on_load(){

        $current_post_id = get_the_ID();
        $pix_code = get_post_meta( $current_post_id, 'fb_cp_code');
        $is_hide = get_post_meta( $current_post_id, 'fb_cp_is_hide');
        //page have own pix code
        if( !empty($pix_code) && $is_hide[0] != '2'){
            $_SESSION['fb_cp_code'] = $pix_code[0];
            add_action('wp_head', array($this, 'fb_conversion_pixel_get_code'));
        }
        else{
            add_action('wp_head', array($this, 'fb_conversion_pixel_hook_code'));
        }
    }
    public function fb_conversion_pixel_setup_admin_menu(){
        add_submenu_page('options-general.php', __('FB Conversion Pixel', 'fb_conversion_pixel_td'), FB_CONVERSION_PIXEL_PLUGIN_NM, 'manage_options', 'fb_conversion_pixel_slug', array($this, 'fb_conversion_pixel_admin_page'));
    }
    public function fb_conversion_pixel_admin_page()
    {
        include(plugin_dir_path(__FILE__) . 'views/dashboard.php');
    }
    function fb_conversion_pixel_backend_plugin_js_scripts_filter_table()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_script("email_to_users.js", FB_CONVERSION_PIXEL_PLUGIN_PLUGIN_URL . "assets/js/fb_conversion_pixel.js");
    }
    function fb_conversion_pixel_backend_plugin_css_scripts_filter_table()
    {
        wp_enqueue_style("emai_to_users_view.css", FB_CONVERSION_PIXEL_PLUGIN_PLUGIN_URL . "assets/css/fb_conversion_pixel.css");
    }
    public function fb_conversion_pixel_activate()
    {
    }
    public function fb_conversion_pixel_deactivate()
    {
    }
    function fb_conversion_pixel_settings()
    {    //register our settings
        register_setting('fb_conversion_pixel_group', 'fbcp_code_pix');
        register_setting('fb_conversion_pixel_group', 'fbcp_code_hide_btn');

        $all_post = get_post_types();
        unset($all_post['post']);
        unset($all_post['page']);

        foreach ($all_post as $post_type) {
                register_setting('fb_conversion_pixel_group', 'fbcp_'.$post_type);
        }
    }
    function fb_conversion_pixel_hook_code()
    {
        if (get_option('fbcp_code_hide_btn') != 'false') {
            echo get_option('fbcp_code_pix');
        }

    }
    function fb_conversion_pixel_get_code(){

        if($_SESSION['fb_cp_code']){
            echo stripcslashes(base64_decode($_SESSION['fb_cp_code']));
        }
    }
//Register Meta Box
    function fb_conversion_pixel_register_meta_box()
    {
        $where_meta_box_show = array('post', 'page');
        $all_post = get_post_types();
        unset($all_post['post']);
        unset($all_post['page']);

        foreach ($all_post as $post_type) {
            if(get_option('fbcp_'.$post_type) == 'fbcp_'.$post_type) {
                    array_push($where_meta_box_show, $post_type);
            }
        }
         //var_dump($all_post); die;
        add_meta_box('fb-conversion-pixel-meta-box-id', __('Fb Conversion Pixel', 'fb_conversion_pixel_td'), array($this,
            'fb_conversion_pixel_meta_box_callback'), $where_meta_box_show, 'normal', 'high');
        }
    function fb_conversion_pixel_meta_box_callback($object)
    {
        $pix_code = get_post_meta( $object->ID, 'fb_cp_code');
        $is_hide = get_post_meta( $object->ID, 'fb_cp_is_hide');

        if(isset($pix_code[0])) {
            $pix_code_val = $pix_code[0];
        }
        else{
            $pix_code_val = "";
        }
        if(isset($is_hide[0])) {
            $is_hide_val = $is_hide[0];
        }
        else{
            $is_hide_val = "";
        }
        ?>

        <form>
            <div>
                <h4><?php echo __("FB Conversion Pixel code", 'fb_conversion_pixel_td');?></h4>
                <textarea class="ajax_class_fb_cp" rows="15" cols="80" id="fbcp_code_pix"><?php echo stripcslashes(base64_decode($pix_code_val)); ?></textarea>
            </div>
            <div>
                <h4><?php echo __("Hide code from website", 'fb_conversion_pixel_td');?></h4>
                <input class="ajax_class_fb_cp" id="start_tracking"  type="checkbox" value="2" <?php checked('2', $is_hide_val); ?>
            </div>
            <input type="hidden" value="<?php echo get_the_ID();?>" id="fb_pic_post_id">
            <input type="hidden" value="<?php echo wp_create_nonce( 'ajax_nonce' );?>" id="fb_cp_ajax_nonce">
        </form>
        <?php
    }
    function fb_conversion_pixel_save_postdata_ajax_callback()
    {// Verify our nonce
        if( ! ( isset( $_REQUEST['fb_cp_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['fb_cp_ajax_nonce'], 'ajax_nonce' ) ) ) {
            wp_die();
        }
        else{
            $pix_code = sanitize_text_field(base64_encode($_POST['pix_code']));
            $is_hide = sanitize_text_field($_POST['is_hide']);
            $post_id = sanitize_text_field($_POST['post_id']);
            if(empty($is_hide)){
                $is_hide= "0";
            }

            delete_post_meta($post_id, 'fb_cp_code');
            delete_post_meta($post_id, 'fb_cp_is_hide');

            update_post_meta($post_id, 'fb_cp_code', $pix_code);
            update_post_meta($post_id, 'fb_cp_is_hide', $is_hide);

            wp_die();
            }
    }
    function fb_conversion_pixel_ajax() { ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {
                jQuery(".ajax_class_fb_cp").on('blur paste', function(){
                    setTimeout(function () {
                        pix_code = jQuery("#fbcp_code_pix").val();
                        post_id = jQuery("#fb_pic_post_id").val();
                        fb_cp_ajax_nonce = jQuery("#fb_cp_ajax_nonce").val();

                        if(jQuery("#start_tracking").is(":checked")) {
                            is_hide = 2;
                        }
                        else{
                            is_hide = 0;
                        }

                        var data = {
                        'action': 'fb_cp_save_postdata_ajax',
                        'pix_code': pix_code,
                        'is_hide': is_hide,
                        'post_id': post_id,
                        'fb_cp_ajax_nonce': fb_cp_ajax_nonce
                        };

                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function(response) {
                            console.log(response);
                        });
                    }, 50);
                })
            })
        </script> <?php
    }
}

$FB_CONVERSION_PIXEL_OBJ = new FB_CONVERSION_PIXEL();
