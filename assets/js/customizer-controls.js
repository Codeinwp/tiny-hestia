/* Move controls to Widgets sections. Used for sidebar placeholders */

jQuery(document).ready( function() {

    if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_1' ) !== 'undefined' ) {
        wp.customize.control('hestia_placeholder_sidebar_1').section('sidebar-widgets-sidebar-1');
    }
    if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_woocommerce' ) !== 'undefined' ) {
        wp.customize.control('hestia_placeholder_sidebar_woocommerce').section('sidebar-widgets-sidebar-woocommerce');
    }

    jQuery( '#customize-theme-controls' ).on( 'click', '.hestia-link-to-top-menu', function(){
        wp.customize.section( 'menu_locations' ).focus();
    });

});