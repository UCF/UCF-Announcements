<?php
/**
 * Handles authenticating users
 **/
if ( ! class_exists( 'UCF_Announcements_Auth' ) ) {
	class UCF_Announcements_Auth {
		/**
		 * Handles authenticating the user
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $username string | The User's username
		 * @param $password string | The User's password
		 **/
		public static function authenticate( $username, $password ) {
			$ldapbind = false;
			$ldap_host = UCF_Announcements_Config::get_option_or_default( 'ldap_path' );

			$ldap = ldap_connect( $ldap_host );

			if ( $ldap ) {
				try {
					$bind = ldap_bind( $ldap, $username . '@' . $ldap_host, $password );
				} 
				catch (Exception $e) {
					UCF_Announcements_Log::write( 'Could not connect to ' . $ldap_host );	
				}
			} else {
				UCF_Announcements_Log::write( 'Could not connect to ' . $ldap_host );
			}

			return $bind;
		}

		/**
		 * Determines if the user is logged in.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return bool | True if the user is logged in.
		 **/
		public static function is_authenticated() {
			if ( isset( $_SESSION['user'] ) ) {
				return true;
			}

			return false;
		}

		public static function handle_session() {
			if ( isset( $_SESSION['timeout'] ) && $_SESSION['timeout'] < time() ) {
				self::destroy_session();
			}

			if ( isset( $_SESSION['user'] ) && isset( $_SESSION['ip'] ) && $_SESSION['ip'] === $_SERVER['REMOTE_ADDR'] ) {
				self::create_session( $_SESSION['user'] );
				return true;
			}

			return false;
		}

		/**
		 * Creates session data for the user.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $user string | The username
		 **/
		public static function create_session() {
			$timeout = 15 * 60;
			$_SESSION['timeout'] = time() + $timeout;
			$_SESSION['user'] = $user;
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		}

		/**
		 * Destroys the user's session.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function destroy_session() {
			$_SESSION = array();
			session_destroy();
		}
	}
}
