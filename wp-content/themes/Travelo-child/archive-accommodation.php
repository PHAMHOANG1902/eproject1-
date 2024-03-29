<?php
/**
* Accommodation Archive Template
 */

get_header();

global $current_view, $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $language_count;
$locals = get_location();

$order_array = array( 'ASC', 'DESC' );
$order_by_array = array(
    'name' => 'acc_title',
    'price' => 'cast(avg_price as unsigned)',
  //  'rating' => 'review'
);

$trans = [
	'name' => 'Tên',
	'price' => 'Giá',
];
$order_defaults = array(
    'name' => 'ASC',
    'price' => 'ASC',
    'rating' => 'DESC'
);

$s = isset($_REQUEST['s']) ? sanitize_text_field( $_REQUEST['s'] ) : '';
$rooms = ( isset( $_REQUEST['rooms'] ) && is_numeric( $_REQUEST['rooms'] ) ) ? sanitize_text_field( $_REQUEST['rooms'] ) : 1;
$adults = ( isset( $_REQUEST['adults'] ) && is_numeric( $_REQUEST['adults'] ) ) ? sanitize_text_field( $_REQUEST['adults'] ) : 1;
$kids = ( isset( $_REQUEST['kids'] ) && is_numeric( $_REQUEST['kids'] ) ) ? sanitize_text_field( $_REQUEST['kids'] ) : 0;
$min_price = ( isset( $_REQUEST['min_price'] ) && is_numeric( $_REQUEST['min_price'] ) ) ? sanitize_text_field( $_REQUEST['min_price'] ) : 0;
$max_price = ( isset( $_REQUEST['max_price'] ) && ( is_numeric( $_REQUEST['max_price'] ) || ( $_REQUEST['max_price'] == 'no_max' ) ) ) ? sanitize_text_field( $_REQUEST['max_price'] ) : 'no_max';
$rating = ( isset( $_REQUEST['rating'] ) && is_numeric( $_REQUEST['rating'] ) ) ? sanitize_text_field( $_REQUEST['rating'] ) : 0;
$order_by = ( isset( $_REQUEST['order_by'] ) && array_key_exists( $_REQUEST['order_by'], $order_by_array ) ) ? sanitize_text_field( $_REQUEST['order_by'] ) : 'name';
$order = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $order_array ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
$acc_type = ( isset( $_REQUEST['acc_type'] ) ) ? ( is_array( $_REQUEST['acc_type'] ) ? $_REQUEST['acc_type'] : array( $_REQUEST['acc_type'] ) ):array();
$amenities = ( isset( $_REQUEST['amenities'] ) && is_array( $_REQUEST['amenities'] ) ) ? $_REQUEST['amenities'] : array();
$current_view = isset( $_REQUEST['view'] ) ? sanitize_text_field( $_REQUEST['view'] ) : 'list';
$page = ( isset( $_REQUEST['page'] ) && ( is_numeric( $_REQUEST['page'] ) ) && ( $_REQUEST['page'] >= 1 ) ) ? sanitize_text_field( $_REQUEST['page'] ) : 1;
$per_page = ( isset( $trav_options['acc_posts'] ) && is_numeric($trav_options['acc_posts']) ) ? $trav_options['acc_posts'] : 12;

if ( is_tax() ) {
    $queried_taxonomy = get_query_var( 'taxonomy' );
    $queried_term = get_query_var( 'term' );
    $queried_term_obj = get_term_by('slug', $queried_term, $queried_taxonomy);
    if ( $queried_term_obj ) {
        if ( ( $queried_taxonomy == 'accommodation_type' ) && ( ! in_array( $queried_term_obj->term_id, $acc_type ) ) ) $acc_type[] = $queried_term_obj->term_id;
        if ( ( $queried_taxonomy == 'amenity' ) && ( ! in_array( $queried_term_obj->term_id, $amenities ) ) ) $amenities[] = $queried_term_obj->term_id;
    }
}

$date_from = isset( $_REQUEST['date_from'] ) ? trav_sanitize_date( $_REQUEST['date_from'] ) : '';
$date_to = isset( $_REQUEST['date_to'] ) ? trav_sanitize_date( $_REQUEST['date_to'] ) : '';


if ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) {
    $date_from = '';
    $date_to = '';
}

$place_to = (isset($_REQUEST['place_to'])) ? sanitize_text_field($_REQUEST['place_to']) : '';

$results = trav_acc_get_search_result( $s, $date_from, $date_to, $rooms, $adults, $kids, $order_by_array[$order_by], $order, ( $page - 1 ) * $per_page, $per_page, $min_price, $max_price, $rating, $acc_type, $amenities , $place_to );



//$count = trav_acc_get_search_result_count( $min_price, $max_price, $rating, $acc_type, $amenities );
$count = count($results);
global $before_article, $after_article, $acc_list;

$before_article = '';
$after_article = '';

$acc_list = array();


foreach ( $results as $result ) {
    $acc_list[] = $result->ID;
} 
//pirnt_r( $acc_list);

?>

<section id="content">
    <div class="container">
        <div id="main">
            <div class="row">
                <div class="col-sm-4 col-md-3">
					<div class="hotel-search-sidebar">
                    <h4 class="search-results-title">
                        <i class="soap-icon-search"></i><b><?php echo esc_html( $count ); ?></b> kết quả
                    </h4>
                    <div class="toggle-container style1 filters-container">
                        <div class="panel arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#modify-search-panel" class="">Tìm Khách sạn</a>
                            </h4>
                            <div id="modify-search-panel" class="panel-collapse collapse in">
                                <div class="panel-content">
                                     <form role="search" method="get" id="searchform" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                        <input type="hidden" name="post_type" value="accommodation">
                                        <input type="hidden" name="view" value="<?php echo esc_attr( $current_view ) ?>">
                                        <input type="hidden" name="order_by" value="<?php echo esc_attr( $order_by ) ?>">
                                        <input type="hidden" name="order" value="<?php echo esc_attr( $order ) ?>">
                                        <?php if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) && ( trav_get_default_language() != ICL_LANGUAGE_CODE ) ) { ?>
                                            <input type="hidden" name="lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ) ?>">
                                        <?php } ?>
                                        <div class="form-group">
                                            <label>Điểm đến</label>
											<select name="place_to" class="full-width">
												<?php foreach($locals as $local) : ?>
												<?php if($local->name == esc_attr($s)) : ?>
												<option value="<?php echo $local->term_id; ?>" selected><?php echo $local->name;?></option>
												<?php else: ?>
													<option value="<?php echo $local->term_id; ?>"><?php echo $local->name;?></option>
												<?php endif; ?>
												<?php endforeach ?>
											</select>
                                        </div>
                                        
                                        
                                        <br />
                                        <button class="btn-medium icon-check uppercase full-width">Tìm khách sạn</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php if ( $trav_options['acc_enable_price_filter'] ) : ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#price-filter" class="collapsed">Giá (mỗi đêm)</a>
                            </h4>
                            <div id="price-filter" class="panel-collapse collapse">
                                <div class="panel-content">
                                    <div id="price-range" data-slide-last-val="<?php echo esc_attr( ( ! empty($trav_options['acc_price_filter_max']) && is_numeric($trav_options['acc_price_filter_max']) ) ? $trav_options['acc_price_filter_max'] :200 ) ?>" data-slide-step="<?php echo esc_attr( ( ! empty($trav_options['acc_price_filter_step']) && is_numeric($trav_options['acc_price_filter_step']) ) ? $trav_options['acc_price_filter_step'] :50 ) ?>" data-def-currency="<?php echo esc_attr( trav_get_site_currency_symbol() );?>" data-min-price="<?php echo esc_attr( $min_price ); ?>" data-max-price="<?php echo esc_attr( $max_price ); ?>" data-url-noprice="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price', 'page' ) ) ); ?>"></div>
                                    <br />
                                    <span class="min-price-label pull-left"></span>
                                    <span class="max-price-label pull-right"></span>
                                    <div class="clearer"></div>
                                </div><!-- end content -->
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ( $trav_options['acc_enable_review_filter'] ) : ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#rating-filter" class="<?php echo ( $rating == 0 )?'collapsed':''?>"><?php _e( 'User Reviews', 'trav' );?></a>
                            </h4>
                            <div id="rating-filter" class="panel-collapse collapse filters-container <?php echo ( $rating == 0 )?'':'in'?>">
                                <div class="panel-content">
                                    <div id="rating" class="five-stars-container editable-rating" data-rating="<?php echo esc_attr( $rating );?>" data-url-norating="<?php echo esc_url( remove_query_arg( array( 'rating', 'page' ) ) ); ?>" data-label-norating="<?php _e( 'All Ratings', 'trav' );?>" data-label-rating="<?php _e( 'and Above', 'trav' );?>" data-label-fullrating="<?php _e( '5 Ratings', 'trav' );?>"></div>
                                    <span><?php _e( 'All', 'trav' );?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ( $trav_options['acc_enable_acc_type_filter'] ) : ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#accomodation-type-filter" class="<?php echo empty( $acc_type )?'collapsed':''?>"><?php _e( 'Accommodation Type', 'trav' ) ?></a>
                            </h4>
                            <div id="accomodation-type-filter" data-url-noacc_type="<?php echo esc_url( remove_query_arg( array( 'acc_type', 'page' ) ) ); ?>" class="panel-collapse collapse <?php echo empty( $acc_type )?'':'in'?>">
                                <div class="panel-content">
                                    <ul class="check-square filters-option">
                                        <?php
                                            $selected = ( $acc_type == '' )?' active':'';
                                            echo '<li class="all-types' . esc_attr( $selected ) . '"><a href="#">' . __( 'All', 'trav' ) . '<small>(' . esc_html( $count ) . ')</small></a></li>';
                                            $all_acc_types = get_terms( 'accommodation_type', array('hide_empty' => 0) );
                                            foreach ( $all_acc_types as $each_acc_type ) {
                                                $selected = ( ( is_array( $acc_type ) && in_array( $each_acc_type->term_id, $acc_type ) ) )?' class="active"':'';
                                                echo '<li' . $selected . ' data-term-id="' . esc_attr( $each_acc_type->term_id ) . '"><a href="#">' . esc_html( $each_acc_type->name ) . '<small>(' . esc_html( trav_acc_get_search_result_count( $min_price, $max_price, $rating, array( $each_acc_type->term_id ), $amenities ) ) . ')</small></a></li>';
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ( $trav_options['acc_enable_amenity_filter'] ) : ?>
                        <div class="panel style1 arrow-right">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#amenities-filter" class="<?php echo empty( $amenities )?'collapsed':''?>"><?php _e( 'Amenities', 'trav' ) ?></a>
                            </h4>
                            <div id="amenities-filter" data-url-noamenities="<?php echo esc_url( remove_query_arg( array( 'amenities', 'page' ) ) ); ?>" class="panel-collapse collapse <?php echo empty( $amenities )?'':'in'?>">
                                <div class="panel-content">
                                    <ul class="check-square filters-option">
                                        <?php
                                            $args = array(
                                                    'orderby'           => 'count', 
                                                    'order'             => 'DESC',
                                                    'hide_empty' => 0
                                                );

                                            $all_amenities = get_terms( 'amenity', $args );
                                            foreach ($all_amenities as $each_amenity) {
                                                $selected = ( ( is_array( $amenities ) && in_array( $each_amenity->term_id, $amenities ) ) )?' class="active"':'';
                                                echo '<li' . $selected . ' data-term-id="' . esc_attr( $each_amenity->term_id ) . '"><a href="#">' . esc_html( $each_amenity->name ) . '<small>(' . esc_html( trav_acc_get_search_result_count( $min_price, $max_price, $rating, $acc_type ,array( $each_amenity->term_id ) ) ) . ')</small></a></li>';
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
					</div>
                </div>
                <div class="col-sm-8 col-md-9">
                    <div class="sort-by-section clearfix box">
                        <h4 class="sort-by-title block-sm">Thứ tự sắp xếp</h4>
                        <ul class="sort-bar clearfix block-sm">
                            <?php
                                foreach( $order_by_array as $key => $value ) {
                                    $active = '';
                                    $def_order = $order_defaults[ $key ];

                                    if ( $key == $order_by ) {
                                        $active = ' active';
                                        $def_order = ( $order == 'ASC' )?'DESC':'ASC';
                                    }

                                    echo '<li class="sort-by-' . esc_attr( $key . $active ) . '"><a class="sort-by-container" href="' . esc_url( add_query_arg( array( 'order_by' => $key, 'order' => $def_order ) ) ) . '"><span>' . esc_html( __( $trans[$key], 'trav' ) ) . '</span></a></li>';
                                }
                            ?>
                        </ul>
                        
                        
                    </div>
                    <?php if ( ! empty( $results ) ) { ?>
                        <div class="hotel-list list-wrapper">
                            <?php 
                            if ( $current_view == 'block' ) {
                                echo '<div class="row image-box listing-style2 add-clearfix">';
                                $before_article = '<div class="col-sms-6 col-sm-6 col-md-4">';
                                $after_article = '</div>';
                            } elseif ( $current_view == 'grid' ) {
                                echo '<div class="row image-box hotel listing-style1 add-clearfix">';
                                $before_article = '<div class="col-sm-6 col-md-4">';
                                $after_article = '</div>';
                            } else {
                                echo '<div class="image-box listing-style3 hotel">';
                                $before_article = '';
                                $after_article = '';
                            }

                            trav_get_template( 'accommodation-list.php', '/templates/accommodation/'); 
                            ?>
                        </div>

                        <?php 
                        if ( ! empty( $trav_options['ajax_pagination'] ) ) {
                            if ( count( $results ) >= $per_page ) { 
                            ?>
                                <a href="<?php echo esc_url( add_query_arg( array( 'page' => ( $page + 1 ) ) ) ); ?>" class="uppercase full-width button btn-large btn-load-more-accs" data-view="<?php echo esc_attr( $current_view ); ?>" data-search-params="<?php echo esc_attr( http_build_query( $_GET, '', '&amp;' ) ) ?>"><?php echo __( 'load more listing', 'trav' ) ?></a>
                            <?php 
                            }
                        } else {
                            unset( $_GET['page'] );

                            $pagenum_link = strtok( $_SERVER["REQUEST_URI"], '?' ) . '%_%';
                            $total = ceil( $count / $per_page );
                            $args = array(
                                'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by formformat (below)
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

                            echo paginate_links( $args );
                        } 
                        ?>

                        </div>
                    <?php } else { ?>
                        <div class="travelo-box">Không khách sạn nào được tìm thấy.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

get_footer();