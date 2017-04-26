<?php
/**
 * Handles common tasks
 **/
if ( ! class_exists( 'UCF_Announcements_Common' ) ) {
	class UCF_Announcements_Common {
		/**
		 * Handles activation functions.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function activate() {
			$args = array(
				'post_type'  => 'page',
				'post_name'  => 'post-an-announcement',
				'post_title' => 'Post An Announcement'
			);

			$pages = get_posts( $args );

			if ( count( $pages ) === 0 ) {
				$args = array(
					'post_type'  => 'page',
					'post_name'  => 'post-an-announcement',
					'post_title' => 'Post An Announcement'
				);

				wp_insert_post( $args );
			}

			flush_rewrite_rules( false );
		}

		/**
		 * Handles deactivation functions.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function deactivate() {
			flush_rewrite_rules();
		}

		/**
		 * Sets the page template for the create-announcement page.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $page_template string | The page template to use
		 * @return string | The modified page template
		 **/
		public static function page_templates( $page_template ) {
			if ( is_page( 'post-an-announcement' ) ) {
				$page_template = plugin_dir_path( __FILE__ ) . '../templates/ucf-announcements-template.php';
			}

			return $page_template;
		}

		/**
		 * Function responsible for handling the announcement form.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $postdata | The post data
		 * @return bool|Array | Returns true if the post is
		 * submitted successfully. Returns an array of error strings if not.
		 **/
		public static function submit_announcement( $postdata ) {
			$errors = array();
			$args = array();

			if ( isset( $postdata['post_title'] ) && ! empty( $postdata['post_title'] ) ) {
				$args['post_title'] = sanitize_text_field( $postdata['post_title'] );
			} else {
				$errors['post_title'] = '"Announcement Title" is required.';
			}

			if ( isset( $postdata['post_content'] ) && ! empty( $postdata['post_content'] ) ) {
				$args['post_content'] = sanitize_text_field( $postdata['post_content'] );
			} else {
				$errors['post_content'] = '"Description" is required.';
			}

			if ( isset( $postdata['audience'] ) && ! empty( $postdata['audience'] ) ) {
				foreach( $postdata['audience'] as $audience ) {
					$args['tax_input'][] = array( 'taxonomy' => ' audience', 'term' => $audience );
				}
			} else {
				$errors['audience'] = '"Audience" is required.';
			}

			if ( isset( $postdata['keywords'] ) && ! empty( $postdata['keywords'] ) ) {
				// do something
			}

			if ( isset( $postdata['start_date'] ) && ! empty( $postdata['start_date'] ) ) {
				try {
					$start_date = new DateTime( sanitize_text_field( $postdata['start_date'] ) );
				}
				catch (Exception $e) {
					$errors['start_date'] = 'Please enter a valid date. E.g. 2017-04-29';
				}
			} else {
				$errors['start_date'] = '"Start Date" is required.';
			}

			if ( isset( $postdata['end_date'] ) && ! empty( $postdata['end_date'] ) ) {
				try {
					$start_date = new DateTime( sanitize_text_field( $postdata['end_date'] ) );
				}
				catch (Exception $e) {
					$errors['end_date'] = 'Please enter a valid date. E.g. 2017-04-29';
				}
			} else {
				$errors['end_date'] = '"End Date" is required.';
			}

			if ( count( $errors ) > 0 ) {
				return $errors;
			}

			return true;
		}
	}
}
?>
