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
		 * Returns all the gravity forms as options.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return Array | An associative array of forms [$id => $title]
		 **/
		public static function get_forms_as_options() {
			$retval = array();
			$forms = GFAPI::get_forms();

			foreach( $forms as $form ) {
				$retval[strval($form['id'])] = $form['title'];
			}

			return $retval;
		}

		/**
		 * Handles changing the post type
		 * @author Jim Barnes
	     * @since 1.0.0
		 * @param $post_data Array | An array of post data from the form.
		 * @param $form Array | An array of data about the form.
		 * @param $entry Array | An array of data about an existing entry.
		 * @return Array | The modified form data.
		 **/
		public static function announcement_save( $post_data, $form, $entry ) {
			$form_id = UCF_Announcements_Config::get_option_or_default( 'form' );
			if ( $form['id'] == $form_id ) {
				$post_data['post_type'] = 'announcement';
			}

			return $post_data;
		}
	}
}
?>
