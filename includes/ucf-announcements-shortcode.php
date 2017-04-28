<?php
/**
 * Handles the announcement shortcode
 **/
if ( ! class_exists( 'UCF_Announcements_Shortcode' ) ) {
	class UCF_Announcements_Shortcode {
		public static function register_shortcode() {
			add_shortcode( 'announcements', array( 'UCF_Announcements_Shortcode', 'callback' ) );
		}

		public static function callback( $atts, $content='' ) {
			$args = $_GET;

			$announcements = UCF_Announcements::get_announcements( $args );
			$audiences = get_terms( array(
				'taxonomy'   => 'audienceroles',
				'hide_empty' => true
			) );

			$timeframes = UCF_Announcements_Config::get_timeframes();
		
			ob_start();
		?>
			<div class="container">
				<form class="form" id="filter_form" action>
					<div class="row align-bottom">
						<div class="col-md-1">
							<p>Filter By: </p>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="role">Audience: </label>
								<select id="role" name="role" class="form-control">
									<option value="all">All</option>
								<?php foreach( $audiences as $audience ) : ?>
									<option value="<?php echo $audience->slug; ?>"<?php echo ( $args['role'] === $audience->slug ) ? ' selected' : ''; ?>><?php echo $audience->name; ?></option>
								<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="keyword">Keyword: </label>
								<input type="text" id="keyword" name="keyword" class="form-control" <?php echo $args['keyword'] ? 'value ="' . $args['keyword'] . '"' : ''; ?>>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="time">Timeframe: </label>
								<select id="time" name="time" class="form-control">
								<?php foreach( $timeframes as $value => $text ) : ?>
									<option value="<?php echo $value; ?>"<?php echo ( $args['time'] === $value ) ? ' selected' : ''; ?>><?php echo $text; ?></option>
								<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
				<?php if ( $announcements ) : ?>
				<div class="card-deck">
				<?php foreach( $announcements as $announcement ) : ?>
					<div class="card">
						<h2 class="h4 card-header"><?php echo $announcement->post_title; ?></h2>
						<div class="card-block">
							<h4 class="card-subtitle mb-2 text-muted">
								<?php echo $announcement->meta['announcement_start_date']->format( 'M j' ); ?> &mdash; <?php echo $announcement->meta['announcement_end_date']->format( 'M j' ); ?>
							</h4>
							<p class="card-text"><?php echo $announcement->post_content; ?></p>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		<?php
			return ob_get_clean();
		}
	}
}
