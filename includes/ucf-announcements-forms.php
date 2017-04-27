<?php
/**
 * Handles outputting forms
 **/
if ( ! class_exists( 'UCF_Announcements_Forms' ) ) {
	class UCF_Announcements_Forms {

		/**
		 * Outputs the login form
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param $error bool | Indicates if there was a login error
		 * @return string | The form html output
		 **/
		public static function login_form( $error=false ) {
			$page_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			ob_start()
		?>
			<div id="announcements-login" class="container">
				<h2>Login</h2>
				<p>To post a new announcement, please log in using your NID and NID password below.</p>
				<form method="post" id="auth-form" action="<?php echo $page_url; ?>">
					<?php if ( $error ) : ?>
					<div class="alert alert-danger" id="login-error">
						<strong>Error: </strong>
						<p>You've entered and invalid username or password.</p>
						<p>To verify your NID, go to <a href="https://my.ucf.edu/">myUCF</a> and select "What are my PID and NID?"<br/>
						To reset your password, go to the <a href="https://mynid.ucf.edu/">Change Your NID Password</a> page.<br/>
						For further help, contact the Service Desk at 407-823-5117, Monday-Friday 8am-5pm.</p>
					</div>
					<?php endif; ?>
					<fieldset>
						<div class="form-group">
							<label for="username">NID (Network ID): </label>
							<input type="text" name="username" id="username" class="form-control">
						</div>
						<div class="form-group">
							<label for="username">Password: </label>
							<input type="password" name="password" id="password" class="form-control">
						</div>
						<input type="submit" name="submit-auth" id="submit-auth" class="btn btn-primary" value="Submit">
					</fieldset>
				</form>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
