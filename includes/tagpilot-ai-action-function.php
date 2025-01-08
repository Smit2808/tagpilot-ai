<?php

/**
 * This file is for action functions of the plugin.
 *
 * @link       https://profiles.wordpress.org/smit08/
 * @since      1.0.0
 */

// Hook to add a menu item to the admin menu
add_action( 'admin_menu', 'tagpilot_ai_create_menu' );

/**
 * Create a top-level menu in the WordPress admin.
 *
 * @since 1.0.0
 */
function tagpilot_ai_create_menu() {
	// Add a top-level menu
	add_menu_page(
		__( 'TagPilot AI Settings', 'tagpilot-ai' ), // Page title
		__( 'TagPilot AI', 'tagpilot-ai' ), // Menu title
		'manage_options', // Capability
		'tagpilot-ai-plugin-settings', // Menu slug
		'tagpilot_ai_settings_page', // Function to display the page content
		'dashicons-admin-generic', // Icon
		6 // Position
	);
}

// Hook to enqueue styles in the admin area
add_action( 'admin_enqueue_scripts', 'tagpilot_ai_enqueue_styles' );

/**
 * Enqueue admin styles.
 *
 * @since 1.0.0
 */
function tagpilot_ai_enqueue_styles() {
	wp_enqueue_style( 'tagpilot-ai-style', TAGPILOT_AI_PLUGIN_URL . 'tagpilot-ai/assets/css/tagpilot-ai-style.css', array(), '1.0.0' );
}

