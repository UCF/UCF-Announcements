<?php
/**
 * Handles registering the audience custom taxonomy.
 **/
if ( ! class_exists( 'UCF_Audience_Custom_Taxonomy' ) ) {
	class UCF_Audience_Custom_Taxonomy
	 {
		public function register_taxonomy() {
			$labels = apply_filters(
				'audienceroles_labels',
				array(
					'singular' => 'Audience Role',
					'plural'   => 'Audience Roles',
					'taxonomy' => 'audienceroles'
				)
			);

			register_taxonomy( 'audienceroles', array( 'announcement' ), self::args( $labels ) );
		}

		public static function labels( $singular, $plural, $taxonomy ) {
			return array(
				'name'                       => _x( $plural, 'Taxonomy General Name', 'ucf_announcements' ),
				'singular_name'              => _x( $singular, 'Taxonomy Singular Name', 'ucf_announcements' ),
				'menu_name'                  => __( $plural, 'ucf_announcements' ),
				'all_items'                  => __( 'All ' . $plural, 'ucf_announcements' ),
				'parent_item'                => __( 'Parent ' . $singular, 'ucf_announcements' ),
				'parent_item_colon'          => __( 'Parent ' . $singular . ':', 'ucf_announcements' ),
				'new_item_name'              => __( 'New ' . $singular . ' Name', 'ucf_announcements' ),
				'add_new_item'               => __( 'Add New ' . $singular, 'ucf_announcements' ),
				'edit_item'                  => __( 'Edit ' . $singular, 'ucf_announcements' ),
				'update_item'                => __( 'Update ' . $singular, 'ucf_announcements' ),
				'view_item'                  => __( 'View ' . $singular, 'ucf_announcements' ),
				'separate_items_with_commas' => __( 'Separate ' . $plural . ' with commas', 'ucf_announcements' ),
				'add_or_remove_items'        => __( 'Add or remove ' . $plural, 'ucf_announcements' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'ucf_announcements' ),
				'popular_items'              => __( 'Popular ' . $plural, 'ucf_announcements' ),
				'search_items'               => __( 'Search ' . $plural, 'ucf_announcements' ),
				'not_found'                  => __( 'Not Found', 'ucf_announcements' ),
				'no_terms'                   => __( 'No ' . $plural, 'ucf_announcements' ),
				'items_list'                 => __( $plural . ' list', 'ucf_announcements' ),
				'items_list_navigation'      => __( $plural . ' list navigation', 'ucf_announcements' ),
			);
		}

		public static function args( $labels ) {
			$singular = $labels['singular'];
			$plural = $labels['plural'];
			$taxonomy = $labels['taxonomy'];

			return array(
				'labels'            => self::labels( $singular, $plural, $taxonomy ),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => false
			);
		}
	}
}
