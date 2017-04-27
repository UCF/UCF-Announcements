<?php
/**
 * Handles the registration of the announcement custom post type.
 * @author Jim Barnes
 * @since 1.0.0
 **/
if ( ! class_exists( 'UCF_Announcements_PostType' ) ) {
	class UCF_Announcements_PostType {
		/**
		 * Registers the custom post type.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register() {
			$labels = apply_filters(
				'ucf_promo_labels',
				array(
					'singular'  => 'Announcement',
					'plural'    => 'Announcements',
					'post_type' => 'announcement'
				)
			);
			register_post_type( 'announcement', self::args( $labels ) );
			add_action( 'add_meta_boxes', array( 'UCF_Announcements_PostType', 'register_metabox' ) );
			add_action( 'save_post', array( 'UCF_Announcements_PostType', 'save_metabox' ) );
		}


		/**
		 * Adds a metabox to the promo custom post type.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register_metabox() {
			add_meta_box(
				'announcement_metabox',
				'Announcement Details',
				array( 'UCF_Announcements_PostType', 'register_metafields' ),
				'announcement',
				'normal',
				'high'
			);
		}


		/**
		 * Adds metafields to the metabox
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $post WP_POST object
		 **/
		public static function register_metafields( $post ) {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
			wp_enqueue_style('jquery-ui');

			wp_nonce_field( 'ucf_announcements_nonce_save', 'ucf_announcements_nonce' );
			$header = get_post_meta( $post->ID, 'ucf_promo_header', TRUE );
			$copy = get_post_meta( $post->ID, 'ucf_promo_copy', TRUE );
			$link_text = get_post_meta( $post->ID, 'ucf_promo_link_text', TRUE );
			$link_url = get_post_meta( $post->ID, 'ucf_promo_link_url', TRUE );
?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><label class="block" for="announcement_start_date">Start Date</label></th>
						<td>
							<p class="description">The date the announcement should <strong>begin</strong> displaying.</p>
							<input type="text" id="announcement_start_date" name="announcement_start_date" class="datepicker" <?php echo ( ! empty( $start_date ) ) ? 'value="' . $start_date . '"' : ''; ?>>
						</td>
					</tr>
					<tr>
						<th><label class="block" for="announcement_end_date">End Date</label></th>
						<td>
							<p class="description">The date the announcement should <strong>stop</strong> displaying.</p>
							<input type="text" id="announcement_end_date" name="announcement_end_date" class="datepicker" <?php echo ( ! empty( $start_date ) ) ? 'value="' . $start_date . '"' : ''; ?>>
						</td>
					</tr>
				</tbody>
			</table>
			<script>
				jQuery(document).ready(function() {
					jQuery('.datepicker').datepicker({
						dateFormat: 'yy-mm-dd'
					});
				});
			</script>
<?php
		}

		/**
		 * Handles saving the data in the metabox
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $post_id WP_POST post id
		 **/
		public static function save_metabox( $post_id ) {
			$post_type = get_post_type( $post_id );
			// If this isn't a promo, return.
			if ( 'ucf_promo' !== $post_type ) return;
			if ( isset( $_POST['ucf_promo_header'] ) ) {
				// Ensure field is valid.
				$header = sanitize_text_field( $_POST['ucf_promo_header'] );
				if ( $header ) {
					update_post_meta( $post_id, 'ucf_promo_header', $header );
				}
			}
			if ( isset( $_POST['ucf_promo_copy'] ) ) {
				// Ensure field is valid.
				$copy = $_POST['ucf_promo_copy'];
				if ( $copy ) {
					update_post_meta( $post_id, 'ucf_promo_copy', $copy );
				}
			}
			if ( isset( $_POST['ucf_promo_link_text'] ) ) {
				// Ensure field is valid.
				$link_text = sanitize_text_field( $_POST['ucf_promo_link_text'] );
				if ( $link_text ) {
					update_post_meta( $post_id, 'ucf_promo_link_text', $link_text );
				}
			}
			if ( isset( $_POST['ucf_promo_link_url'] ) ) {
				// Ensure field is valid.
				$link_url = sanitize_text_field( $_POST['ucf_promo_link_url'] );
				if ( $link_url ) {
					update_post_meta( $post_id, 'ucf_promo_link_url', $link_url );
				}
			}
		}

		/**
		 * Returns an array of labels for the custom post type.
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $singular string | The singular form for the CPT labels.
		 * @param $plural string | The plural form for the CPT labels.
		 * @param $post_type string | The post type name.
		 * @return Array
		 **/
		public static function labels( $singular, $plural, $post_type ) {
			return array(
				'name'                  => _x( $plural, 'Post Type General Name', $post_type ),
				'singular_name'         => _x( $singular, 'Post Type Singular Name', $post_type ),
				'menu_name'             => __( $plural, $post_type ),
				'name_admin_bar'        => __( $singular, $post_type ),
				'archives'              => __( $plural . ' Archives', $post_type ),
				'parent_item_colon'     => __( 'Parent ' . $singular . ':', $post_type ),
				'all_items'             => __( 'All ' . $plural, $post_type ),
				'add_new_item'          => __( 'Add New ' . $singular, $post_type ),
				'add_new'               => __( 'Add New', $post_type ),
				'new_item'              => __( 'New ' . $singular, $post_type ),
				'edit_item'             => __( 'Edit ' . $singular, $post_type ),
				'update_item'           => __( 'Update ' . $singular, $post_type ),
				'view_item'             => __( 'View ' . $singular, $post_type ),
				'search_items'          => __( 'Search ' . $plural, $post_type ),
				'not_found'             => __( 'Not found', $post_type ),
				'not_found_in_trash'    => __( 'Not found in Trash', $post_type ),
				'featured_image'        => __( 'Featured Image', $post_type ),
				'set_featured_image'    => __( 'Set featured image', $post_type ),
				'remove_featured_image' => __( 'Remove featured image', $post_type ),
				'use_featured_image'    => __( 'Use as featured image', $post_type ),
				'insert_into_item'      => __( 'Insert into ' . $singular, $post_type ),
				'uploaded_to_this_item' => __( 'Uploaded to this ' . $singular, $post_type ),
				'items_list'            => __( $plural . ' list', $post_type ),
				'items_list_navigation' => __( $plural . ' list navigation', $post_type ),
				'filter_items_list'     => __( 'Filter ' . $plural . ' list', $post_type ),
			);
		}

		public static function args() {
			$args = array(
				'label'                 => __( 'Announcement', 'ucf_announcements' ),
				'description'           => __( 'Announcements', 'ucf_announcements' ),
				'labels'                => self::labels( $singular, $plural, $post_type ),
				'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', ),
				'taxonomies'            => self::taxonomies(),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-microphone',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
			);
			$args = apply_filters( 'ucf_announcements_post_type_args', $args );
			return $args;
		}
		public static function taxonomies() {
			$retval = array(
				'post_tag',
				'category'
			);

			$retval = apply_filters( 'ucf_announcements_taxonomies', $retval );

			foreach( $retval as $taxonomy ) {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					unset( $retval[$taxonomy] );
				}
			}
			return $retval;
		}
	}
}
?>