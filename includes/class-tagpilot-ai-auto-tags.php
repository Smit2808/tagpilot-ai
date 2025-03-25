<?php
/**
 * Class Tagpilot_Ai_Auto_Tags
 *
 * Handles the automatic tagging of posts using AI.
 *
 * @package Tagpilot_AI
 * @link    https://profiles.wordpress.org/smit08/
 * @since   1.0.0
 */

class Tagpilot_Ai_Auto_Tags {

	/**
	 * Constructor
	 *
	 * Initializes the class by setting up the necessary hooks.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'save_post', array( $this, 'tagpilot_ai_save_post' ), 10, 3 );
	}

	/**
	 * Handles the save post action to scan the post for auto tags.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 *
	 * @return void
	 */
	public function tagpilot_ai_save_post( $post_id, $post ) {
		// Check if this is a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check if post type is set.
		if ( ! isset( $post->post_type ) ) {
			return;
		}

		$auto_tagging_disabled = get_post_meta( $post_id, '_tagpilot_ai_disable_auto_tagging', true );

		if ( $auto_tagging_disabled === 'yes' ) {
			return;
		}

		// Auto tag the post.
		self::tagpilot_ai_auto_tag_for_posts( $post_id, $post );
	}

	/**
	 * Automatically tags the post based on its content.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 *
	 * @return bool
	 */
	public static function tagpilot_ai_auto_tag_for_posts( $post_id, $post ) {
		$options = get_option( 'tagpilot_ai_settings' );

		// Check if options are empty.
		if ( empty( $options ) ) {
			return false;
		}

		// Determine content source for auto terms.
		$content_source = ! empty( $options['tagpilot_ai_auto_terms_from'] ) ? $options['tagpilot_ai_auto_terms_from'] : 'post_content_title';
		if ( $content_source === 'post_title' ) {
			$content = $post->post_title;
		} elseif ( $content_source === 'post_content' ) {
			$content = $post->post_content;
		} else {
			$content = $post->post_content . ' ' . $post->post_title;
		}

		// Append post excerpt if available.
		if ( isset( $post->post_excerpt ) ) {
			$content .= ' ' . $post->post_excerpt;
		}

		// Clean up the content.
		$content = trim( wp_strip_all_tags( $content ) );

		// Check if content is empty.
		if ( empty( $content ) ) {
			return false;
		}

		$post_title = $post->post_title;

		$args = array(
			'post_id'        => $post_id,
			'settings_data'  => $options,
			'content'        => $content,
			'clean_content'  => self::tagpilot_ai_content_cleaning_for_posts( $content, $post_title ),
			'content_source' => $content_source,
		);

		// Get results from Dandelion API.
		$dandelion_results = Tagpilot_Ai_Api_Helper_Functions::tagpilot_ai_get_dandelion_api_results( $args );

		// Assign tags to the post if results are available.
		if ( ! empty( $dandelion_results['results'] ) ) {
			$tag_results = $dandelion_results['results'];
			wp_set_post_tags( $post_id, $tag_results, true );
		}
	}

	/**
	 * Cleans the content for processing.
	 *
	 * @param string $content    Post content.
	 * @param string $post_title Post title.
	 *
	 * @return string Cleaned content.
	 */
	public static function tagpilot_ai_content_cleaning_for_posts( $content, $post_title ) {
		// Return empty string if both post content and title are empty.
		if ( empty( $content ) && empty( $post_title ) ) {
			return '';
		}

		// Apply content and title filters if provided.
		if ( ! empty( $content ) ) {
			$content = apply_filters( 'the_content', $content );

			// Remove HTML entities.
			$content = preg_replace( '/&#?[a-z0-9]{2,8};/i', '', $content );

			// Remove abbreviations.
			$content = preg_replace( '/[A-Z][A-Z]+/', '', $content );

			// Replace HTML line breaks with newlines.
			$content = preg_replace( '#<br\s?/?>#', "\n\n", $content );

			// Strip all remaining HTML tags.
			$content = wp_strip_all_tags( $content );
		}

		// Initialize the cleaned-up content variable.
		$cleaned_up_content = '';

		// Combine post title and content if both are available.
		if ( ! empty( $content ) && ! empty( $post_title ) ) {
			$cleaned_up_content = $post_title . ".\n\n" . $content;
		} elseif ( ! empty( $content ) ) { // Use post content if title is empty.
			$cleaned_up_content = $content;
		} elseif ( ! empty( $post_title ) ) { // Use post title if content is empty.
			$cleaned_up_content = $post_title;
		}

		// Return the cleaned-up content.
		return $cleaned_up_content;
	}
}

// Initialize the class if it exists.
if ( class_exists( 'Tagpilot_Ai_Auto_Tags' ) ) {
	new Tagpilot_Ai_Auto_Tags();
}
