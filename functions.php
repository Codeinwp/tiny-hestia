<?php
/**
 * Hestia Bare Bones functions and definitions.
 *
 * @package barebones
 * @since 1.0.0
 */

/**
 * Enqueue style of parent theme and styles from current child theme.
 *
 * @since 1.0.0
 */
function barebones_scripts() {
	$parent_style = 'parent-style';
	wp_enqueue_style( 'barebones-bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );


	wp_enqueue_script( 'backbone-jquery-bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), HESTIA_VENDOR_VERSION, true );

	wp_enqueue_script( 'backbone-scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array( /*'jquery-hestia-material', 'jquery-ui-core'*/ ),HESTIA_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'barebones_scripts',99);

/**
 * Function necessary to inherit theme mods from parent theme
 *
 * @param string $value Current value.
 * @param string $old_value Old value.
 *
 * @return mixed
 * @since 1.0.0
 */
function barebones_update_theme_mods( $value, $old_value ){
	update_option( 'theme_mods_' . get_template(), $value );
	return $old_value; // prevent update to child theme mods
}

/**
 * Function necessary to inherit theme mods from parent theme
 * @param string $default Default value.
 *
 * @return mixed
 * @since 1.0.0
 */
function barebones_get_theme_mods( $default ){
	return get_option( 'theme_mods_' . get_template(), $default );
}

if ( get_stylesheet() !== get_template() ) {
	add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), 'barebones_update_theme_mods', 10, 2 );
	add_filter( 'pre_option_theme_mods_' . get_stylesheet(), 'barebones_get_theme_mods' );
}

/**
 * Remove sidebars that are no longer used in this child-theme.
 *
 * @since 1.0.0
 */
function barebones_unrgister_sidebar(){
	$sidebars_array = array(
		'subscribe-widgets',
		'blog-subscribe-widgets',
	);

	foreach ( $sidebars_array as $sidebar_id ) {
		unregister_sidebar( $sidebar_id );
	}
}
add_action( 'widgets_init', 'barebones_unrgister_sidebar', 20 );

/**
 * Dequeue scripts that are no longer used
 *
 * Hooked to the wp_enqueue_scripts action, with a late priority (20),
 * so that it is after the script was enqueued.
 *
 * @since 1.0.0
 */
function barebones_dequeue_script() {

	// Bootstrap
	wp_deregister_style('bootstrap');

	// Deregister Customizer Style
	if ( is_customize_preview() ) {
		wp_deregister_style( 'hestia-customizer-preview-style' );
	}

	// Dequeue parent's theme Bootstrap js
	wp_dequeue_script( 'jquery-bootstrap' );

	// Dequeue material script
	wp_dequeue_script( 'jquery-hestia-material' );

	// Dequeue scriptsfrom parent
	wp_dequeue_script( 'hestia_scripts' );
}
add_action( 'wp_enqueue_scripts', 'barebones_dequeue_script', 20 );

/**
 * Remove unnecessary actions from theme.
 *
 * @since 1.0.0
 */
function barebones_remove_actions_and_filters() {

	remove_action('after_setup_theme', 'hestia_starter_content');

	remove_filter( 'frontpage_template', 'hestia_filter_front_page_template' );

	remove_filter( 'hestia_filter_features', 'hestia_filter_features' );
}
add_action( 'after_setup_theme', 'barebones_remove_actions_and_filters', 0 );


/**
 * Remove sections and files from companion.
 *
 * @since 1.0.0
 * @return array
 */
function barebones_remove_from_companion(){
	return array();
}
add_filter( 'themeisle_companion_hestia_sections', 'barebones_remove_from_companion' );
add_filter( 'themeisle_companion_hestia_controls', 'barebones_remove_from_companion' );

/**
 * Filter classes on recaptcha warp in pirateforms to remove the code from scripts.js
 *
 * @since 1.0.0
 * @return array
 */
function barebones_pirateforms_recaptcha_classes(){
	return array('col-md-12 col-xs-12 col-sm-6 form_field_wrap form_captcha_wrap');
}
add_filter( 'pirateform_wrap_classes_captcha', 'barebones_pirateforms_recaptcha_classes' );

/**
 * Define Allowed Files to be included.
 *
 * @since 1.0.0
 */
function barebones_filter_features( $array ){
	$files_to_load = array(
		'features/feature-general-settings',
		'features/feature-color-settings',
		'features/feature-theme-info-section',
		'features/feature-header-settings',
		'features/feature-about-page',
		'typography/typography-settings',
	);

	return array_merge(
		$array, $files_to_load
	);
}
add_filter( 'hestia_filter_features', 'barebones_filter_features' );



