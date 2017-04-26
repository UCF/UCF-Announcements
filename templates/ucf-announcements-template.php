<?php
if ( $_POST ) {
	if ( isset( $_POST['submit-announcement'] ) ) {
		$success = UCF_Announcements_Common::submit_announcement( $_POST );

		if ( $success !== true ) {
			get_header(); the_post();
			echo UCF_Announcements_Forms::create_form( $success );
			get_footer();
			return;
		}
	}

	$username = $_POST['username'];
	$password = $_POST['password'];

	$auth = UCF_Announcements_Auth::authenticate( $username, $password );

	get_header(); the_post();

	if ( $auth ) {
		echo UCF_Announcements_Forms::create_form();
	} else {
		echo UCF_Announcements_Forms::login_form( true );
	}

	get_footer();

} else {
	$valid = UCF_Announcements_Auth::handle_session();

	get_header(); the_post();

	if ( UCF_Announcements_Auth::is_authenticated() && $valid ) {
		echo UCF_Announcements_Forms::create_form();
	} else {
		echo UCF_Announcements_Forms::login_form();
	}

	get_footer();
}
