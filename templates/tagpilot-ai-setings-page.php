<?php

/**
 * This file is for HTML structure of plugin's settings page.
 *
 * @package    Tagpilot_AI
 * @link       https://profiles.wordpress.org/smit08/
 * @since      1.0.0
 */

/**
 * Display the settings page for the TagPilot AI plugin.
 *
 */
function tagpilot_ai_settings_page() {
	// Retrieve the plugin settings from the database.
	$options = get_option( 'tagpilot_ai_settings' );
	?>
	<div class="tagpilot-ai-settings-page-wrap">
		<h1><?php esc_html_e( 'TagPilot AI Settings', 'tagpilot-ai' ); ?></h1>
		<form method="post" action="options.php">
		   <?php
			// Output security fields for the registered setting "tagpilot_ai_settings_group".
			settings_fields( 'tagpilot_ai_settings_group' );

			// Output setting sections and their fields.
			do_settings_sections( 'tagpilot_ai_settings_group' );
			?>
		   <table class="form-table">
			   <tr valign="top">
			   <th scope="row"><?php esc_html_e( 'Dandelion API Key', 'tagpilot-ai' ); ?></th>
			   <td><input type="text" name="tagpilot_ai_settings[tagpilot_ai_api_key]" value="<?php echo esc_attr( $options['tagpilot_ai_api_key'] ?? '' ); ?>" /></td>
			   </tr>
			   <tr valign="top">
			   <th scope="row"><?php esc_html_e( 'Dandelion API Confidence Value', 'tagpilot-ai' ); ?></th>
			   <td><input type="number" name="tagpilot_ai_settings[tagpilot_ai_api_confidence]" value="<?php echo esc_attr( $options['tagpilot_ai_api_confidence'] ?? '0.7' ); ?>" min="0" max="1" step="0.1" /></td>
			   </tr>
			   <tr valign="top">
			   <th scope="row"><?php esc_html_e( 'Make Auto Terms From', 'tagpilot-ai' ); ?></th>
			   <td>
				   <select name="tagpilot_ai_settings[tagpilot_ai_auto_terms_from]">
					   <option value="post_title" <?php selected( $options['tagpilot_ai_auto_terms_from'] ?? 'both', 'title' ); ?>><?php esc_html_e( 'Post Title', 'tagpilot-ai' ); ?></option>
					   <option value="post_content" <?php selected( $options['tagpilot_ai_auto_terms_from'] ?? 'both', 'content' ); ?>><?php esc_html_e( 'Post Content', 'tagpilot-ai' ); ?></option>
					   <option value="both" <?php selected( $options['tagpilot_ai_auto_terms_from'] ?? 'both', 'both' ); ?>><?php esc_html_e( 'Both', 'tagpilot-ai' ); ?></option>
				   </select>
			   </td>
			   </tr>
		   </table>
		   <?php
		   // Output save settings button.
		   submit_button();
		   ?>
		</form>
	</div>
	<?php
}

/**
 * Register settings for the TagPilot AI plugin.
 *
 */
function tagpilot_ai_register_settings() {
	// Register new settings for "tagpilot_ai_settings_group" page.
	register_setting( 'tagpilot_ai_settings_group', 'tagpilot_ai_settings' );
}

// Hook into the 'admin_init' action to register the settings.
add_action( 'admin_init', 'tagpilot_ai_register_settings' );

