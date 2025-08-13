<?php

/*-----------------------------------------------------------------------------------------------------//
/*	Theme Setup
/*-----------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'purpose_setup' ) ) :

function purpose_setup() {
/*-----------------------------------------------------------------------------------------------------//	
	Options Framework		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/' );
	require_once dirname( __FILE__ ) . '/admin/options-framework.php';
}

	// Make theme available for translation
	load_theme_textdomain( 'organicthemes', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails' );
	
	add_image_size( 'purpose-featured-large', 1920, 900, true ); // Medium Featured Image
	add_image_size( 'purpose-featured-square', 720, 720, true ); // Square Featured Image
	
	
	// function for get image thumbnail from image url(for options framework)
	function get_attachment_id_from_src ($image_src) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;
	}

	// Create Menus
	register_nav_menus( array(
		'main-menu' => __( 'Main Menu', 'organicthemes' ),
		'header-menu' => __( 'Header Menu', 'organicthemes' ),
		'footer-menu' => __( 'Footer Menu', 'organicthemes' ),
	));
	
	// Custom Header
	$defaults = array(
		'width'                 => 1800,
		'height'                => 600,
		'flex-height'           => true,
		'flex-width'            => true,
		'default-text-color'    => 'ffffff',
		'header-text'           => false,
		'uploads'               => true,
	);
	add_theme_support( 'custom-header', $defaults );
	
	// Custom Background
	$defaults = array(
		'default-color'          => 'ffffff',
	);
	add_theme_support( 'custom-background', $defaults );
}
endif; // purpose_setup
add_action( 'after_setup_theme', 'purpose_setup' );

/*-----------------------------------------------------------------------------------------------------//	
	Category ID to Name		       	     	 
-------------------------------------------------------------------------------------------------------*/

function purpose_cat_id_to_name( $id ) {
	$cat = get_category( $id );
	if ( is_wp_error( $cat ) )
		return false;
	return $cat->cat_name;
}

/*-----------------------------------------------------------------------------------------------------//	
	Register Scripts		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( !function_exists('purpose_enqueue_scripts') ) {
	function purpose_enqueue_scripts() {
	
		// Enqueue Styles
		wp_enqueue_style( 'purpose-style', get_stylesheet_uri() );
		wp_enqueue_style( 'purpose-style-mobile', get_template_directory_uri() . '/css/style-mobile.css', array( 'purpose-style' ), '1.0' );
		wp_enqueue_style( 'purpose-style-ie8', get_template_directory_uri() . '/css/style-ie8.css', array( 'purpose-style' ), '1.0' );
		
		// IE Conditional Styles
		global $wp_styles;
		$wp_styles->add_data('purpose-style-ie8', 'conditional', 'lt IE 9');
		
		// Resgister Scripts
		wp_register_script( 'purpose-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '20130729' );
		wp_register_script( 'purpose-hover', get_template_directory_uri() . '/js/hoverIntent.js', array( 'jquery' ), '20130729' );
		wp_register_script( 'purpose-superfish', get_template_directory_uri() . '/js/superfish.js', array( 'jquery', 'purpose-hover' ), '20130729' );
		wp_register_script( 'purpose-isotope', get_template_directory_uri() . '/js/jquery.isotope.js', array( 'jquery' ), '20130729' );
	
		// Enqueue Scripts
		wp_enqueue_script( 'purpose-html5shiv', get_template_directory_uri() . '/js/html5shiv.js' );
		//wp_enqueue_script( 'purpose-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20130729', true );
		wp_enqueue_script( 'purpose-custom', get_template_directory_uri() . '/js/jquery.custom.js', array( 'jquery', 'purpose-superfish', 'purpose-fitvids', 'purpose-isotope', 'jquery-masonry', 'jquery-color' ), '20130729', true );
		
		// IE Conditional Scripts
		global $wp_scripts;
		$wp_scripts->add_data( 'purpose-html5shiv', 'conditional', 'lt IE 9' );
		
		// Load Flexslider on front page and slideshow page template
		if ( is_home() || is_front_page() || is_single() || is_page_template('template-slideshow.php') || is_page_template('template-blog.php') ) {
			wp_enqueue_script( 'purpose-flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array( 'jquery' ), '20130729' );
		}
	
		// Load single scripts only on single pages
	    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	    	wp_enqueue_script( 'comment-reply' );
	    }
	}
}
add_action('wp_enqueue_scripts', 'purpose_enqueue_scripts');

/*-----------------------------------------------------------------------------------------------------//	
	WooCommerce Integration			       	     	 
-------------------------------------------------------------------------------------------------------*/

add_filter( 'woocommerce_add_to_cart_fragments', 'cart_count_fragments', 10, 1 );
function cart_count_fragments( $fragments ) {
    $fragments['span.cartCount'] = '<span class="cartCount">' . WC()->cart->get_cart_contents_count() . '</span>';
    return $fragments;
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;
}

add_filter( 'woocommerce_show_page_title', '__return_false' );
/*-----------------------------------------------------------------------------------------------------//	
	Register Sidebars		       	     	 
-------------------------------------------------------------------------------------------------------*/

function organic_widgets_init() {
	register_sidebar(array(
		'name'=> __( "Footer Widget 1", 'organicthemes' ),
		'id' => 'footer-w1',
		'before_widget'=>'<div id="%1$s" class="fw_wrap %2$s">',
		'after_widget'=>'</div>',
		'before_title'=>'<h5>',
		'after_title'=>'</h5>'
	));
	register_sidebar(array(
		'name'=> __( "Footer Widget 2", 'organicthemes' ),
		'id' => 'footer-w2',
		'before_widget'=>'<div id="%1$s" class="fw_wrap %2$s">',
		'after_widget'=>'</div>',
		'before_title'=>'<h5>',
		'after_title'=>'</h5>'
	));
	register_sidebar(array(
		'name'=> __( "Instagram", 'organicthemes' ),
		'id' => 'instagram',
		'before_widget'=>'',
		'after_widget'=>'',
		'before_title'=>'<h6">',
		'after_title'=>'</h6>'
	));
}
add_action( 'widgets_init', 'organic_widgets_init' );


/*----------------------------------------------------------------------------------------------------//
/*	Content Width
/*----------------------------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) )
	$content_width = 640;

/**
 * Adjust content_width value based on the presence of widgets
 */
function purpose_content_width() {
	if ( ! is_active_sidebar( 'post-sidebar' ) || is_active_sidebar( 'page-sidebar' ) || is_active_sidebar( 'blog-sidebar' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'purpose_content_width' );
	
/*-----------------------------------------------------------------------------------------------------//	
	Comments Function		       	     	 
-------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'purpose_comment' ) ) :
function purpose_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'organicthemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'organicthemes' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
		break;
		default :
	?>
	<li <?php comment_class(); ?> id="<?php echo esc_attr( 'li-comment-' . get_comment_ID() ); ?>">
	
		<article id="<?php echo esc_attr( 'comment-' . get_comment_ID() ); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 72;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 48;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s <br/> %2$s <br/>', 'organicthemes' ),
							sprintf( '<span class="fn">%s</span>', wp_kses_post( get_comment_author_link() ) ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s', 'organicthemes' ), get_comment_date(), get_comment_time() )
							)
						);
					?>
				</div><!-- .comment-author .vcard -->
			</footer>

			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'organicthemes' ); ?></em>
					<br />
				<?php endif; ?>
				<?php comment_text(); ?>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'organicthemes' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
				<?php edit_comment_link( __( 'Edit', 'organicthemes' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		</article><!-- #comment-## -->

	<?php
	break;
	endswitch;
}
endif; // ends check for purpose_comment()

/*-----------------------------------------------------------------------------------------------------//	
	Comments Disabled On Pages By Default		       	     	 
-------------------------------------------------------------------------------------------------------*/

function purpose_default_comments_off( $data ) {
    if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
        $data['comment_status'] = 0;
    } 

    return $data;
}
add_filter( 'wp_insert_post_data', 'purpose_default_comments_off' );

/*-----------------------------------------------------------------------------------------------------//	
	Custom Excerpt Length		       	     	 
-------------------------------------------------------------------------------------------------------*/

function purpose_excerpt_length( $length ) {
	return 38;
}
add_filter( 'excerpt_length', 'purpose_excerpt_length', 999 );

function purpose_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'purpose_excerpt_more');


//short_excerpt
function short_excerpt($getcontent, $excerpt_length) {
	echo substr($getcontent, 0, $excerpt_length).' ...';
}

/*-----------------------------------------------------------------------------------------------------//	
	Add Excerpt To Pages		       	     	 
-------------------------------------------------------------------------------------------------------*/

add_action( 'init', 'purpose_add_excerpts_to_pages' );
function purpose_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

/*-----------------------------------------------------------------------------------------------------//
/*	Pagination Function
/*-----------------------------------------------------------------------------------------------------*/

function purpose_get_pagination_links() {
	global $wp_query;
	$big = 999999999;
	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'prev_text' => __('&laquo;', 'organicthemes'),
		'next_text' => __('&raquo;', 'organicthemes'),
		'total' => $wp_query->max_num_pages
	) );
}

/*-----------------------------------------------------------------------------------------------------//
/*	Custom Page Links
/*-----------------------------------------------------------------------------------------------------*/

function purpose_wp_link_pages_args_prevnext_add($args) {
    global $page, $numpages, $more, $pagenow;

    if (!$args['next_or_number'] == 'next_and_number') 
        return $args; 

    $args['next_or_number'] = 'number'; // Keep numbering for the main part
    if (!$more)
        return $args;

    if($page-1) // There is a previous page
        $args['before'] .= _wp_link_page($page-1)
            . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';

    if ($page<$numpages) // There is a next page
        $args['after'] = _wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after'];

    return $args;
}

add_filter('wp_link_pages_args', 'purpose_wp_link_pages_args_prevnext_add');

/*-----------------------------------------------------------------------------------------------------//	
	Featured Video Meta Box		       	     	 
-------------------------------------------------------------------------------------------------------*/

add_action("admin_init", "admin_init_featurevid");
add_action('save_post', 'save_featurevid');

function admin_init_featurevid(){
	add_meta_box("featurevid-meta", __("Featured Video Embed Code", 'organicthemes'), "meta_options_featurevid", "post", "normal", "high");
}

function meta_options_featurevid(){
	global $post;
	$custom = get_post_custom($post->ID);
	$featurevid = isset( $custom["featurevid"] ) ? esc_attr( $custom["featurevid"][0] ) : '';

	echo '<textarea name="featurevid" cols="60" rows="4" style="width:97.6%" />'.$featurevid.'</textarea>';
}

function save_featurevid($post_id){
	global $post;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
	if ( isset($_POST["featurevid"]) ) { 
		update_post_meta($post->ID, "featurevid", $_POST["featurevid"]); 
	}
}

/*-----------------------------------------------------------------------------------------------------//	
	Add Home Link To Custom Menu		       	     	 
-------------------------------------------------------------------------------------------------------*/

function home_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'home_page_menu_args');

/*-----------------------------------------------------------------------------------------------------//	
	Strip inline width and height attributes from WP generated images		       	     	 
-------------------------------------------------------------------------------------------------------*/
 
function remove_thumbnail_dimensions( $html ) { 
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html ); 
	return $html; 
	}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 ); 
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

/*-----------------------------------------------------------------------------------------------------//
	Body Class
-------------------------------------------------------------------------------------------------------*/

function purpose_body_class( $classes ) {
	if ( is_singular() )
		$classes[] = 'purpose-singular';

	if ( is_active_sidebar( 'right-sidebar' ) )
		$classes[] = 'purpose-right-sidebar';

	if ( '' != get_theme_mod( 'background_image' ) ) {
		// This class will render when a background image is set
		// regardless of whether the user has set a color as well.
		$classes[] = 'purpose-background-image';
	} else if ( ! in_array( get_background_color(), array( '', get_theme_support( 'custom-background', 'default-color' ) ) ) ) {
		// This class will render when a background color is set
		// but no image is set. In the case the content text will
		// Adjust relative to the background color.
		$classes[] = 'purpose-relative-text';
	}

	return $classes;
}
add_action( 'body_class', 'purpose_body_class' );

/*---------------------------------------------------------------------------------------------//
	Retrieve email value from Customizer and add mailto protocol
/*---------------------------------------------------------------------------------------------*/

function purpose_get_email_link() {
	$email = get_theme_mod( 'purpose_link_email' );

	if ( ! $email )
		return false;

	return 'mailto:' . sanitize_email( $email );
}

/*-----------------------------------------------------------------------------------------------------//
	Filters wp_title to print a neat <title> tag based on what is being viewed.
-------------------------------------------------------------------------------------------------------*/

function purpose_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'organicthemes' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'purpose_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------------------------//
	Includes
-------------------------------------------------------------------------------------------------------*/

//require_once( get_template_directory() . '/includes/theme-updater.php' );
//require_once( get_template_directory() . '/includes/customizer.php' );
require_once( get_template_directory() . '/includes/typefaces.php' );
include_once( get_template_directory() . '/organic-shortcodes/organic-shortcodes.php' );

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

add_action('admin_init','optionscheck_change_santiziation', 100);

function optionscheck_change_santiziation() {
	remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
	remove_filter( 'of_sanitize_text', 'sanitize_text_field' );
	add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea_custom', 10, 2 );
	add_filter( 'of_sanitize_text', 'of_sanitize_text_custom', 10, 2 );
}

function of_sanitize_textarea_custom($input) {
	global $allowedtags;
	$of_custom_allowedtags["embed"] = array(
		"src" => array(),
		"type" => array(),
		"allowfullscreen" => array(),
		"allowscriptaccess" => array(),
		"height" => array(),
		"width" => array()
	);
	$of_custom_allowedtags["span"] =array();

        $of_custom_allowedtags = array_merge($of_custom_allowedtags, $allowedtags);
        $output = wp_kses( $input, $of_custom_allowedtags);
	return $output;
}
function of_sanitize_text_custom($input) {
	global $allowedtags;
	$of_custom_allowedtags["span"] =array();
	$of_custom_allowedtags["br"] =array();

        $of_custom_allowedtags = array_merge($of_custom_allowedtags, $allowedtags);
        $output = wp_kses( $input, $of_custom_allowedtags);
	return $output;
}

//Social Shortcode
function socialicons(){
	ob_start();
	get_template_part( 'content/social', 'links' );
	$content = ob_get_clean();
return $content; 
}
add_shortcode('social_icons','socialicons');

//Latest News Shortcode
function latestNews(){
	ob_start();
	get_template_part( 'content/latest', 'news' );
	$content = ob_get_clean();
return $content; 
}
add_shortcode('latest_news','latestNews');


/* 
 * Recreate the default filters on the_content
 * this will make it much easier to output the meta content with proper/expected formatting
*/
add_filter( 'meta_content', 'wptexturize' );
add_filter( 'meta_content', 'convert_smilies' );
add_filter( 'meta_content', 'convert_chars'  );
add_filter( 'meta_content', 'wpautop' );
add_filter( 'meta_content', 'shortcode_unautop'  );
add_filter( 'meta_content', 'prepend_attachment' );


//Instagram Scrap
//Used to fetch Instagram feeds without API

function scrape_insta($username) {
	$insta_source = wp_remote_get( 'http://instagram.com/'.trim( $username ) );
	
	if ( is_wp_error( $insta_source ) ){
		echo 'Unable to communicate with Instagram. Try refresh this page';
	}elseif( 200 != wp_remote_retrieve_response_code( $insta_source ) ){
		echo 'Instagram did not return a 200';
	}else{
		$shards = explode('window._sharedData = ', $insta_source['body']);
		$insta_json = explode(';</script>', $shards[1]); 
		$insta_array = json_decode($insta_json[0], TRUE);
		return $insta_array;
	}
}

// Remove Pagination from a Stockists
add_action( 'parse_query', 'cd_nopaging' );
function cd_nopaging( $query ) {
  if ( is_post_type_archive( 'stockist' ) ) {
  	$query->set( 'nopaging', 1 );
  }
}


add_action( 'pre_get_posts', function ( $query ) 
{
    if (    !is_admin() 
         && $query->is_main_query() 
         && $query->is_tax() 
    ) {
        $query->set( 'posts_per_page', '-1'   );
        $query->set( 'orderby',        'rand' );
    }
});
?>