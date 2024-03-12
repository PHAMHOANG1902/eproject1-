<?php
/**
 * Inner style1
 */
?>

<div class="page-title-container">
    <div class="container">
        <div class="page-title pull-left">
            <h2 class="entry-title">
                <?php
if (is_category()) {
    single_cat_title();
} elseif (is_tag()) {
    single_tag_title();
} elseif (is_author()) {
    the_author_meta('display_name');
} elseif (is_date()) {
    single_month_title(' ');
} elseif (is_search()) {
    if (isset($_GET['post_type']) && ($_GET['post_type'] == 'accommodation')) {
        printf(__('Kết quả tìm kiếm: %s', 'trav'), '<span>' . get_search_query() . '</span>');
    } else {
        printf(__('Kết quả tìm kiếm: %s', 'trav'), '<span>' . get_search_query() . '</span>');
    }
} elseif (is_tax()) {
    if (get_query_var('taxonomy') == 'location') {
        printf(__('Activities in %s', 'trav'), single_term_title('', false));
    } else {
        single_term_title();
    }
} elseif (is_post_type_archive('accommodation')) {
    printf(__('Kết quả tìm kiếm khách sạn: %s', 'trav'), '<span>' . get_search_query() . '</span>');
} elseif (is_post_type_archive('tour')) {
    printf(__('Kết quả tìm kiếm: %s', 'trav'), '<span>' . get_search_query() . '</span>');
} elseif (is_post_type_archive('product')) {
    woocommerce_page_title();
} elseif (is_single()) {
	global $post;
	if($post->post_type == 'tour'){
	
	
	} 
}else {
    the_title();
}
?>
            </h2>
        </div>
        <?php //trav_breadcrumbs();?>		
    </div>
</div>