<?php
$form_id = UCF_Announcements_Config::get_option_or_default( 'form' );

if ( $_POST ) {
	if ( isset( $_POST['submit-announcement'] ) ) {
		$success = UCF_Announcements_Common::submit_announcement( $_POST );

		if ( $success !== true ) {
			get_header(); the_post();
			echo '<div class="container">';
			echo RGForms::parse_shortcode( array( 'id' => $form_id ) );
			echo '</div>';
			get_footer();
			return;
		}
	}

	$username = $_POST['username'];
	$password = $_POST['password'];

	$auth = UCF_Announcements_Auth::authenticate( $username, $password );

	get_header(); the_post();

	if ( $auth ) {
		echo '<div class="container">';
		echo RGForms::parse_shortcode( array( 'id' => $form_id ) );
		echo '</div>';
	} else {
		echo UCF_Announcements_Forms::login_form( true );
	}

	get_footer();

} else {
	$valid = UCF_Announcements_Auth::handle_session();

	get_header(); the_post();

	if ( UCF_Announcements_Auth::is_authenticated() && $valid ) {
		echo '<div class="container">';
		echo RGForms::parse_shortcode( array( 'id' => $form_id ) );
		echo '</div>';
	} else {
		echo UCF_Announcements_Forms::login_form();
	}

	get_footer();
}
