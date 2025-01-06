<?php

/**
 * This file is for action functions of the plugin.
 *
 * @link       https://https://profiles.wordpress.org/smit08/
 * @since      1.0.0
 */

add_action( 'admin_menu', 'tagpilot_ai_create_menu' );

function tagpilot_ai_create_menu() {
	// Add a top-level menu
	add_menu_page(
		'TagPilot AI Settings',
		'TagPilot AI',
		'manage_options',
		'tagpilot-ai-plugin-settings',
		'tagpilot_ai_settings_page',
		'dashicons-admin-generic',
		6
	);
}
