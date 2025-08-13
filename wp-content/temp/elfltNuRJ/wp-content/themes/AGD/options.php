<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */
function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	// echo $themename;
}
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */
function optionsframework_options() {
	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress' ));
	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );
	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}
	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	
	
	$options_collections = array();
		$collections_terms_obj = get_terms('collections');
		$options_collections[''] = 'Select a Collection:';
		  foreach ($collections_terms_obj as $collection) {
		$options_collections[$collection->term_id] = $collection->name;
	}
	
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';
	$options = array();
	
	$options[] = array(
		'name' => __('Header', 'options_check'),
		'type' => 'heading');
		
		$options[] = array(
			'name' => __('Header Phone', 'options_check'),
			'desc' => __('Enter the text to display on the button', 'options_check'),
			'id' => 'number_display',
			'type' => 'text');

		$options[] = array(
			'desc' => __('Enter the phone number', 'options_check'),
			'id' => 'number_call',
			'type' => 'text');
			
		$options[] = array(
			'name' => __('Header Phone 2', 'options_check'),
			'desc' => __('Enter the text to display on the button', 'options_check'),
			'id' => 'number_display_2',
			'type' => 'text');

		$options[] = array(
			'desc' => __('Enter the phone number', 'options_check'),
			'id' => 'number_call_2',
			'type' => 'text');

		$options[] = array(
			'name' => __('Header Logo', 'options_check'),
			'desc' => __('Upload Logo', 'options_check'),
			'id' => 'logo_img',
			'type' => 'upload');



		$options[] = array(
			'desc' => __('<h3>Home Banner</h3>', 'options_check'),
			'type' => 'info');

		$options[] = array(
			'name' => __('Banner Content', 'options_check'),
			'desc' => __('', 'options_check'),
			'id' => 'banner_content',
			'type' => 'editor');

		$options[] = array(
			'name' => __('Featured Image', 'options_check'),
			'id' => 'banner_img',
			'type' => 'upload');

		$options[] = array(
			'desc' => __('Button 1' , 'options_check'),
			'id' => 'banner_button',
			'type' => 'text');	
		$options[] = array(
			'desc' => __('Button Link' , 'options_check'),
			'id' => 'banner_button_link',
			'type' => 'select',
			'options' => $options_pages);

		$options[] = array(
			'desc' => __('Button 2' , 'options_check'),
			'id' => 'banner_button_2',
			'type' => 'text');	
		$options[] = array(
			'desc' => __('Button Link' , 'options_check'),
			'id' => 'banner_button_link_2',
			'type' => 'select',
			'options' => $options_pages);
					
		

	$options[] = array(
		'name' => __('Section 1', 'options_check'),
		'type' => 'heading');	
		
		$options[] = array(
			'desc' => __('Display This Section?', 'options_check'),
			'id' => 'section_1_display',
			'type' => 'checkbox');
					
		$options[] = array(
			'name' => __('Choose Page' , 'options_check'),
			'id' => 'section_1_page',
			'type' => 'select',
			'options' => $options_pages);
		
		

	$options[] = array(
		'name' => __('Section 2', 'options_check'),
		'type' => 'heading');	
		
		$options[] = array(
			'desc' => __('Display This Section?', 'options_check'),
			'id' => 'section_2_display',
			'type' => 'checkbox');

		$options[] = array(
			'name' => __('Choose Page' , 'options_check'),
			'id' => 'section_2_page',
			'type' => 'select',
			'options' => $options_pages);

	$options[] = array(
		'name' => __('Section 3', 'options_check'),
		'type' => 'heading');
		
		$options[] = array(
			'desc' => __('Display This Section?', 'options_check'),
			'id' => 'section_3_display',
			'type' => 'checkbox');

		$options[] = array(
			'name' => __('Choose Page' , 'options_check'),
			'id' => 'section_3_page',
			'type' => 'select',
			'options' => $options_pages);

	$options[] = array(
		'name' => __('Section 4', 'options_check'),
		'type' => 'heading');		
		
		$options[] = array(
			'desc' => __('Display This Section?', 'options_check'),
			'id' => 'section_4_display',
			'type' => 'checkbox');

		$options[] = array(
			'name' => __('Choose Page' , 'options_check'),
			'id' => 'section_4_page',
			'type' => 'select',
			'options' => $options_pages);

	
	$options[] = array(
		'name' => __('Page Banner', 'options_check'),
		'type' => 'heading');
		
		$options[] = array(
			'name' => __('Header Image for pages', 'options_check'),
			'desc' => __('This image will appear on the header of pages', 'options_check'),
			'id' => 'page_banner',
			'type' => 'upload');

	$options[] = array(
		'name' => __('Footer', 'options_check'),
		'type' => 'heading');
	
		
		$options[] = array(
			'desc' => __('<h3>Instagram</h3>', 'options_check'),
			'type' => 'info');

		$options[] = array(
			'desc' => __('Instagram Title', 'options_check'),
			'id' => 'insta_title',
			'type' => 'text');

		$options[] = array(
			'desc' => __('Instagram Username', 'options_check'),
			'id' => 'insta_username',
			'type' => 'text');
					

		$options[] = array(
			'name' => __('Footer Logo', 'options_check'),
			'desc' => __('Footer Logo', 'options_check'),
			'id' => 'footer_logo',
			'type' => 'upload');

		$options[] = array(
			'desc' => __('<h3>Footer Additional Links</h3>', 'options_check'),
			'type' => 'info');

		$options[] = array(
			'name' => __('Link 1', 'options_check'),
			'desc' => __('Choose Page' , 'options_check'),
			'id' => 'footer_link_1',
			'type' => 'select',
			'options' => $options_pages);
	
		$options[] = array(
			'name' => __('Link 2', 'options_check'),
			'desc' => __('Choose Page' , 'options_check'),
			'id' => 'footer_link_2',
			'type' => 'select',
			'options' => $options_pages);

		
	$options[] = array(
		'name' => __('Social', 'options_check'),
		'type' => 'heading');
		
		$options[] = array(
			'name' => __('Facebook', 'options_check'),
			'desc' => __('Enter your facebook url.', 'options_check'),
			'id' => 'link_facebook',
			'type' => 'text');

		$options[] = array(
			'name' => __('Twitter', 'options_check'),
			'desc' => __('Enter your twitter url.', 'options_check'),
			'id' => 'link_twitter',
			'type' => 'text');

		$options[] = array(
			'name' => __('Linked In', 'options_check'),
			'desc' => __('Enter your linked-in url.', 'options_check'),
			'id' => 'link_linkedin',
			'type' => 'text');

		$options[] = array(
			'name' => __('Google +', 'options_check'),
			'desc' => __('Enter your google + url.', 'options_check'),
			'id' => 'link_googleplus',
			'type' => 'text');

		$options[] = array(
			'name' => __('Pinterest', 'options_check'),
			'desc' => __('Enter your pinterest url.', 'options_check'),
			'id' => 'link_pinterest',
			'type' => 'text');

		$options[] = array(
			'name' => __('Instagram', 'options_check'),
			'desc' => __('Enter your instagram url.', 'options_check'),
			'id' => 'link_instagram',
			'type' => 'text');

	return $options;
}