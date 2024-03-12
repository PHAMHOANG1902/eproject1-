<?php
 $_SESSION['user_currency'] = sanitize_text_field('VND' );

 
 // Our custom post type function
function create_tour_booking_type() {
 
    register_post_type( 'tour-bookings',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Tour bookings' ),
                'singular_name' => __( 'Tour bookings' )
            ),
			'supports' => array( 'title', 'author' ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'tour-bookings'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_tour_booking_type' );

function create_hotel_booking_type() {
 
    register_post_type( 'hotel-bookings',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Hotel bookings' ),
                'singular_name' => __( 'Hotel bookings' )
            ),
			'supports' => array( 'title', 'author' ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'hotel-bookings'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_hotel_booking_type' );

 
function register_my_menus() {
  register_nav_menus(
	array(
		'left-header-menu' => __( 'Left Header Menu' ),
		'right-header-menu' => __( 'Right Header Menu' ),
	)
  );
}
add_action( 'init', 'register_my_menus' );

function get_location($parent = 74){
		
	//in : torng nuoc
	//out: ngoai
	$locals = get_terms(array(
		'taxonomy' => 'location',
		'hide_empty' => false,
		'parent'   => $parent
	));
	
	return $locals;
	
}



function amenity_taxonomy() {
 
        /* Biến $label chứa các tham số thiết lập tên hiển thị của Taxonomy
         */
        $labels = array(
                'name' => 'Amenity',
                'singular' => 'Amenity',
                'menu_name' => 'Tiện ích'
        );
 
        /* Biến $args khai báo các tham số trong custom taxonomy cần tạo
         */
        $args = array(
                'labels'                     => $labels,
                'hierarchical'               => false,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
        );
 
        /* Hàm register_taxonomy để khởi tạo taxonomy
         */
        register_taxonomy('tour_amenity', array('tour'), $args);
 
}
 
// Hook into the 'init' action
add_action( 'init', 'amenity_taxonomy', 0 );


add_action('wp_ajax_create_new_booking', 'create_new_booking');
add_action('wp_ajax_nopriv_create_new_booking', 'create_new_booking');

function create_new_booking(){
	
	//$input = serialize(array_values($_POST));
	 parse_str($_POST['data'], $params);
	//print_r($params);
	
	
	
	if(!isset($params['post_id']) || !isset($params['full_name'])){
		return;
	}
	
	$my_post = [];
	
	$hotel = get_post($params['post_id']);
		
	$link = '';
	if(isset($params['post_id'])){
		$link = get_post_permalink($params['post_id']);
	}
	
	$my_post = array(
		'post_type' => 'tour-bookings',
		'post_status'   => 'private',
		'post_author'   => 2,
		'post_title' => $params['full_name'] . ' - ' . $hotel->post_title
	);
	 
	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );
	
	update_field('b_fullname', $params['full_name'], $post_id);
	update_field('b_email', $params['email'], $post_id);
	update_field('b_phone', $params['phone'], $post_id);
	update_field('b_person', $params['adults'], $post_id);
	update_field('b_date_from', $params['date_from'], $post_id);
	update_field('b_link', $link, $post_id);
	
	
	$limit_person = get_field( "tour_limit_person" , $hotel->ID);
	if($limit_person){
		$limit_person = (int)$limit_person;
		update_field('tour_limit_person', $limit_person - 1, $hotel->ID);
	}
	
	
	
	//send email
	if(isset($params['email'])){
    
		$name = $params['full_name'];
		$phone = $params['phone'] ;
		$email = $params['email'];

		$to = get_settings('admin_email');
		$subject = '[Hosana] booking';
		$body = 
		'Họ tên khách hàng: '.$name.'<br />'. 
		'Điện thoại khách hàng: '.$phone . '<br />' . 
		'Email: '.$email . '<br />'.
		'Chi tiết:' . $link		;
		$headers = array('Content-Type: text/html; charset=UTF-8');

		wp_mail( $to, $subject, $body, $headers );
		
		$body2 = "Chào bạn ".$name.' !<br /><br />Bạn đã gửi yêu cầu đặt tour tới Hosana, chúng tôi sẽ liên lạc với bạn sớm nhất.';
		wp_mail( $email , '[Hosana] Đặt tour thành công',  $body2 , $headers );
	}
}

/////// HOTEL CREATE BOOKING  ////

add_action('wp_ajax_create_new_hotel_booking', 'create_new_hotel_booking');
add_action('wp_ajax_nopriv_create_new_hotel_booking', 'create_new_hotel_booking');

function create_new_hotel_booking(){
	
	//$input = serialize(array_values($_POST));
	 parse_str($_POST['data'], $params);
	//print_r($params);
	
	
	
	if(!isset($params['post_id']) || !isset($params['full_name'])){
		return;
	}
	
	$my_post = [];
	
	$hotel = get_post($params['post_id']);
		
	$link = '';
	if(isset($params['post_id'])){
		$link = get_post_permalink($params['post_id']);
	}
	
	$my_post = array(
		'post_type' => 'hotel-bookings',
		'post_status'   => 'private',
		'post_author'   => 2,
		'post_title' => $params['full_name'] . ' - ' . $hotel->post_title
	);
	 
	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );
	
	update_field('h_fullname', $params['full_name'], $post_id);
	update_field('h_email', $params['email'], $post_id);
	update_field('h_phone', $params['phone'], $post_id);
	update_field('h_person', $params['adults'], $post_id);
	update_field('h_date_from', $params['date_from'], $post_id);
	update_field('h_date_to', $params['date_to'], $post_id);
	update_field('h_link', $link, $post_id);
	
	
	
	
	//send email
	if(isset($params['email'])){
    
		$name = $params['full_name'];
		$phone = $params['phone'] ;
		$email = $params['email'];

		$to = get_settings('admin_email');
		$subject = '[Hosana] booking';
		$body = 
		'Họ tên khách hàng: '.$name.'<br />'. 
		'Điện thoại khách hàng: '.$phone . '<br />' . 
		'Email: '.$email . '<br />'.
		'Chi tiết:' . $link		;
		$headers = array('Content-Type: text/html; charset=UTF-8');

		wp_mail( $to, $subject, $body, $headers );
		
		$body2 = "Chào bạn ".$name.' !<br /><br />Bạn đã gửi yêu cầu đặt phòng tới Hosana, chúng tôi sẽ liên lạc với bạn sớm nhất.';
		wp_mail( $email , '[Hosana] Đặt phòng thành công',  $body2 , $headers );
	}
}

function no_wp_logo_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'no_wp_logo_admin_bar_remove', 0);

remove_action('welcome_panel', 'wp_welcome_panel');


add_filter( 'admin_footer_text', '__return_empty_string', 11 );
//add_filter( 'update_footer',     '__return_empty_string', 11 );

// A callback function to add a custom field to our "presenters" taxonomy  
function tour_amenity_taxonomy_custom_fields($tag) {  
   // Check for existing taxonomy meta for the term you're editing  
    $t_id = $tag->term_id; // Get the ID of the term you're editing  
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check  
?>  
  
<tr class="form-field">  
    <th scope="row" valign="top">  
        <label for="icon_text"><?php _e('Icon text'); ?></label>  
    </th>  
    <td>  
        <input type="text" name="term_meta[icon_text]" id="term_meta[icon_text]" size="25" style="width:60%;" value="<?php echo $term_meta['icon_text'] ? $term_meta['icon_text'] : ''; ?>"><br />  
        <span class="description"><?php _e('Icon text FontAwesome'); ?></span>  
    </td>  
</tr>  
  
<?php  
}
// A callback function to save our extra taxonomy field(s)  
function save_taxonomy_custom_fields( $term_id ) {  
    if ( isset( $_POST['term_meta'] ) ) {  
        $t_id = $term_id;  
        $term_meta = get_option( "taxonomy_term_$t_id" );  
        $cat_keys = array_keys( $_POST['term_meta'] );  
            foreach ( $cat_keys as $key ){  
            if ( isset( $_POST['term_meta'][$key] ) ){  
                $term_meta[$key] = $_POST['term_meta'][$key];  
            }  
        }  
        //save the option array  
        update_option( "taxonomy_term_$t_id", $term_meta );  
    }  
}  

// Add the fields to the "presenters" taxonomy, using our callback function  
add_action( 'tour_amenity_edit_form_fields', 'tour_amenity_taxonomy_custom_fields', 10, 2 ); 
// Save the changes made on the "presenters" taxonomy, using our callback function  
add_action( 'edited_tour_amenity', 'save_taxonomy_custom_fields', 10, 2 );  

function ho_format_money($string){	
	if($string){
		return number_format(esc_html($string), 0, ',', '.').' đ';
	}
	return '';
}

function remove_menus()
{
    // remove_menu_page('index.php');
	// remove_menu_page('upload.php');
	// remove_menu_page('themes.php');
	// remove_menu_page('plugins.php');
    remove_menu_page('edit-comments.php');
	
}
add_action('admin_menu', 'remove_menus', 999);


add_action('admin_enqueue_scripts', 'load_admin_styles');
function load_admin_styles()
{
	wp_enqueue_style('styles', get_stylesheet_directory_uri() . '/admin/css/admin-style.css', false, '1.0.0');
	wp_enqueue_script( 'my-scripts', get_stylesheet_directory_uri(). '/admin/js/myscript.js' ,false, '1.0.0');
	wp_enqueue_script( 'qrcode-scripts', get_stylesheet_directory_uri() . '/admin/js/qrcode.min.js',false, '1.0.0');
	
}



//QR CODE GENERATE


/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes($post_type) {
	//echo $post_type;
    //if ( in_array( $post_type, $post_types ) ) {
		add_meta_box( 'meta-box-id', 
		__( 'QR CODE', 'textdomain' ), 
		'wpdocs_my_display_callback', 
		'tour' ,
		'normal',                  // $context
       'high'                     // $priority
		);
	//}
	
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wpdocs_my_display_callback() {
	global $post;
	
	if($post->post_status == 'publish'){ 
	?>
		
	<div id="qrcode"></div>
	<script type="text/javascript">
	new QRCode(document.getElementById("qrcode"), "<?php echo get_permalink(); ?>");
	</script>
	<?php 
	}
?>
	
<?php
}

function get_tour_nearest(){
	
	global $post;
	if( have_rows('tour_date_start_repeater') ):

		
		while ( have_rows('tour_date_start_repeater') ) : the_row();
			
			$date = str_replace('/', '-', get_sub_field('tour_date_start'));
			if(time() <= strtotime($date)){
				
				$query_args = array(
					'date_from' => get_sub_field('tour_date_start')
				);

				$url = esc_url(add_query_arg($query_args, get_permalink()));
				
				return [
					'tour_sku' => get_sub_field('tour_sku'),
					'tour_date_start' => get_sub_field('tour_date_start'),
					'tour_limit_person' => get_sub_field('tour_limit_person'),
					'tour_price_per_person' => get_sub_field('tour_price_per_person'),
					'url' => $url
				];
				
			}
			
			
		
		
			//echo date_create_from_format('j-M-Y', get_sub_field('tour_date_start')) .'<br />';

		endwhile;

	else :

		// no rows found

	endif;
}


function get_tour_by_date($date){	
	global $post;
	if( have_rows('tour_date_start_repeater') ):

		
		while ( have_rows('tour_date_start_repeater') ) : the_row();
			
			$tour_date = str_replace('/', '-', get_sub_field('tour_date_start'));
			if($date  == strtotime($tour_date)){
				
				$query_args = array(
					'date_from' => get_sub_field('tour_date_start')
				);

				$url = esc_url(add_query_arg($query_args, get_permalink()));
				
				return [
					'tour_sku' => get_sub_field('tour_sku'),
					'tour_date_start' => get_sub_field('tour_date_start'),
					'tour_limit_person' => get_sub_field('tour_limit_person'),
					'tour_price_per_person' => get_sub_field('tour_price_per_person'),
					'url' => $url
				];
				
			}
			
			
		
		
			//echo date_create_from_format('j-M-Y', get_sub_field('tour_date_start')) .'<br />';

		endwhile;

	else :

		// no rows found

	endif;
}