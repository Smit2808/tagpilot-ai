<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://profiles.wordpress.org/smit08/
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       TagPilot AI – Smart Auto-Tagging for WordPress
 * Plugin URI:        https://https://profiles.wordpress.org/smit08/
 * Description:       TagPilot AI is your intelligent companion for effortless content organization. This plugin automatically analyzes your posts, assigns relevant tags, and saves you time.
 * Version:           1.0.0
 * Author:            Smit Rathod
 * Author URI:        https://https://profiles.wordpress.org/smit08//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tagpilot-ai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TAGPILOT_AI_VERSION', '1.0.0' );

if ( ! defined( 'TAGPILOT_AI_PLUGIN_PATH' ) ) {
	define( 'TAGPILOT_AI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

require_once TAGPILOT_AI_PLUGIN_PATH . 'includes/tagpilot-ai-action-function.php';

require_once TAGPILOT_AI_PLUGIN_PATH . 'templates/tagpilot-ai-setings-page.php';
