<?php
/**
 * Handles pulling announcements
 **/
if ( ! class_exists( 'UCF_Announcements' ) ) {
	class UCF_Announcements {
		public static function get_announcements( $args ) {
			$args = wp_parse_args(
				$args,
				array(
					'limit'    => -1,
					'keywords' => null,
					'role'     => 'all',
					'time'     => 'thisweek'
				)
			);

			var_dump( $args );

			// Time variables
			$this_monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
			$first_day_this_month = date( 'Y-m-d', strtotime( 'first day of this month' ) );

			$query_args = array(
				'numberposts' => $args['limit'],
				'post_type'   => 'announcement',
				'orderby'     => 'meta_value',
				'order'       => 'ASC',
				'meta_key'    => 'announcement_start_date'
			);

			if ( $role_args = self::get_role_arguments( $args['role'] ) ) {
				$query_args = array_merge( $query_args, $role_args );
			}

			if ( $keyword_args = self::get_keyword_arguments( $args['keywords'] ) ) {
				if ( isset( $query_args['tax_query'] ) ) {
					$query_args['tax_query'][] = $keyword_args['tax_query'][0];
					$query_args['s'] = $keyword_args['s'];
				} else {
					$query_args = array_merge( $query_args, $keyword_args );
				}
			}

			$time_args = self::get_time_arguments( $args['time'] );

			$query_args = array_merge( $query_args, $time_args );

			$retval = get_posts( $query_args );

			return $retval;
		}

		/**
		 * Returns WP_Query arguments for the requested role(s).
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $role string|Array | The role or roles
		 * @return Array|null | Array of args for WP_Query or null if role is all
		 **/
		private static function get_role_arguments( $role ) {
			if ( $role === 'all' ) {
				return null;
			}

			return array(
				'tax_query' => array(
					array(
						'taxonomy' => 'audienceroles',
						'field'    => 'slug',
						'terms'    => $role
					)
				)
			);
		}

		private static function get_keyword_arguments( $keywords ) {
			if ( ! $keywords ) {
				return null;
			}

			return array(
				'tax_query' => array(
					array(
						'taxonomy' => 'keywords',
						'field'    => 'name',
						'terms'    => $keywords
					)
				),
				's' => $keywords
			);
		}

		/**
		 * Returns WP_Query arguments for the requested timeframe.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $time string | The requested time frame.
		 * @return array | Array of args for WP_Query
		 **/
		private static function get_time_arguments( $time ) {
			$today = date( 'Y-m-d' );

			switch( $time ) {
				case 'nextweek':
					$next_monday = date( 'Y-m-d', strtotime( 'monday next week' ) );
					$next_sunday = date( 'Y-m-d', strtotime( $next_monday . ' + 6 days' ) );
					
					return array(
						'meta_query' => array(
							array(
								'key'     => 'announcement_start_date',
								'value'   => $next_sunday,
								'compare' => '<=',
								'type'    => 'DATE'
							),
							array(
								'key'     => 'announcement_end_date',
								'value'   => $next_monday,
								'compare' => '>=',
								'type'    => 'DATE'
							)
						)
					);

					break;
				case 'thismonth':
					$last_day_this_month = date( 'Y-m-d', strtotime( 'last day of this month' ) );

					return array(
						'meta_query' => array(
							array(
								'key'     => 'announcement_start_date',
								'value'   => $last_day_this_month,
								'compare' => '<=',
								'type'    => 'DATE'
							),
							array(
								'key'     => 'announcement_end_date',
								'value'   => $today,
								'compare' => '>=',
								'type'    => 'DATE'
							)
						)
					);

					break;
				case 'nextmonth':
					$first_day_next_month = date( 'Y-m-d', strtotime( 'first day of next month' ) );
					$last_day_next_month = date( 'Y-m-d', strtotime( 'last day of next month' ) );

					return array(
						'meta_query' => array(
							array(
								'key'     => 'announcement_start_date',
								'value'   => $last_day_next_month,
								'compare' => '<=',
								'type'    => 'DATE'
							),
							array(
								'key'     => 'announcement_end_date',
								'value'   => $first_day_next_month,
								'compare' => '>=',
								'type'    => 'DATE'
							)
						)
					);

					break;
				case 'thisweek':
				default:
					$this_sunday = date( 'Y-m-d', strtotime( $thismonday . ' + 6 days' ) );

					return array(
						'meta_query' => array(
							array(
								'key'     => 'announcement_start_date',
								'value'   => $this_sunday,
								'compare' => '<='
							),
							array(
								'key'     => 'announcement_end_date',
								'value'   => $today,
								'compare' => '>='
							)
						)
					);

					break;
			}
		}
	}
}
