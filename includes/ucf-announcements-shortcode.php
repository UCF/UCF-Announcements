<?php
/**
 * Handles the announcement shortcode
 **/
if ( ! class_exists( 'UCF_Announcements_Shortcode' ) ) {
	class UCF_Announcements_Shortcode {
		/**
		 * Registers the `announcements` shortcode
		* @author Jim Barnes
		* @since 1.0.0
		**/
		public static function register_shortcode() {
			add_shortcode( 'announcements', array( 'UCF_Announcements_Shortcode', 'callback' ) );
		}

		/**
		 * Returns the markup for the announcement filter form
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return string | The announcement filter form
		 **/
		private static function get_form_markup( $args ) {
			$audiences = get_terms( array(
				'taxonomy'   => 'audienceroles',
				'hide_empty' => true
			) );

			$post_an_announcement = get_page_by_title( 'Post An Announcement' );
			$post_an_announcement = get_permalink( $post_an_announcement->ID );

			ob_start();
		?>
				<div class="row mb-4 align-items-center">
					<div class="col-md-8">
						<label for="roles" class="mr-2 text-muted">Filter by: </label>
						<?php echo self::get_roles_markup(); ?>
					</div>
					<div class="col-md-4">
						<a href="<?php echo $post_an_announcement; ?>" class="btn btn-success pull-right">Post An Announcement</a>
					</div>
				</div>
		<?php
			return ob_get_clean();
		}

		/**
		 * Returns the markup for an announcement
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $post WP_Post | The WP_Post object
		 * @return string | The announcement markup.
		 **/
		private static function get_announcement_markup( $post ) {
			ob_start();
		?>
			<div class="card">
				<h2 class="h4 card-header"><?php echo $post->post_title; ?></h2>
				<div class="card-block">
					<p class="card-text"><?php echo wp_trim_words( $post->post_content, 20 ); ?></p>
					<p class="card-text"><?php echo self::get_roles_markup( $post ); ?></p>
				</div>
			</div>
		<?php
			return ob_get_clean();
		}

		/**
		 * Returns the markup for roles
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $roles Array | The array of roles
		 * @return string | The roles markup
		 **/
		private static function get_roles_markup( $post=null ) {
			if ( $post ) {
				$roles = wp_get_post_terms( $post->ID, 'audienceroles' );
			} else {
				$roles = get_terms( array( 'taxonomy' => 'audienceroles' ) );
			}

			ob_start();
		?>
			<ul class="list-unstyled list-inline d-inline-block mb-0">
			<?php foreach( $roles as $role ) : ?>
				<li class="list-inline-item">
					<a href="<?php echo '?role=' . $role->slug; ?>" class="badge badge-<?php echo self::get_role_badge_class( $role->slug ); ?>">
						<?php echo $role->name; ?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php
			return ob_get_clean();
		}

		private static function get_role_badge_class( $role ) {
			switch( $role ) {
				case 'alumni':
					return 'primary';
				case 'faculty':
					return 'secondary';
				case 'staff':
					return 'complementary';
				case 'students':
					return 'success';
				case 'public':
					return 'default';
			}
		}

		/**
		 * The callback for the shortcode.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $atts Array | The array of shortcode attributes
		 * @return string | The markup
		 **/
		public static function callback( $atts ) {		
			$args = $_GET;
			$announcements = UCF_Announcements::get_announcements( $args );

			ob_start();
		?>
			<div class="container">
				<?php echo self::get_form_markup( $args ); ?>
				<?php if ( $announcements ) : ?>
				<div class="card-deck">
				<?php 
					foreach( $announcements as $announcement ) {
						echo self::get_announcement_markup( $announcement );
					}
				?>
				</div>
				<?php endif; ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
