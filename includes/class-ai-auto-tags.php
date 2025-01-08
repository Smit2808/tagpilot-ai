<?php

class Ai_Auto_Tags {

	/**
	 * Constructor
	 *
	 * @return void
	 * @author Smit Rathod
	 */
	public function __construct() {
		 add_action( 'save_post', array( $this, 'tagpilot_ai_save_post' ), 10, 3 );
	}

	/**
	 * Scan current post for ID, title, and content for auto tags.
	 *
	 * @param integer $post_id
	 * @param object  $post
	 *
	 * @return boolean
	 */
	public function tagpilot_ai_save_post( $post_id, $post, $update ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $post->post_type ) ) {
			return;
		}

		// Loop option for find if autoterms is actived on any taxonomy and post type
		$current_post_type   = $post->post_type;
		$current_post_status = $post->post_status;

		self::tagpilot_ai_auto_tag_for_posts( $post_id, $post );
	}

	public static function tagpilot_ai_auto_tag_for_posts( $post_id, $post ) {
		$options = get_option( 'tagpilot_ai_settings' );

		if (empty($options)) {
			return false;
		}

		$content_source = !empty($options['tagpilot_ai_auto_terms_from']) ? $options['tagpilot_ai_auto_terms_from'] : 'post_content_title';
		if ($content_source === 'post_title') {
			$content = $post->post_title;
		} elseif ($content_source === 'post_content') {
			$content = $post->post_content;
		} else {
			$content = $post->post_content . ' ' . $post->post_title;
		}

		if (isset($post->post_excerpt)) {
			$content .= ' ' . $post->post_excerpt;
		}

		$content = trim(strip_tags($content));

		if (empty($content)) {
			return false;
		}

		$post_title = $post->post_title;

		$args = [
			'post_id' => $post_id,
			'settings_data' => $options,
			'content' => $content,
			'clean_content' => self::tagpilot_ai_content_cleaning_for_posts($content, $post_title),
			'content_source' => $content_source
		];

		$dandelion_results = Tagpilot_Ai_Api_Helper_Functions::tagpilot_ai_get_dandelion_api_results($args);

		if (!empty($dandelion_results['results'])) {
			$tag_results = $dandelion_results['results'];
			$tags = array();

			// Assign tags to the post
			wp_set_post_tags($post_id, $tag_results, true);
		}
	}
	
	public static function tagpilot_ai_content_cleaning_for_posts( $content, $post_title ) {
		// Return empty string if both post content and title are empty
		if (empty($content) && empty($post_title)) {
			return '';
		}
	
		// Apply content and title filters if provided
		if (!empty($content)) {
			$content = apply_filters('the_content', $content);
		
			/* Remove HTML entities */
			$content = preg_replace( '/&#?[a-z0-9]{2,8};/i', '', $content );
	
			/*  Remove abbreviations */
			$content = preg_replace( '/[A-Z][A-Z]+/', '', $content );
	
			/* Replace HTML line breaks with newlines */
			$content = preg_replace( '#<br\s?/?>#', "\n\n", $content );
		
			// Strip all remaining HTML tags
			$content = wp_strip_all_tags( $content );
		}
	
		// Initialize the cleaned-up content variable
		$cleaned_up_content = '';
	
		// Combine post title and content if both are available
		if (!empty($content) && !empty($post_title)) {
			$cleaned_up_content = $post_title . ".\n\n" . $content;
		} elseif (!empty($content)) { // Use post content if title is empty
			$cleaned_up_content = $content;
		} elseif (!empty($post_title)) { // Use post title if content is empty
			$cleaned_up_content = $post_title;
		}
	
		// Return the cleaned-up content
		return $cleaned_up_content;
	}
}

if ( class_exists( 'Ai_Auto_Tags' ) ) {
	new Ai_Auto_Tags();
}
