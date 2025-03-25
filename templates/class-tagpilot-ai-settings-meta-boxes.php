<?php
class Tagpilot_Ai_Settings_Meta_Boxes {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'tagpilot_ai_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'tagpilot_ai_save_meta_box_data' ) );
	}

	public function tagpilot_ai_add_meta_box() {
		add_meta_box(
			'tagpilot_ai_meta_box', // ID
			'TagPilot AI Settings', // Title
			array( $this, 'meta_box_callback' ), // Callback
			'post', // Screen
			'side', // Context
			'default' // Priority
		);
	}

	public function meta_box_callback( $post ) {
		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'tagpilot_ai_save_meta_box_data', 'tagpilot_ai_meta_box_nonce' );

		$value = get_post_meta( $post->ID, '_tagpilot_ai_disable_auto_tagging', true );
		$value = $value ? $value : 'no'; // Default value is 'no'

		?>
		<p>Disable auto tagging for this post?</p>
		<label class="tagpilot-ai-radio-yes-button">
			<input type="radio" name="tagpilot_ai_disable_auto_tagging" value="yes" <?php checked( $value, 'yes' ); ?> />
			Yes
		</label>
		<label>
			<input type="radio" name="tagpilot_ai_disable_auto_tagging" value="no" <?php checked( $value, 'no' ); ?> />
			No
		</label>
		<?php
	}

	public function tagpilot_ai_save_meta_box_data( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['tagpilot_ai_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['tagpilot_ai_meta_box_nonce'], 'tagpilot_ai_save_meta_box_data' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		// Make sure that it is set.
		if ( ! isset( $_POST['tagpilot_ai_disable_auto_tagging'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['tagpilot_ai_disable_auto_tagging'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, '_tagpilot_ai_disable_auto_tagging', $my_data );
	}
}

new Tagpilot_Ai_Settings_Meta_Boxes();
?>
