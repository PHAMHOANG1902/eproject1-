<?php
/*
 * Single Tour Page Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header();

if (have_posts()) {
	while (have_posts()) : the_post();

		$tour_info = get_tour_nearest();

		if (isset($_GET['date_from']) && $_GET['date_from']) {
			$tour_date = str_replace('/', '-', $_GET['date_from']);
			$tour_info = get_tour_by_date(strtotime($tour_date));
		}

		//print_r($tour_info);

		//init variables
		$tour_id = get_the_ID();
		//$city = trav_tour_get_city( $tour_id );
		//$country = trav_tour_get_country( $tour_id );

		//$date_from = ( isset( $_GET['date_from'] ) ) ? trav_tophptime( $_GET['date_from'] ) : date( trav_get_date_format('php') );
		//$date_to = ( isset( $_GET['date_to'] ) ) ? trav_tophptime( $_GET['date_to'] ) : date( trav_get_date_format('php'), //trav_strtotime( $date_from ) + 86400 * 30 );
		//$repeated = get_post_meta( $tour_id, 'trav_tour_repeated', true );
		//$multi_book = get_post_meta( $tour_id, 'trav_tour_multi_book', true );
		//$isv_setting = get_post_meta( $tour_id, 'trav_post_media_type', true );
		$discount = get_post_meta($tour_id, 'trav_tour_hot', true);
		$discount_rate = get_post_meta($tour_id, 'trav_tour_discount_rate', true);
		//$sc_list_pos = get_post_meta( $tour_id, 'trav_tour_sl_first', true );

		//$schedule_types = trav_tour_get_schedule_types( $tour_id );

		// add to user recent activity
		trav_update_user_recent_activity($tour_id); ?>

		<section id="content">
			<div class="container tour-detail-page">
				<?php
				if ($post->post_type == 'tour') {
					$tour_type_obj = get_field_object('tour_type', $post->ID);
					$tour_type = '';
					if (isset($tour_type_obj['value']->name)) {
						$tour_type = $tour_type_obj['value']->name;
					}
				?>
					<ul class="tour-breadcrumb">
						<li><a href="<?php echo get_site_url(); ?>">Home</a></li>
						<li><a href="#"><?php echo $tour_type; ?></a></li>
						<li><a href="#"><?php echo get_the_title(); ?></a></li>
					</ul>
				<?php
				}
				?>
				<div class="tour-intro-section">
					<div class="row">
						<div class="col-md-7">
							<div class="image-box">
								<?php if (!empty($discount) && !empty($discount_rate)) : ?>
									<span class="discount"><span class="discount-text"><?php echo esc_html($discount_rate) ?>% Discount</span></span>
								<?php endif; ?>
								<?php trav_post_gallery($tour_id) ?>
							</div>
						</div>
						<div class="col-md-5 tour-info-right">
							<div class="tour-info-right-content">

								<h1 class="tour-title"><?php the_title(); ?></h1>
								<div class="info-frame">
									<div class="tour-price">

										<?php if (!empty($discount) && !empty($discount_rate)) : ?>
											<span itemprop="price">
												<?php
												if (isset($tour_info['tour_price_per_person'])) {
													echo $tour_info['tour_price_per_person'] * (1 - ($discount_rate / 100)) . ' đ';
												}
												?>
											</span>
											<p class="price-discount">
												<?php
												if (isset($tour_info['tour_price_per_person'])) {
													echo ho_format_money($tour_info['tour_price_per_person']);
												}
												?>
											</p>
										<?php else : ?>

											<span itemprop="price">
												<?php
												if (isset($tour_info['tour_price_per_person'])) {
													echo $tour_info['tour_price_per_person'] . ' đ';
												}
												?>
											</span>

										<?php endif; ?>
									</div>
									<a data-toggle="modal" data-target="#bookingModal" class="btn-book-now full-width btn  btn-lg" href="">Đặt ngay</a>
								</div>
								<div class="info-frame">
									<div class="row">
										<div class="col-md-4">Mã tour</div>
										<div class="col-md-8">
											<?php
											if (isset($tour_info['tour_sku'])) {
												echo $tour_info['tour_sku'];
											}
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">Khởi hành</div>
										<div class="col-md-8">
											<div class="row">
												<div class="col-md-6">
													<?php
													if (isset($tour_info['tour_date_start'])) {
														echo $tour_info['tour_date_start'];
													}
													?>
												</div>
												<div class="col-md-6"><i class="fa fa-calendar"></i>&nbsp;&nbsp;
													<a style="color: #f99300" href="<?php echo get_permalink(2009); ?>?tour_id=<?php echo get_the_ID(); ?>">Ngày khác</a>
												</div>
											</div>



										</div>

									</div>
									<div class="row">

										<div class="col-md-4">Nơi khởi hành</div>
										<div class="col-md-8">
											<?php
											$place_from_obj = get_field_object('tour_place_from');
											if (isset($place_from_obj['value'])) {
												echo $place_from_obj['value']->name;
												//print_r($place_from_obj);
											}
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">Thời gian</div>
										<div class="col-md-8"><?php the_field('tour_duration'); ?></div>
									</div>
									<div class="row">
										<div class="col-md-4">Số chỗ còn nhận</div>
										<div class="col-md-8"><?php the_field('tour_limit_person'); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tour-detail-section">
					<div class="row">
						<div id="main" class="col-sm-8 col-md-8">
							<div <?php post_class(); ?>>

								<div id="tour-details" class="travelo-box">
									<div class="tab-container">
										<ul class="tabs">
											<li class="active"><a href="#tour-description" data-toggle="tab">Chương trình</a></li>
											<li><a href="#tour-description-detail" data-toggle="tab">Chi tiết</a></li>
											<li><a href="#tour-note" data-toggle="tab">Lưu ý</a></li>
											<li><a href="#tour-contact" data-toggle="tab">Liên hệ</a></li>
										</ul>
										<div class="tab-content">
											<div id="tour-description" class="tab-pane fade in active">
												<?php //the_content(); 
												?>
												<?php the_field('tour_programs'); ?>
											</div>
											<div id="tour-description-detail" class="tab-pane fade">
												<?php the_field('tour_detail'); ?>
											</div>
											<div id="tour-note" class="tab-pane fade">
												<?php the_field('tour_note'); ?>
											</div>
											<div id="tour-contact" class="tab-pane fade">
												<?php the_field('tour_contact'); ?>
											</div>

										</div>

									</div>
									<?php if (!empty($repeated)) : ?>

									<?php endif; ?>


									<div class="fb-like" data-href="<?php get_permalink(); ?>" data-action="like" data-show-faces="false" data-share="true"></div>
									<div data-width="100%" class="fb-comments" data-href="<?php get_permalink(); ?>" data-numposts="15"></div>

								</div>
							</div>
						</div>

						<div class="col-sm-4 col-md-4">
							<?php //generated_dynamic_sidebar(); 
							?>
							<div class="short-description-widget widget">
								<h3 class="widget-title">TÓM TẮT CHƯƠNG TÌNH TOUR</h3>
								<div class="widget-content">
									<?php the_field('tour_short_desciption'); ?>
								</div>
							</div>
							<div class="amenity-widget widget">
								<h3 class="widget-title">DỊCH VỤ ĐI KÈM</h3>
								<div class="widget-content">
									<?php
									$tour_amenities = get_field('tour_amenities');
									if ($tour_amenities) {
										echo '<ul>';

										foreach ($tour_amenities as $amenity_id) {
											$custom_fields = get_option("taxonomy_term_" . $amenity_id);
											$term = get_term($amenity_id);

											echo 	'<div class="mg-bot10">
													<span class="fa-stack fa-lg">
														<i class="fa fa-circle fa-stack-2x orange"></i>
														<i class="' . $custom_fields['icon_text'] . '"></i>
													</span>
													<span style="margin-left: 10px">' . $term->name . '</span>
												</div>';
										}

										echo '</ul>';
									}
									?>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			</div>
		</section>
		<!-- Modal -->
		<div id="bookingModal" tabindex="-1" role="dialog" class="modal fade" style="padding:20px">
			<div class="modal-dialog" role="document">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title head_popup">ĐẶT TOUR NHANH<br />
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
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
													<input name="date_from" type="text" class="input-text full-width" value=<?php echo $tour_info['tour_date_start'] ?> placeholder="Ngày đi" required />

												</div>
											</div>
										</div>
										<div class="col-md-6">
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
										</div>
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
							'action': 'create_new_booking',
							'data': datastring
						},
						success: function(data) {
							console.log(data);
							jQuery('#billing-pop-up-status').html('<div class="alert alert-success"><strong>Đặt tour thành công!</strong> Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!</div>');

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
<?php
	endwhile;
}

get_footer();
