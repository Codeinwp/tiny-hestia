<?php
/**
 * Frontpage main file
 *
 * @package barebones
 * @since 1.0.0
 */

if ( 'posts' == get_option( 'show_on_front' ) ) {
	include( get_home_template() );
} else {
	include( get_page_template() );
}