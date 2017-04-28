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
		 * Handles changing the post type and adding postmeta
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

		/**
		 * Maps gravity form inputName to ids
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $form Form | A gravity form object.
		 * @return Form
		 **/
		public static function map_field_ids( $form ) {
			$retval = array();

			foreach( $form['fields'] as $key => $field ) {
				$retval[$field->inputName] = $field->id;
			}

			return $retval;
		}

		/**
		 * Handles setting an announcements metadata after the gravity form
		 * is submitted.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $entry Entry | The gravity form entry
		 * @param $form Form | The form that was submitted.
		 **/
		public static function anouncement_post_tax_save( $entry, $form ) {
			$form_id = UCF_Announcements_Config::get_option_or_default( 'form' );
			if ( ! $form['id'] == $form_id ) {
				return;
			}

			$post = get_post( $entry['post_id'] );
			$field_ids = self::map_field_ids( $form );

			if ( $post ) {
				$keywords = $audience_roles = $entry_keywords = $entry_audience_roles = array();

				foreach( $entry as $key => $val ) {
					if ( substr( $key, 0, 1 ) == $field_ids['audience'] && ! empty( $val ) ) {
						$entry_audience_roles[] = $val;
					} else  if ( $key == $field_ids['keywords'] ) {
						$entry_keywords = explode( ',', $val );
					}
				}

				if ( ! empty( $entry_audience_roles ) ) {
					foreach( $entry_audience_roles as $role ) {
						$role_term = get_term_by( 'name', $role, 'audienceroles', 'ARRAY_A' );
						if ( is_array( $role_term ) ) {
							$audience_roles[] = intval( $role_term['term_id'] );
						}
					}
				}

				if ( ! empty( $entry_keywords ) ) {
					foreach( $entry_keywords as $keyword ) {
						$keyword_term = get_term_by( 'name', $keyword, 'keywords', 'ARRAY_A' );

						if ( ! $keyword_term ) {
							$keyword_term = wp_insert_term( $keyword, 'keywords' );
						}

						if ( is_array( $keyword_term ) ) {
							$keywords[] = intval( $keyword_term['term_id'] );
						}
					}
				}

				if ( ! empty( $audience_roles ) ) {
					wp_set_object_terms( $post->ID, $audience_roles, 'audienceroles' );
				}

				if ( ! empty( $keywords ) ) {
					wp_set_object_terms( $post->ID, $keywords, 'keywords' );
				}
			}
		}

		/**
		 * Tells WordPress not to texturize the output of
		 * the announcements shortcode.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $shortcode Array<string> | An array of shortcodes
		 * @return Array<string> | The modified array of shortcodes
		 **/
		public static function no_texturize_me( $shortcodes ) {
			$shortcodes[] = 'announcements';
			return $shortcodes;
		}
	}
}
?>
