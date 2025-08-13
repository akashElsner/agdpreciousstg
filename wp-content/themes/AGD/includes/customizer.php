<?php
/**
* Theme customizer with real-time update
*
* Very helpful: http://ottopress.com/2012/theme-customizer-part-deux-getting-rid-of-options-pages/
*
* @package Purpose
* @since Purpose 1.0
*/
function purpose_theme_customizer( $wp_customize ) {

	// Category Dropdown Control
	class Purpose_Category_Dropdown_Control extends WP_Customize_Control {
	public $type = 'dropdown-categories';

	public function render_content() {
		$dropdown = wp_dropdown_categories(
				array(
					'name'              => '_customize-dropdown-categories-' . $this->id,
					'echo'              => 0,
					'show_option_none'  => __( '&mdash; Select &mdash;', 'organicthemes' ),
					'option_none_value' => '0',
					'selected'          => $this->value(),
				)
			);

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

			printf( '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
				$this->label,
				$dropdown
			);
		}
	}
	
	// Numerical Control
	class Purpose_Customizer_Number_Control extends WP_Customize_Control {
	
		public $type = 'number';
		
		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="number" <?php $this->link(); ?> value="<?php echo intval( $this->value() ); ?>" />
			</label>
			<?php
		}
	
	}
	
	function purpose_sanitize_transition_interval( $input ) {
	    $valid = array(
	        '2000' 		=> __( '2 Seconds', 'organicthemes' ),
	        '4000' 		=> __( '4 Seconds', 'organicthemes' ),
	        '6000' 		=> __( '6 Seconds', 'organicthemes' ),
	        '8000' 		=> __( '8 Seconds', 'organicthemes' ),
	        '10000' 	=> __( '10 Seconds', 'organicthemes' ),
	        '12000' 	=> __( '12 Seconds', 'organicthemes' ),
	        '20000' 	=> __( '20 Seconds', 'organicthemes' ),
	        '30000' 	=> __( '30 Seconds', 'organicthemes' ),
	        '60000' 	=> __( '1 Minute', 'organicthemes' ),
	        '999999999'	=> __( 'Hold Frame', 'organicthemes' ),
	    );
	 
	    if ( array_key_exists( $input, $valid ) ) {
	        return $input;
	    } else {
	        return '';
	    }
	}
	
	function purpose_sanitize_categories( $input ) {
		$categories = get_all_category_ids();
	 
	    if ( in_array( $input, $categories ) ) {
	        return $input;
	    } else {
	    	return '';
	    }
	}
	
	function purpose_sanitize_pages( $input ) {
		$pages = get_all_page_ids();
	 
	    if ( in_array( $input, $pages ) ) {
	        return $input;
	    } else {
	    	return '';
	    }
	}
	
	function purpose_sanitize_columns( $input ) {
	    $valid = array(
	        'one' 		=> __( 'One Column', 'organicthemes' ),
	        'two' 		=> __( 'Two Columns', 'organicthemes' ),
	        'three' 	=> __( 'Three Columns', 'organicthemes' ),
	    );
	 
	    if ( array_key_exists( $input, $valid ) ) {
	        return $input;
	    } else {
	        return '';
	    }
	}
	
	function purpose_sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}
	
	function purpose_sanitize_text( $input ) {
	    return wp_kses_post( force_balance_tags( $input ) );
	}

	// Set site name and description text to be previewed in real-time
	$wp_customize->get_setting('blogname')->transport='postMessage';
	$wp_customize->get_setting('blogdescription')->transport='postMessage';

	// Set site title color to be previewed in real-time
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	//-------------------------------------------------------------------------------------------------------------------//
	// Logo Section
	//-------------------------------------------------------------------------------------------------------------------//
	
	$wp_customize->add_section( 'purpose_logo_section' , array(
		'title' 	=> __( 'Logo', 'organicthemes' ),
		'description' => __( 'Both logos should be equal size, 120px x 120px is recommended.', 'organicthemes' ),
		'priority' 	=> 10,
	) );

		// Logo uploader
		$wp_customize->add_setting( 'purpose_logo_light', array(
			'default' 	=> get_template_directory_uri() . '/images/logo-white.png',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'purpose_logo_light', array(
			'label' 	=> __( 'Logo Light', 'organicthemes' ),
			'section' 	=> 'purpose_logo_section',
			'settings'	=> 'purpose_logo_light',
			'priority'	=> 20,
		) ) );
		
		// Logo uploader
		$wp_customize->add_setting( 'purpose_logo_dark', array(
			'default' 	=> get_template_directory_uri() . '/images/logo-black.png',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'purpose_logo_dark', array(
			'label' 	=> __( 'Logo Dark', 'organicthemes' ),
			'section' 	=> 'purpose_logo_section',
			'settings'	=> 'purpose_logo_dark',
			'priority'	=> 20,
		) ) );
	
	//-------------------------------------------------------------------------------------------------------------------//
	// Colors Section
	//-------------------------------------------------------------------------------------------------------------------//
		
		// Menu Background Color
		$wp_customize->add_setting( 'nav_color', array(
		    'default' => '#f9f9f9',
		    'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_color', array(
		    'label' => 'Menu Background Color',
		    'section' => 'colors',
		    'settings' => 'nav_color',
		    'priority'    => 40,
		) ) );
		
		// Link Color
		$wp_customize->add_setting( 'link_color', array(
	        'default' => '#99cc33',
	        'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
	        'label' => 'Link Color',
	        'section' => 'colors',
	        'settings' => 'link_color',
	        'priority'    => 50,
	    ) ) );
	    
	    // Link Hover Color
	    $wp_customize->add_setting( 'link_hover_color', array(
	        'default' => '#99cc33',
	        'sanitize_callback' => 'sanitize_hex_color',
	    ) );
	    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_hover_color', array(
	        'label' => 'Link Hover Color',
	        'section' => 'colors',
	        'settings' => 'link_hover_color',
	        'priority'    => 60,
	    ) ) );
	    
	    // Heading Link Color
	    $wp_customize->add_setting( 'heading_link_color', array(
	        'default' => '#333333',
	        'sanitize_callback' => 'sanitize_hex_color',
	    ) );
	    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_link_color', array(
	        'label' => 'Heading Link Color',
	        'section' => 'colors',
	        'settings' => 'heading_link_color',
	        'priority'    => 70,
	    ) ) );
	    
	    // Heading Link Hover Color
	    $wp_customize->add_setting( 'heading_link_hover_color', array(
	        'default' => '#99cc33',
	        'sanitize_callback' => 'sanitize_hex_color',
	    ) );
	    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_link_hover_color', array(
	        'label' => 'Heading Link Hover Color',
	        'section' => 'colors',
	        'settings' => 'heading_link_hover_color',
	        'priority'    => 80,
	    ) ) );
		
	//-------------------------------------------------------------------------------------------------------------------//
	// Home Page Section
	//-------------------------------------------------------------------------------------------------------------------//
	
	$wp_customize->add_section( 'purpose_home_section' , array(
		'title'       => __( 'Home Page', 'organicthemes' ),
		'priority'    => 102,
	) );
	
		// Featured Slideshow Category
		$wp_customize->add_setting( 'category_slideshow_home' , array(
			'sanitize_callback' => 'purpose_sanitize_categories',
		) );
		$wp_customize->add_control( new Purpose_Category_Dropdown_Control( $wp_customize, 'category_slideshow_home', array(
			'label'		=> __( 'Featured Slideshow Category', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'category_slideshow_home',
			'type'		=> 'dropdown-categories',
			'priority' => 20,
		) ) );
		
		// Slider Transition Interval
		$wp_customize->add_setting( 'transition_interval', array(
		    'default' => '12000',
		    'sanitize_callback' => 'purpose_sanitize_transition_interval',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'transition_interval', array(
		    'type' => 'select',
		    'label' => __( 'Slideshow Transition Interval', 'organicthemes' ),
		    'section' => 'purpose_home_section',
		    'choices' => array(
		        '2000' 		=> __( '2 Seconds', 'organicthemes' ),
		        '4000' 		=> __( '4 Seconds', 'organicthemes' ),
		        '6000' 		=> __( '6 Seconds', 'organicthemes' ),
		        '8000' 		=> __( '8 Seconds', 'organicthemes' ),
		        '10000' 	=> __( '10 Seconds', 'organicthemes' ),
		        '12000' 	=> __( '12 Seconds', 'organicthemes' ),
		        '20000' 	=> __( '20 Seconds', 'organicthemes' ),
		        '30000' 	=> __( '30 Seconds', 'organicthemes' ),
		        '60000' 	=> __( '1 Minute', 'organicthemes' ),
		        '999999999'	=> __( 'Hold Frame', 'organicthemes' ),
		    ),
		    'priority' => 30,
		) ) );
	
		// Featured Page Left
		$wp_customize->add_setting( 'page_one', array(
			'default' => '2',
			'sanitize_callback' => 'purpose_sanitize_pages',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'page_one', array(
			'label'		=> __( 'Featured Page One', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'page_one',
			'type'		=> 'dropdown-pages',
			'priority' => 40,
		) ) );
		
		// Featured Page Middle
		$wp_customize->add_setting( 'page_two', array(
			'default' => '2',
			'sanitize_callback' => 'purpose_sanitize_pages',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'page_two', array(
			'label'		=> __( 'Featured Page Two', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'page_two',
			'type'		=> 'dropdown-pages',
			'priority' => 60,
		) ) );
		
		// Featured Page Right
		$wp_customize->add_setting( 'page_three', array(
			'default' => '2',
			'sanitize_callback' => 'purpose_sanitize_pages',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'page_three', array(
			'label'		=> __( 'Featured Page Three', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'page_three',
			'type'		=> 'dropdown-pages',
			'priority' => 80,
		) ) );
	
		// Featured News Category
		$wp_customize->add_setting( 'category_news', array(
			'default' => '1',
			'sanitize_callback' => 'purpose_sanitize_categories',
		) );
		$wp_customize->add_control( new Purpose_Category_Dropdown_Control( $wp_customize, 'category_news', array(
			'label'		=> __( 'Featured News Category', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'category_news',
			'type'		=> 'dropdown-categories',
			'priority' => 100,
		) ) );
		
		// Featured News Posts Displayed
		$wp_customize->add_setting( 'postnumber_news', array(
			'default' => '3',
			'sanitize_callback' => 'purpose_sanitize_text',
		) );
		$wp_customize->add_control( new Purpose_Customizer_Number_Control( $wp_customize, 'postnumber_news', array(
			'label'		=> __( 'Featured News Posts Displayed', 'organicthemes' ),
			'section'	=> 'purpose_home_section',
			'settings'	=> 'postnumber_news',
			'type'		=> 'number',
			'priority' => 120,
		) ) );
		
	//-------------------------------------------------------------------------------------------------------------------//
	// Page Templates
	//-------------------------------------------------------------------------------------------------------------------//
	
	$wp_customize->add_section( 'purpose_templates_section' , array(
		'title'       => __( 'Page Templates', 'organicthemes' ),
		'priority'    => 103,
	) );
		
		// Blog Template Category
		$wp_customize->add_setting( 'category_blog' , array(
			'default' => '1',
			'sanitize_callback' => 'purpose_sanitize_categories',
		) );
		$wp_customize->add_control( new Purpose_Category_Dropdown_Control( $wp_customize, 'category_blog', array(
			'label'		=> __( 'Blog Template Category', 'organicthemes' ),
			'section'	=> 'purpose_templates_section',
			'settings'	=> 'category_blog',
			'type'		=> 'dropdown-categories',
			'priority' => 40,
		) ) );
		
		// Featured News Posts Displayed
		$wp_customize->add_setting( 'postnumber_blog', array(
			'default' => '10',
			'sanitize_callback' => 'purpose_sanitize_text',
		) );
		$wp_customize->add_control( new Purpose_Customizer_Number_Control( $wp_customize, 'postnumber_blog', array(
			'label'		=> __( 'Blog Posts Displayed', 'organicthemes' ),
			'section'	=> 'purpose_templates_section',
			'settings'	=> 'postnumber_blog',
			'type'		=> 'number',
			'priority' => 60,
		) ) );
		
		// Portfolio Column Layout
		$wp_customize->add_setting( 'portfolio_columns', array(
		    'default' => 'three',
		    'sanitize_callback' => 'purpose_sanitize_columns',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'portfolio_columns', array(
		    'type' => 'radio',
		    'label' => __( 'Portfolio Column Layout', 'organicthemes' ),
		    'section' => 'purpose_templates_section',
		    'choices' => array(
		        'one' 		=> __( 'One Column', 'organicthemes' ),
		        'two' 		=> __( 'Two Columns', 'organicthemes' ),
		        'three' 	=> __( 'Three Columns', 'organicthemes' ),
		    ),
		    'priority' => 80,
		) ) );
		
		// Portfolio Template Category
		$wp_customize->add_setting( 'category_portfolio' , array(
			'default' => '1',
			'sanitize_callback' => 'purpose_sanitize_categories',
		) );
		$wp_customize->add_control( new Purpose_Category_Dropdown_Control( $wp_customize, 'category_portfolio', array(
			'label'		=> __( 'Portfolio Template Category', 'organicthemes' ),
			'section'	=> 'purpose_templates_section',
			'settings'	=> 'category_portfolio',
			'type'		=> 'dropdown-categories',
			'priority' => 100,
		) ) );
		
		// Display Portfolio Info
		$wp_customize->add_setting( 'display_portfolio_info', array(
			'default'	=> true,
			'sanitize_callback' => 'purpose_sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'display_portfolio_info', array(
			'label'		=> __( 'Show Portfolio Title & Excerpt?', 'organicthemes' ),
			'section'	=> 'purpose_templates_section',
			'settings'	=> 'display_portfolio_info',
			'type'		=> 'checkbox',
			'priority' => 120,
		) ) );
		
	//-------------------------------------------------------------------------------------------------------------------//
	// Layout
	//-------------------------------------------------------------------------------------------------------------------//
	
	$wp_customize->add_section( 'purpose_layout_section' , array(
		'title'       => __( 'Layout', 'organicthemes' ),
		'priority'    => 104,
	) );
	
		// Enable Responsive Grid
		$wp_customize->add_setting( 'enable_responsive', array(
			'default'	=> true,
			'sanitize_callback' => 'purpose_sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'enable_responsive', array(
			'label'		=> __( 'Enable Responsive Grid?', 'organicthemes' ),
			'section'	=> 'purpose_layout_section',
			'settings'	=> 'enable_responsive',
			'type'		=> 'checkbox',
			'priority' => 20,
		) ) );
		
		// Enable CSS3 Full Width Background
		$wp_customize->add_setting( 'background_stretch', array(
			'default'	=> true,
			'sanitize_callback' => 'purpose_sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'background_stretch', array(
			'label'		=> __( 'Enable Full Width Background Image?', 'organicthemes' ),
			'section'	=> 'purpose_layout_section',
			'settings'	=> 'background_stretch',
			'type'		=> 'checkbox',
			'priority' => 120,
		) ) );
		
	//-------------------------------------------------------------------------------------------------------------------//
	// Social Section
	//-------------------------------------------------------------------------------------------------------------------//
	
	$wp_customize->add_section( 'purpose_social_section' , array(
		'title'       => __( 'Social Links', 'organicthemes' ),
		'priority'    => 105,
	) );
	
		// Social link - Facebook
		$wp_customize->add_setting( 'purpose_link_facebook', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_facebook', array(
			'label'		=> __( 'Facebook Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_facebook',
			'type'		=> 'text',
			'priority' => 60,
		) ) );
	
		// Social link - Twitter
		$wp_customize->add_setting( 'purpose_link_twitter', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_twitter', array(
			'label'		=> __( 'Twitter Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_twitter',
			'type'		=> 'text',
			'priority' => 80,
		) ) );
		
		// Twitter User
		$wp_customize->add_setting( 'purpose_user_twitter', array(
			 'default'	=> 'OrganicThemes', 
			 'sanitize_callback' => 'purpose_sanitize_text',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_user_twitter', array(
			'label'		=> __( 'Twitter User', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_user_twitter',
			'type'		=> 'text',
			'priority' => 100,
		) ) );
	
		// Social link - LinkedIn
		$wp_customize->add_setting( 'purpose_link_linkedin', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_linkedin', array(
			'label'		=> __( 'LinkedIn Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_linkedin',
			'type'		=> 'text',
			'priority' => 120,
		) ) );
	
		// Social link - Google Plus
		$wp_customize->add_setting( 'purpose_link_googleplus', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_googleplus', array(
			'label'		=> __( 'Google Plus Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_googleplus',
			'type'		=> 'text',
			'priority' => 140,
		) ) );
	
		// Social link - Pinterest
		$wp_customize->add_setting( 'purpose_link_pinterest', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_pinterest', array(
			'label'		=> __( 'Pinterest Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_pinterest',
			'type'		=> 'text',
			'priority' => 160,
		) ) );
	
		// Social link - Instagram
		$wp_customize->add_setting( 'purpose_link_instagram', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_instagram', array(
			'label'		=> __( 'Instagram Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_instagram',
			'type'		=> 'text',
			'priority' => 180,
		) ) );
	
		// Social link - YouTube
		$wp_customize->add_setting( 'purpose_link_youtube', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_youtube', array(
			'label'		=> __( 'YouTube Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_youtube',
			'type'		=> 'text',
			'priority' => 200,
		) ) );
	
		// Social link - GitHub
		$wp_customize->add_setting( 'purpose_link_github', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_github', array(
			'label'		=> __( 'GitHub Link', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_github',
			'type'		=> 'text',
			'priority' => 220,
		) ) );
	
		// Social link - Email
		$wp_customize->add_setting( 'purpose_link_email', array(
			//'sanitize_callback' => 'is_email',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'purpose_link_email', array(
			'label'		=> __( 'Email', 'organicthemes' ),
			'section'	=> 'purpose_social_section',
			'settings'	=> 'purpose_link_email',
			'type'		=> 'text',
			'priority' => 240,
		) ) );
	
}
add_action('customize_register', 'purpose_theme_customizer');

/**
* Binds JavaScript handlers to make Customizer preview reload changes
* asynchronously.
*/
function purpose_customize_preview_js() {
	wp_enqueue_script( 'purpose-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ) );
}
add_action( 'customize_preview_init', 'purpose_customize_preview_js' );