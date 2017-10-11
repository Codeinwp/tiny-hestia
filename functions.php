<?php
/**
 * Tiny Hestia functions and definitions.
 *
 * @package tiny-hestia
 * @since 1.0.0
 */

define( 'TINY_HESTIA_VERSION', '1.0.6');
/**
 * Enqueue style of parent theme and styles from current child theme.
 *
 * @since 1.0.0
 */
function tiny_hestia_scripts() {

	wp_enqueue_style( 'tiny-hestia-bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), TINY_HESTIA_VERSION );
	wp_enqueue_style( 'tiny-hestia-style', get_stylesheet_uri(), array(), TINY_HESTIA_VERSION );
	wp_enqueue_script( 'tiny-hestia-jquery-bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), TINY_HESTIA_VERSION, true );
	wp_enqueue_script( 'tiny-hestia-scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array(),TINY_HESTIA_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'tiny_hestia_scripts',9);

/**
 * Dequeue scripts that are no longer used
 *
 * Hooked to the wp_enqueue_scripts action, with a late priority (20),
 * so that it is after the script was enqueued.
 *
 * @since 1.0.0
 */
function tiny_hestia_dequeue_script() {

	// Bootstrap
	wp_deregister_style('bootstrap');

	// Customizer style
	if ( is_customize_preview() ) {
		wp_deregister_style( 'hestia-customizer-preview-style' );
	}

	// Font awesome
	wp_deregister_style('font-awesome');

	// Hestia Style
	wp_deregister_style('hestia_style');

	// Deregister Customizer Style
	if ( is_customize_preview() ) {
		wp_deregister_style( 'hestia-customizer-preview-style' );
	}

	// Dequeue parent's theme Bootstrap js
	wp_dequeue_script( 'jquery-bootstrap' );

	// Dequeue material script
	wp_dequeue_script( 'jquery-hestia-material' );

	// Dequeue scripts from parent
	wp_dequeue_script( 'hestia_scripts' );

	// Dequeue hammer.js from pro
	wp_dequeue_script( 'jquery-hammer' );

	// Dequeue tabs script from pro
	wp_dequeue_script( 'hestia-tabs-addon-script' );



}
add_action( 'wp_enqueue_scripts', 'tiny_hestia_dequeue_script', 20 );


/**
 * Enqueue customizer scripts
 *
 * @since 1.0.0
 */
function tiny_hestia_customizer_scripts() {
	wp_enqueue_script( 'tiny-hestia-customizer-scripts', get_stylesheet_directory_uri() . '/assets/js/customizer-controls.js', array( 'jquery', 'customize-preview' ), TINY_HESTIA_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'tiny_hestia_customizer_scripts' );

/**
 * Dequeue scripts that are loading in customizer.
 *
 * @since 1.0.0
 */
function tiny_hestia_dequeue_customizer_scripts(){

	// Dequeue customizer controls script
	wp_dequeue_script( 'hestia_customize_controls' );
}
add_action( 'customize_controls_enqueue_scripts', 'tiny_hestia_dequeue_customizer_scripts', 20 );

/**
 * Function necessary to inherit theme mods from parent theme
 *
 * @param string $value Current value.
 * @param string $old_value Old value.
 *
 * @return mixed
 * @since 1.0.0
 */
function tiny_hestia_update_theme_mods( $value, $old_value ){
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
function tiny_hestia_get_theme_mods( $default ){
	return get_option( 'theme_mods_' . get_template(), $default );
}

if ( get_stylesheet() !== get_template() ) {
	add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), 'tiny_hestia_update_theme_mods', 10, 2 );
	add_filter( 'pre_option_theme_mods_' . get_stylesheet(), 'tiny_hestia_get_theme_mods' );
}

/**
 * Remove sidebars that are no longer used in this child-theme.
 *
 * @since 1.0.0
 */
function tiny_hestia_unrgister_sidebar(){
	$sidebars_array = array(
		'subscribe-widgets',
		'blog-subscribe-widgets',
	);

	foreach ( $sidebars_array as $sidebar_id ) {
		unregister_sidebar( $sidebar_id );
	}
}
add_action( 'widgets_init', 'tiny_hestia_unrgister_sidebar', 20 );

/**
 * Remove unnecessary actions from theme.
 *
 * @since 1.0.0
 */
function tiny_hestia_remove_actions_and_filters() {

	remove_action('after_setup_theme', 'hestia_starter_content');

	remove_filter( 'frontpage_template', 'hestia_filter_front_page_template' );

	remove_filter( 'hestia_filter_features', 'hestia_filter_features' );

	remove_action( 'hestia_blog_social_icons', 'hestia_social_icons' );

}
add_action( 'after_setup_theme', 'tiny_hestia_remove_actions_and_filters', 0 );


/**
 * Remove sections and files from companion.
 */
add_filter( 'themeisle_companion_hestia_sections', '__return_empty_array' );
add_filter( 'themeisle_companion_hestia_controls', '__return_empty_array' );

/**
 * Filter classes on recaptcha warp in pirateforms to remove the code from scripts.js
 *
 * @since 1.0.0
 * @return array
 */
function tiny_hestia_pirateforms_recaptcha_classes(){
	return array('col-md-12 col-xs-12 col-sm-6 form_field_wrap form_captcha_wrap');
}
add_filter( 'pirateform_wrap_classes_captcha', 'tiny_hestia_pirateforms_recaptcha_classes' );

/**
 * Define Allowed Files to be included.
 *
 * @since 1.0.0
 */
function tiny_hestia_filter_features( $array ){
	$files_to_load = array(
		'features/feature-general-settings',
		'features/feature-color-settings',
		'features/feature-theme-info-section',
		'features/feature-header-settings',
		'features/feature-about-page',
		'typography/typography-settings',
		'features/feature-pro-manager',
	);

	return array_merge(
		$array, $files_to_load
	);
}
add_filter( 'hestia_filter_features', 'tiny_hestia_filter_features' );

/**
 * Filter nav classes.
 *
 * @param string $classes Classes on navbar.
 *
 * @return string
 * @since 1.0.0
 */
function tiny_hestia_filter_nav_classes( $classes ){
	$classes = explode(' ', $classes);
	if( !empty( $classes ) ){
		foreach( $classes as $key => $nav_class ){
			if( $nav_class === 'navbar-color-on-scroll' || $nav_class === 'navbar-transparent' ){
				unset( $classes[$key] );
			}
		}
	}
	return implode(' ', $classes);
}
add_filter('hestia_header_classes','tiny_hestia_filter_nav_classes');


/**
 * Get inline style from customizer.
 * This function is necessary because there isn't a script with a handle 'hestia_script' ( we removed it ) so the
 * wp_add_inline_style won't add inline style for top bar.
 *
 * @since 1.0.0
 */
function tiny_hestia_top_bar_inline_style() {
	if( !function_exists( 'hestia_get_top_bar_style' ) ){
		return;
	}
	$custom_css = hestia_get_top_bar_style();
	wp_add_inline_style( 'tiny-hestia-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'tiny_hestia_top_bar_inline_style', 99 );


/**
 * Adds inline style from customizer
 *
 * @since 1.0.1
 */
function tiny_hestia_typography_style() {

	if( !function_exists('hestia_get_fonts_style') ){
		return;
	}
	$custom_css = hestia_get_fonts_style();
	wp_add_inline_style( 'tiny-hestia-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'tiny_hestia_typography_style', 99 );

/**
 * Change default fonts to system fonts.
 *
 * @since 1.0.1
 */
function tiny_hestia_customize_register( $wp_customize ) {

	$hestia_headings_font = $wp_customize->get_setting( 'hestia_headings_font');
	if( !empty( $hestia_headings_font ) ){
		$hestia_headings_font->default = 'Arial, Helvetica, sans-serif';
	}

	$hestia_body_font = $wp_customize->get_setting( 'hestia_body_font' );
	if( !empty( $hestia_body_font ) ){
		$hestia_body_font->default = 'Arial, Helvetica, sans-serif';
	}

	$sections_to_remove = array(
		'hestia_info_woocommerce',
		'hestia_info_jetpack',
		'hestia-theme-info-section',
		'hestia_theme_info_main_section'
	);
	foreach ( $sections_to_remove as $section ){
		$customize_section = $wp_customize->get_section( $section );
		if( !empty( $customize_section ) ){
			$wp_customize->remove_section( $section );
		}
	}

}
add_action( 'customize_register', 'tiny_hestia_customize_register', 99 );


/**
 * Change default parameter for headings and body fonts
 *
 * @since 1.0.1
 */
function tiny_hestia_default_fonts(){
	return 'Arial, Helvetica, sans-serif';
}
add_filter('hestia_headings_default', 'tiny_hestia_default_fonts');
add_filter('hestia_body_font_default', 'tiny_hestia_default_fonts');


/**
 * Change the handle where to add inline style.
 * @return string
 */
function tiny_hestia_custom_css(){
	return 'tiny-hestia-style';
}
add_filter('hestia_custom_color_handle','tiny_hestia_custom_css');