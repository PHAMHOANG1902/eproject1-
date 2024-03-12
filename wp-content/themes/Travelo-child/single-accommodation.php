<?php
/*
 * Single Accommodation Page Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $search_max_rooms, $search_max_adults, $search_max_kids;

get_header();

if (have_posts()) {
	while (have_posts()) : the_post();

		//init variables
		$acc_id = get_the_ID();
		$acc_meta = get_post_meta($acc_id);
		$acc_meta['review'] = get_post_meta(trav_acc_org_id($acc_id), 'review', true);
		$acc_meta['review_detail'] = get_post_meta(trav_acc_org_id($acc_id), 'review_detail', true);
		// $tm_data = get_post_meta( $acc_id, 'trav_accommodation_tm_testimonial', true );
		$accommodation_type = wp_get_post_terms($acc_id, 'accommodation_type');
		$args = array(
			'post_type' => 'room_type',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'trav_room_accommodation',
					// 'value' => array( $acc_id ),
					'value' => array(trav_acc_org_id($acc_id))
				)
			),
			'suppress_filters' => 1,
			'post_status' => 'publish',
		);
		$room_types = get_posts($args);
		$city = trav_acc_get_city($acc_id);
		$country = trav_acc_get_country($acc_id);
		$facilities = wp_get_post_terms($acc_id, 'amenity');
		$things_to_do = empty($acc_meta['trav_accommodation_ttd']) ? '' : $acc_meta['trav_accommodation_ttd'];

		// init map & gallery & calendar variables
		$gallery_imgs = array_key_exists('trav_gallery_imgs', $acc_meta) ? $acc_meta['trav_gallery_imgs'] : array();
		$map = empty($acc_meta['trav_accommodation_loc']) ? '' : $acc_meta['trav_accommodation_loc'][0];
		$calendar_desc = empty($acc_meta['trav_accommodation_calendar_txt']) ? '' : $acc_meta['trav_accommodation_calendar_txt'][0];
		$show_gallery = 0;
		$show_map = 0;
		$show_street_view = 0;
		$show_calendar = 0;
		if (array_key_exists('trav_accommodation_main_top', $acc_meta)) {
			$main_top_meta = $acc_meta['trav_accommodation_main_top'];
			$show_gallery = in_array('gallery', $main_top_meta) ? 1 : 0;
			$show_map = in_array('map', $main_top_meta) ? 1 : 0;
			$show_street_view = in_array('street', $main_top_meta) ? 1 : 0;
			$show_calendar = in_array('calendar', $main_top_meta) ? 1 : 0;
		}

		// init booking search variables
		$rooms = (isset($_GET['rooms']) && is_numeric($_GET['rooms'])) ? sanitize_text_field($_GET['rooms']) : 1;
		$adults = (isset($_GET['adults']) && is_numeric($_GET['adults'])) ? sanitize_text_field($_GET['adults']) : 1;
		$kids = (isset($_GET['kids']) && is_numeric($_GET['kids'])) ? sanitize_text_field($_GET['kids']) : 0;
		$child_ages = isset($_GET['child_ages']) ? $_GET['child_ages'] : '';
		$date_from = (isset($_GET['date_from'])) ? trav_tophptime($_GET['date_from']) : '';
		$date_to = (isset($_GET['date_to'])) ? trav_tophptime($_GET['date_to']) : '';
		$except_booking_no = (isset($_GET['edit_booking_no'])) ? sanitize_text_field($_GET['edit_booking_no']) : 0;
		$pin_code = (isset($_GET['pin_code'])) ? sanitize_text_field($_GET['pin_code']) : 0;

		// add to user recent activity
		trav_update_user_recent_activity($acc_id); ?>


		<section id="content">
			<div class="container tour-detail-page">

				<div class="tour-intro-section">
					<div class="row">
						<div class="col-md-7">
							<?php if (!empty($gallery_imgs) && $show_gallery) { ?>
								<div id="photos-tab" class="tab-pane fade">
									<div class="photo-gallery flexslider style1" data-animation="slide" data-sync="#photos-tab .image-carousel">
										<ul class="slides">
											<?php foreach ($gallery_imgs as $gallery_img) {
												echo '<li>' . wp_get_attachment_image($gallery_img, 'full') . '</li>';
											} ?>
										</ul>
									</div>
									<div class="image-carousel style1" data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#photos-tab .photo-gallery">
										<ul class="slides">
											<?php foreach ($gallery_imgs as $gallery_img) {
												echo '<li>' . wp_get_attachment_image($gallery_img, 'widget-thumb') . '</li>';
											} ?>
										</ul>
									</div>
								</div>
							<?php } ?>

						</div>
						<div class="col-md-5 tour-info-right">
							<div class="tour-info-right-content">

								<h1 class="tour-title"><?php the_title(); ?></h1>
								<div class="info-frame">
									<div class="tour-price"><span class="pre-price">Chỉ từ </span><span itemprop="price"><?php echo empty($acc_meta["trav_accommodation_minimum_stay"]) ? '' : $acc_meta["trav_accommodation_minimum_stay"][0] ?> đ/ mỗi đêm</span></div>
									<a data-toggle="modal" data-target="#bookingModal" class="btn-book-now full-width btn  btn-lg" href="">Đặt ngay</a>
								</div>
								<div class="info-frame">
									<div class="row">
										<div class="col-md-3 col-xs-3">Địa chỉ</div>
										<div class="col-md-9 col-xs-9">
											<?php echo esc_html(empty($acc_meta["trav_accommodation_address"]) ? '' : $acc_meta["trav_accommodation_address"][0]); ?>
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="hotel-detail-section">
					<div class="row">
						<div class="col-md-7">
							<div class="travelo-box">

								<div class="tab-container">
									<ul class="tabs">
										<li class="active"><a href="#hotel-description-detail" data-toggle="tab">Chi tiết</a></li>
										<li><a href="#hotel-note" data-toggle="tab">Lưu ý</a></li>
										<li><a href="#map-tab" data-toggle="tab">Bản đồ</a></li>
									</ul>
									<div class="tab-content">
										<div id="hotel-description-detail" class="tab-pane fade in active">
											<div class="long-description">
												<div class="box entry-content">
													<?php the_content(); ?>
													<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
												</div>
											</div>
										</div>
										<div id="hotel-note" class="tab-pane fade">
											<div class="box policies-box">
												<?php
												$tr = '<div class="row"><div class="col-xs-2"><label>%s:</label></div><div class="col-xs-10">%s</div></div>';

												$detail_fields = array(
													'check_in' => array('label' => __('Check-in', 'trav'), 'pre' => '', 'sur' => ''),
													'check_out' => array('label' => __('Check-out', 'trav'), 'pre' => '', 'sur' => ''),
													'cancellation' => array('label' => __('Hủy phòng / Tiền cọc', 'trav'), 'pre' => '', 'sur' => ''),
													'extra_beds_detail' => array('label' => __('Children and Extra Beds', 'trav'), 'pre' => '', 'sur' => ''),
													'cards' => array('label' => __('Chấp nhận thanh toán thẻ', 'trav'), 'pre' => '', 'sur' => ''),
													'pets' => array('label' => __('Mang chó mèo', 'trav'), 'pre' => '', 'sur' => ''),
													'other_policies' => array('label' => __('Other Policies', 'trav'), 'pre' => '', 'sur' => ''),
												);

												foreach ($detail_fields as $field => $value) {
													$$field = empty($acc_meta["trav_accommodation_$field"]) ? '' : $acc_meta["trav_accommodation_$field"][0];
													if (!empty($$field)) {
														$content = $value['pre'] . $$field . $value['sur'];
														echo sprintf($tr, esc_html($value['label']), esc_html($content));
													}
												}
												?>
											</div>
										</div>
										<div id="map-tab" class="tab-pane fade"></div>


									</div>

								</div>


							</div>

						</div>
						<div class="col-md-5">

							<div class="amenity-widget widget">
								<h3 class="widget-title">DỊCH VỤ ĐI KÈM</h3>
								<div class="widget-content">
									<ul class="amenities clearfix style1">
										<?php
										$amenity_icons = get_option("amenity_icon");
										$amenity_html = '';
										foreach ($facilities as $facility) {
											if (is_array($amenity_icons) && isset($amenity_icons[$facility->term_id])) {
												$amenity_html .= '<li class="col-md-6 col-sm-6">';
												if (isset($amenity_icons[$facility->term_id]['uci'])) {
													$amenity_html .= '<div class="icon-box style1"><div class="custom_amenity"><img title="' . esc_attr($facility->name) . '" src="' . esc_url($amenity_icons[$facility->term_id]['url']) . '" height="42" alt="amenity-image"></div>' . esc_html($facility->name) . '</div>';
												} else if (isset($amenity_icons[$facility->term_id]['icon'])) {
													$_class = $amenity_icons[$facility->term_id]['icon'];
													$amenity_html .= '<div class="icon-box style1"><i class="' . esc_attr($_class) . '" title="' . esc_attr($facility->name) . '"></i>' . esc_html($facility->name) . '</div>';
												}
												$amenity_html .= '</li>';
											}
										}
										echo wp_kses_post($amenity_html);
										?>
									</ul>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div id="bookingModal" tabindex="-1" role="dialog" class="modal fade" style="padding:20px">
			<div class="modal-dialog" role="document">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title head_popup">ĐẶT PHÒNG KHÁCH SẠN<br />
							<button style="color:#000" type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="wrap_pop">
							<form id="booking-form-popup" method="post">
								<div>
									<div class="popup-feature-image">

										<div class="clear"></div>
									</div>
									<div class="popup-meta">
										<h3 id="Holder_popup_name" class="pop_name"><?php the_title(); ?></h3>
										<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
									</div>
									<div class="clear clearfix"></div>
								</div>
								<div class="clear clearfix"></div>
								<div class="popup-billing-form-fields">

									<div class="form-group">
										<input type="text" name="full_name" class="form-control" placeholder="Họ tên *" required />
									</div>
									<div class="form-group">
										<input type="text" name="phone" class="form-control" placeholder="Số điện thoại *" required />
									</div>
									<div class="form-group">
										<input type="email" name="email" class="form-control" placeholder="Email *" required />
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="Ngày đến" required />

												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="Ngày đi" required />

												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="">
											<select name="adults" class="full-width">
												<option>Số người</option>
												<?php
												for ($i = 1; $i <= 15; $i++) {
													echo '<option value="' . $i . '">' . $i . ' người</option>';
												}
												?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>


									<div class="popup_p">
										<button type="submit" class="vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-color-primary">
											<span class="sp1">Hoàn tất đặt phòng</span>
											<span class="sp2">F4 Travel sẽ gọi và xác nhận lại</span>
										</button>
									</div>
								</div>

								<div class="clear clearfix"></div>
							</form>
							<div class="clear clearfix"></div>
							<br />
							<div id="billing-pop-up-status"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#booking-form-popup").submit(function() {
					jQuery('#billing-pop-up-status').html('<div class="alert alert-info">Hệ thống đang xử lý...</div>');

					var datastring = jQuery("#booking-form-popup").serialize();
					jQuery('#submit-button').val('Đang gửi...');
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo admin_url('admin-ajax.php '); ?>',
						data: {
							'action': 'create_new_hotel_booking',
							'data': datastring
						},
						success: function(data) {
							console.log(data);
							jQuery('#billing-pop-up-status').html('<div class="alert alert-success"><strong>Đặt phòng thành công!</strong> Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!</div>');

							setTimeout(function() {
								window.location.href = "<?php echo get_option('home'); ?>";
							}, 3000);
							return false;
						},
						error: function(errorThrown) {
							jQuery('#billing-pop-up-status').html('<div class="alert alert-danger"><strong>Lỗi hệ thống!</strong>Vui lòng thử lại sau, cảm ơn!</div>');
							console.log(errorThrown);
						}
					});
					return false;
				});
			});
		</script>
<?php endwhile;
}
get_footer();
