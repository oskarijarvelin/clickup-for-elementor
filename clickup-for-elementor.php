<?php

/**
 * Plugin Name: ClickUp-integration / action for Elementor Pro forms
 * Description: Create tasks from Elementor Pro form submissions.
 * Author: Oskari Järvelin
 * Author URI: https://oskarijarvelin.fi/
 * Version: 1.0.0
 */

// You shall not pass (directly)
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

// Initialize CFE
add_action( 'elementor_pro/init', function() {
	include_once( dirname(__FILE__).'/class-cfe.php' );
	$cfe_action = new ClickUp_For_Elementor_Action();
	\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $cfe_action->get_name(), $cfe_action );
});

// Show error message if site has no active Elementor Pro -plugin
add_action('admin_notices', 'cfe_has_active_elementor_pro');
function cfe_has_active_elementor_pro() {
	if ( !is_plugin_active('elementor-pro/elementor-pro.php') ) {
		echo "<div class='error'><p><strong>ClickUp for Elementor</strong> requires active <strong>Elementor Pro</strong> -plugin.</p></div>";
	}
}