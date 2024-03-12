<?php
/*
Template Name: Home Page Template
*/
global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $def_currency;
$all_features = array( 'acc', 'tour' );
$enabled_features = array();

$locals = get_location();

foreach( $all_features as $feature ) {
	if ( empty( $trav_options['disable_' . $feature ] ) ) $enabled_features[] = $feature;
}
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		?>
		<div class="home-slider">
			
		
			<?php
			$slider_active = get_post_meta( get_the_ID(), 'trav_page_slider', true );
			$slider        = ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
			if ( class_exists( 'RevSlider' ) && $slider != 'Deactivated' ) {
				echo '<div id="slideshow">';
				putRevSlider( $slider );
				echo '</div>';
			} ?>
			<div class="search-box-wrapper">
				<div class="search-box container">
					<div class="search-tab-content">
						<?php if ( in_array('tour', $enabled_features) ) : ?>
							<div class="tab-pane fade active in" id="tours-tab">
							<form role="search" method="get" id="searchform" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-6 col-md-2">
										<?php $trip_types = get_terms( 'tour_type' ); ?>										
										<?php if ( ! empty( $trip_types ) ) : ?>
											
												<div class="">
													<select name="tour_types" id="tour_types_selector" class="full-width">
														<option value="">Tất cả</option>
														<?php foreach ( $trip_types as $trip_type ) : ?>
															<option  value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											
										<?php endif; ?>
									</div>
									<div class="form-group col-sm-4 col-md-2">
										<select id="place_to_selector" name="place_to" class="full-width">
											<option value="">Điểm đến</option>
											<?php foreach($locals as $local) : ?>
												<option class="place-to-item" data-parent="<?php echo $local->parent; ?>" value="<?php echo $local->term_id; ?>"><?php echo $local->name;?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="form-group col-sm-4 col-md-2">
										<select name="place_from" class="full-width">
											<option value="">Khởi hành từ</option>
											<?php foreach($locals as $local) : ?>
												<option value="<?php echo $local->term_id; ?>"><?php echo $local->name;?></option>
											<?php endforeach ?>
										</select>
										</div>
									<div class="form-group col-sm-4 col-md-2">
										<div class="datepicker-wrap from-today">
											<input name="date_from" type="text" class="input-text full-width" placeholder="Ngày đi" />
										</div>
									</div>
									
									<div class="col-xs-6 col-sm-1 col-md-2">
										<div class="">
											<select name="max_price" class="full-width">
												<option value="">Giá</option>
												<option value="1000000">Dưới 1 triệu</option>
												<option value="3000000">Dưới 3 triệu</option>
												<option value="5000000">Dưới 5 triệu</option>
											</select>
										</div>
									</div>
									
									<div class="form-group col-sm-6 col-md-2 col-xs-6">
										<button type="submit" class="full-width icon-check" data-animation-type="bounce" data-animation-duration="1">Tìm Tour</button>
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
			</div>
		<section id="content"<?php if ( count( $enabled_features ) > 1 ) echo ' class=""' ?>>
			
		
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</section>
<script type="text/javascript">
    jQuery(document).ready(function () {
		jQuery('#tour_types_selector').on('change', function(e) {	
			
			if(this.value){
				jQuery("#place_to_selector option").removeClass("item-hidden");
				//10: ngoài nước   6: trong nước
				//74: viet nam
				if(this.value == 10){
					jQuery( "#place_to_selector option" ).each(function( index , v) {
					  //console.log( index + ": " + $( this ).text() );
					  //console.log(jQuery(this).data("parent"));
					  if(jQuery(this).data("parent") === 74){
						  jQuery(this).addClass("item-hidden");
					  } else {
						  
					  }
					});
					//jQuery("#place_to_selector").find('option').data("parent")
				}
			}
			return false;
			
		});
	});
</script>
	<?php endwhile;
endif;
get_footer();