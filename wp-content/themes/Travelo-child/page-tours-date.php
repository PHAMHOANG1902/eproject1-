<?php
/*
Template Name:Tours date By list Template
 */
 
get_header();

global $trav_options, $before_article, $after_article, $tour_list, $current_view, $date_from, $date_to, $language_count;


if(isset($_GET['tour_id'])){
	global $post;
	$tour_id = $_GET['tour_id'];
	$post = get_post($tour_id);

	
	setup_postdata( $post );
} else {
	die;
}

$min_price =  0;
$tour_type = array();
$date_from = '';
$place_from =  '';
$place_to = '';

$locals = get_location();

$before_article = '';
$after_article = '';

?>

<section id="content">
    <div class="container">
        <div id="main">
            <div class="row">
                <div class="col-sm-4 col-md-3">
					<div class="sidebar search-sidebar">                   
                    <div class="toggle-container style1 filters-container">
                        <div class="panel arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#modify-search-panel" class="">Tìm Tour</a>
                            </h4>
                            <div id="modify-search-panel" class="panel-collapse collapse in">
                                <div class="panel-content">
                                    <form role="search" method="get" id="searchform" class="tour-searchform" action="<?php echo esc_url(home_url('/')); ?>">
                                        <input type="hidden" name="post_type" value="tour">
                                        <input type="hidden" name="view" value="<?php echo esc_attr($current_view) ?>">
                                        <input type="hidden" name="order_by" value="<?php echo esc_attr($order_by) ?>">
                                        <input type="hidden" name="order" value="<?php echo esc_attr($order) ?>">
                                        <?php if (defined('ICL_LANGUAGE_CODE') && ($language_count > 1) && (trav_get_default_language() != ICL_LANGUAGE_CODE)) {?>
                                            <input type="hidden" name="lang" value="<?php echo esc_attr(ICL_LANGUAGE_CODE) ?>">
                                        <?php }?>
                                        <div class="form-group">
                                            <label>Khởi hành từ</label>
											<select name="s" class="full-width">
												<option value="">Khởi hành từ</option>
												<?php foreach($locals as $local) : ?>
												<?php if($local->name == esc_attr($s)) : ?>
												<option value="<?php echo $local->name; ?>" selected><?php echo $local->name;?></option>
												<?php else: ?>
													<option value="<?php echo $local->name; ?>"><?php echo $local->name;?></option>
												<?php endif; ?>
												<?php endforeach ?>
											</select>
                                        </div>
                                        <div class="search-when" data-error-message1="Ngày bắt đầu lớn hơn ngày kết thúc" data-error-message2="Xin vui lòng chọn 1 ngày hiện tại hoặc trong tương lai">
                                            <div class="form-group">
                                                <label>Ngày khởi hành</label>
                                                <div class="datepicker-wrap from-today">
                                                    <input name="date_from" type="text" class="input-text full-width" placeholder="Ngày đi" value="<?php echo esc_attr($date_from); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <button class="btn-medium icon-check uppercase full-width">Tìm Tour</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php if ($trav_options['tour_enable_price_filter']): ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#price-filter" class="collapsed">Giá</a>
                            </h4>
                            <div id="price-filter" class="panel-collapse collapse">
                                <div class="panel-content">
                                    <div id="price-range" data-slide-last-val="<?php echo esc_attr((!empty($trav_options['tour_price_filter_max']) && is_numeric($trav_options['tour_price_filter_max'])) ? $trav_options['tour_price_filter_max'] : 200) ?>" data-slide-step="<?php echo esc_attr((!empty($trav_options['tour_price_filter_step']) && is_numeric($trav_options['tour_price_filter_step'])) ? $trav_options['tour_price_filter_step'] : 50) ?>" data-def-currency="<?php echo esc_attr(trav_get_site_currency_symbol()); ?>" data-min-price="<?php echo esc_attr($min_price); ?>" data-max-price="<?php echo esc_attr($max_price); ?>" data-url-noprice="<?php echo esc_url(remove_query_arg(array('min_price', 'max_price', 'page'))); ?>"></div>
                                    <br />
                                    <span class="min-price-label pull-left"></span>
                                    <span class="max-price-label pull-right"></span>
                                    <div class="clearer"></div>
                                </div><!-- end content -->
                            </div>
                        </div>
                        <?php endif;?>

                        <?php if ($trav_options['tour_enable_tour_type_filter']): ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#tour-type-filter" class="<?php echo empty($tour_type) ? 'collapsed' : '' ?>">Loại hình Tour</a>
                            </h4>
                            <div id="tour-type-filter" data-url-notour_type="<?php echo esc_url(remove_query_arg(array('tour_types', 'page'))); ?>" class="panel-collapse collapse <?php echo empty($tour_type) ? '' : 'in' ?>">
                                <div class="panel-content">
                                    <ul class="check-square filters-option">
                                        <?php
$selected = ($tour_type == '') ? ' active' : '';
echo '<li class="all-types' . esc_attr($selected) . '"><a href="#">' . __('Tất cả', 'trav') . '</a></li>';
$all_tour_types = get_terms('tour_type', array('hide_empty' => 0));
foreach ($all_tour_types as $each_tour_type) {
    $selected = ((is_array($tour_type) && in_array($each_tour_type->term_id, $tour_type))) ? ' class="active"' : '';
    echo '<li' . $selected . ' data-term-id="' . esc_attr($each_tour_type->term_id) . '"><a href="#">' . esc_html($each_tour_type->name) . '</a></li>';
}
?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>

                    </div>
                </div>
				</div>
                <div class="col-sm-8 col-md-9">
                   <div class="tour-list list-wrapper">
					<div class="tour-packages listing-style3 image-box">
					<?php			
					if( have_rows('tour_date_start_repeater') ):

						
						while ( have_rows('tour_date_start_repeater') ) : the_row();
							
							$date = str_replace('/', '-', get_sub_field('tour_date_start'));
							if(time() <= strtotime($date)){
								
								$t =  [
									'tour_sku' => get_sub_field('tour_sku'),
									'tour_date_start' => get_sub_field('tour_date_start'),
									'tour_limit_person' => get_sub_field('tour_limit_person'),
									'tour_price_per_person' => get_sub_field('tour_price_per_person')
								];
								
							}
							
							 $query_args = array(
								'date_from' => get_sub_field('tour_date_start')
							);

							$url = esc_url(add_query_arg($query_args, get_permalink($tour_id)));
							
						?>
						
						<article class="box">
							<figure class="col-sm-5 col-md-4">
								<a title="View Photo Gallery" class="hover-effect" data-post_id="<?php echo esc_attr($tour_id); ?>" href="<?php echo $url; ?>"><?php echo get_the_post_thumbnail($tour_id, 'biggallery-thumb'); ?></a>
								 <?php if (!empty($discount_rate)) {?>
								<span class="discount"><span class="discount-text"><?php echo esc_html($discount_rate . '%' . ' ' . __('Discount', 'trav')); ?></span></span>
								<?php }?>
											</figure>
							<div class="details col-sm-7 col-md-8">
								<div>
									<div>
										<h4 class="box-title box-full-title"><a href="<?php echo $url; ?>"><?php echo esc_html(get_the_title($tour_id)); ?></a><small><i class="soap-icon-clock yellow-color"></i> <?php echo esc_html(get_field('tour_duration')) ?></small></h4>
										<p>Mã tour:  <?php echo get_sub_field('tour_sku'); ?></p>
										<p>Ngày khởi hành: <?php echo get_sub_field('tour_date_start'); ?></p>
									</div>
									<div>
										<span class="price"><small>mỗi người</small><?php echo ho_format_money(get_sub_field('tour_price_per_person')) ?> </span>
									</div>
								</div>
								
							</div>
						</article>
							
							
						<?php
						
							//echo date_create_from_format('j-M-Y', get_sub_field('tour_date_start')) .'<br />';

						endwhile;

					else :

						// no rows found

					endif;
					
					?>
					
					
				   </div>
				   </div>

                    <?php if (!empty($tour_list)) {
    ?>
                        <div class="tour-list list-wrapper">
                            <?php if ($current_view == 'grid') {
        //echo '<div class="row image-box tour listing-style1 add-clearfix">';
        echo '<div class="tour-packages listing-style1 row add-clearfix image-box">';
        $before_article = '<div class="col-sm-6 col-md-4">';
        $after_article = '</div>';
    } elseif ($current_view == 'block') {
        echo '<div class="tour-packages listing-style2 row add-clearfix image-box">';
        $before_article = '<div class="col-sm-6 col-md-4">';
        $after_article = '</div>';
    } else {
        echo '<div class="tour-packages listing-style3 image-box">';
        $before_article = '';
        $after_article = '';
    }

    trav_get_template('tour-list.php', '/templates/tour/');?>

                        </div>
                        <?php
if (!empty($trav_options['ajax_pagination'])) {
        if (count($tour_list) >= $per_page) {
            ?>
                                <a href="<?php echo esc_url(add_query_arg(array('page' => ($page + 1)))); ?>" class="uppercase full-width button btn-large btn-load-more-accs" data-view="<?php echo esc_attr($current_view); ?>" data-search-params="<?php echo esc_attr(http_build_query($_GET, '', '&amp;')) ?>"><?php echo __('load more listing', 'trav') ?></a>
                            <?php
}
    } else {
        unset($_GET['page']);

        $pagenum_link = strtok($_SERVER["REQUEST_URI"], '?') . '%_%';
        $total = ceil($count / $per_page);
        $args = array(
            'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
            'total' => $total,
            'format' => '?page=%#%',
            'current' => $page,
            'show_all' => false,
            'prev_next' => true,
            'prev_text' => __('Previous', 'trav'),
            'next_text' => __('Next', 'trav'),
            'end_size' => 1,
            'mid_size' => 2,
            'type' => 'list',
            'add_args' => $_GET,
        );

        echo paginate_links($args);
    }
    ?>

                        </div>
                    <?php
				//	wp_reset_postdata();
					} ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

get_footer();