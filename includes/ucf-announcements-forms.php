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

		public static function create_form( $errors=array() ) {
			$page_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			ob_start();
		?>
			<div id="announcement-create" class="container">
				<form method="post" id="announcement-form" action="<?php echo $page_url; ?>">
					<fieldset>
						<div class="form-group">
							<label for="post_title">Announcement Title <span class="text-danger">*</span></label>
							<?php if ( isset( $errors['post_title'] ) ) : ?>
							<p class="text-danger"><?php echo $errors['post_title']; ?>
							<?php endif; ?>
							<input type="text" id="post_title" name="post_title" class="form-control">
						</div>
						<div class="form-group">
							<label for="post_content">Description <span class="text-danger">*</span></label>
							<?php if ( isset( $errors['post_content'] ) ) : ?>
							<p class="text-danger"><?php echo $errors['post_content']; ?>
							<?php endif; ?>
							<textarea rows="10" id="post_content" name="post_content" class="form-control"></textarea>
						</div>
						<div class="form-group">

						</div>
						<div class="form-group">
							<label for="keywords">Keywords</label>
							<?php if ( isset( $errors['keywords'] ) ) : ?>
							<p class="text-danger"><?php echo $errors['keywords']; ?>
							<?php endif; ?>
							<input type="text" id="keywords" name="keywords" class="form-control">
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<label for="start_date">Start Date <span class="text-danger">*</span></label>
								<?php if ( isset( $errors['start_date'] ) ) : ?>
							<p class="text-danger"><?php echo $errors['start_date']; ?>
							<?php endif; ?>
								<input type="date" id="start_date" name="start_date" class="form-control">
							</div>
							<div class="col-sm-6">
								<label for="end_date">End Date <span class="text-danger">*</span></label>
								<?php if ( isset( $errors['end_date'] ) ) : ?>
							<p class="text-danger"><?php echo $errors['end_date']; ?>
							<?php endif; ?>
								<input type="date" id="end_date" name="end_date" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<label for="url">URL</label>
								<input type="text" id="url" name="url" class="form-control">
							</div>
							<div class="col-sm-6">
								<label for="contact_person">Contact Person</label>
								<input type="text" id="contact_person" name="contact_person" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6">
								<label for="phone">Phone</label>
								<input type="text" id="phone" name="phone" class="form-control">
							</div>
							<div class="col-sm-6">
								<label for="email">Email</label>
								<input type="text" id="email" name="email" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="posted_by">Posted By</label>
							<input type="text" id="posted_by" name="posted_by" class="form-control">
						</div>
					</fieldset>
					<input type="submit" class="btn btn-primary" id="submit-announcement" name="submit-announcement" value="Submit">
				</form>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
