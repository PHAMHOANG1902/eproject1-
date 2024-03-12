<?php
/*
Template Name: Home Page Template
*/
global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $def_currency;
$all_features = array( 'acc', 'tour' );
$enabled_features = array();
foreach( $all_features as $feature ) {
	if ( empty( $trav_options['disable_' . $feature ] ) ) $enabled_features[] = $feature;
}
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		?>
		<section id="content"<?php if ( count( $enabled_features ) > 1 ) echo ' class="no-padding"' ?>>
			<div class="search-box-wrapper">
				<div class="search-box container">
					<div class="search-tab-content">
						<?php if ( in_array('tour', $enabled_features) ) : ?>
							<div class="tab-pane fade active in" id="tours-tab">
							<form role="search" method="get" id="searchform" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<h4 class="title">Khởi hành từ</h4>
										<input type="text" name="s" class="input-text full-width" placeholder="Khởi hành từ" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<h4 class="title">Ngày khởi hành</h4>
										<div class="row">
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3 fixheight">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value="">Loại hình Tour</option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<div class="selector">
													<select name="max_price" class="full-width">
														<option value="">Giá</option>
														<option value="1000000">Dưới 1 triệu</option>
														<option value="3000000">Dưới 3 triệu</option>
														<option value="5000000">Dưới 5 triệu</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1">Tìm Tour</button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		
		<?php
		$slider_active = get_post_meta( get_the_ID(), 'trav_page_slider', true );
		$slider        = ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
		if ( class_exists( 'RevSlider' ) && $slider != 'Deactivated' ) {
			echo '<div id="slideshow">';
			putRevSlider( $slider );
			echo '</div>';
		} ?>
		
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endwhile;
endif;
get_footer();