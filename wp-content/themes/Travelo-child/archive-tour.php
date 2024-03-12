<?php
/**
 * Tour Search Template
 */

get_header();

global $trav_options, $before_article, $after_article, $tour_list, $current_view, $date_from, $date_to, $language_count;

$order_array = array('ASC', 'DESC');
$order_by_array = array(
    'name' => 'tour_title',
    'price' => 'cast(min_price as unsigned)',
);
$trans = [
	'name' => 'Tên',
	'price' => 'Giá',
];
$order_defaults = array(
    'name' => 'ASC',
    'price' => 'ASC',
);

$s = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
$min_price = (isset($_REQUEST['min_price']) && is_numeric($_REQUEST['min_price'])) ? sanitize_text_field($_REQUEST['min_price']) : 0;
$max_price = (isset($_REQUEST['max_price']) && (is_numeric($_REQUEST['max_price']) || ($_REQUEST['max_price'] == 'no_max'))) ? sanitize_text_field($_REQUEST['max_price']) : '';
$order_by = (isset($_REQUEST['order_by']) && array_key_exists($_REQUEST['order_by'], $order_by_array)) ? sanitize_text_field($_REQUEST['order_by']) : 'name';
$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], $order_array)) ? sanitize_text_field($_REQUEST['order']) : 'ASC';
$tour_type = (isset($_REQUEST['tour_types'])) ? (is_array($_REQUEST['tour_types']) ? $_REQUEST['tour_types'] : array($_REQUEST['tour_types'])) : array();
$current_view = isset($_REQUEST['view']) ? sanitize_text_field($_REQUEST['view']) : 'list';
$page = (isset($_REQUEST['page']) && (is_numeric($_REQUEST['page'])) && ($_REQUEST['page'] >= 1)) ? sanitize_text_field($_REQUEST['page']) : 1;
$per_page = (isset($trav_options['tour_posts']) && is_numeric($trav_options['tour_posts'])) ? $trav_options['tour_posts'] : 12;

$date_from = empty($_REQUEST['date_from']) || trav_sanitize_date($_REQUEST['date_from']) == '' ? '' : $_REQUEST['date_from'];


$date_to = empty($_REQUEST['date_to']) || trav_sanitize_date($_REQUEST['date_to']) == '' || trav_strtotime($date_from) > trav_strtotime($_REQUEST['date_to']) ? '' : $_REQUEST['date_to'];


$place_from = (isset($_REQUEST['place_from'])) ? sanitize_text_field($_REQUEST['place_from']) : '';
$place_to = (isset($_REQUEST['place_to'])) ? sanitize_text_field($_REQUEST['place_to']) : '';

$locals = get_location();

if (is_tax()) {
    $queried_taxonomy = get_query_var('taxonomy');
    $queried_term = get_query_var('term');
    $queried_term_obj = get_term_by('slug', $queried_term, $queried_taxonomy);
    if ($queried_term_obj) {
        if (($queried_taxonomy == 'tour_type') && (!in_array($queried_term_obj->term_id, $tour_type))) {
            $tour_type[] = $queried_term_obj->term_id;
        }

    }
}


$tour_list = trav_tour_get_search_result(array('s' => $s, 'date_from' => $date_from, 'date_to' => $date_to, 'order_by' => 



$order_by_array[$order_by], 'order' => $order, 'last_no' => ($page - 1) * $per_page, 'per_page' => $per_page, 'min_price' => $min_price, 'max_price' => $max_price, 'tour_type' => $tour_type,'place_from'=> $place_from , 'place_to'=>$place_to));







//$count = trav_tour_get_search_result_count(array('min_price' => $min_price, 'max_price' => $max_price, 'tour_type' => $tour_type));

$count = count($tour_list);

$before_article = '';
$after_article = '';

?>

<section id="content">
    <div class="container">
        <div id="main">
            <div class="row">
                <div class="col-sm-4 col-md-3">
					<div class="sidebar search-sidebar">
                    <h4 class="search-results-title"><i class="soap-icon-search"></i><b><?php echo esc_html($count); ?></b> kết quả</h4>
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
                    <div class="sort-by-section clearfix box">
                       <h4 class="sort-by-title block-sm">Thứ tự sắp xếp</h4>
                        <ul class="sort-bar clearfix block-sm">
                            <?php
foreach ($order_by_array as $key => $value) {
    $active = '';
    $def_order = $order_defaults[$key];

    if ($key == $order_by) {
        $active = ' active';
        $def_order = ($order == 'ASC') ? 'DESC' : 'ASC';
    }

    echo '<li class="sort-by-' . esc_attr($key . $active) . '"><a class="sort-by-container" href="' . esc_url(add_query_arg(array('order_by' => $key, 'order' => $def_order))) . '"><span>' . esc_html(__($trans[$key], 'trav')) . '</span></a></li>';
}
?>
                        </ul>

                        <ul class="swap-tiles clearfix block-sm">
                            <?php
$views = array(
    'list' => __('List View', 'trav'),
    'grid' => __('Grid View', 'trav'),
    // 'block' => __( 'Block View', 'trav' )
);
$params = $_GET;

foreach ($views as $view => $label) {
    $active = ($view == $current_view) ? ' active' : '';
    echo '<li class="swap-' . esc_attr($view . $active) . '">';
    echo '<a href="' . esc_url(add_query_arg(array('view' => $view))) . '" title="' . esc_attr($label) . '"><i class="soap-icon-' . esc_attr($view) . '"></i></a>';
    echo '</li>';
}
?>
                        </ul>
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
					} else {?>
                        <div class="travelo-box">Tìm thấy 0 kết quả</div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

get_footer();