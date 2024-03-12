<?php

/**
 * Footer
 */
global $trav_options, $logo_url;
$footer_skin = empty($trav_options['footer_skin']) ? 'style-def' : $trav_options['footer_skin'];
$locals = get_location();
$foreigns = get_location(85);

?>

<footer id="footer" class="<?php echo esc_attr($footer_skin) ?>">
	<div class="footer-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-3 footer-col">
					<?php dynamic_sidebar('sidebar-footer-1'); ?>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 footer-col">
					<?php //dynamic_sidebar( 'sidebar-footer-2' );
					?>
					<h2 class="widgettitle">TRONG NƯỚC</h2>
					<div class="widget_nav_menu">
						<ul>
							<?php foreach ($locals as $local) : ?>
								<li><a href="<?php echo get_site_url(); ?>/?post_type=tour&place_to=<?php echo $local->term_id; ?>"><?php echo $local->name; ?></a></li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6 col-md-3 footer-col">
					<?php //dynamic_sidebar( 'sidebar-footer-3' );
					?>
					<h2 class="widgettitle">NƯỚC NGOÀI</h2>
					<div class="widget_nav_menu">
						<ul>
							<?php foreach ($foreigns as $local) : ?>
								<li><a href="<?php echo get_site_url(); ?>/?post_type=tour&place_to=<?php echo $local->term_id; ?>"><?php echo $local->name; ?></a></li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 footer-col">
					<?php dynamic_sidebar('sidebar-footer-4'); ?>
				</div>

			</div>
		</div>
	</div>
	<div class="bottom gray-area">
		<div class="container">
			<div class="logo">
				<img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" />
			</div>
			<p class="copyright-text">
			</p>


		</div>
	</div>
</footer>
</div>
<div class="opacity-overlay opacity-ajax-overlay"><i class="fa fa-spinner fa-spin spinner"></i></div>
<script>
	// jQuery(document).ready(function() {
	// //######## SCROLL SIDEBAR #######//
	// if(jQuery('.search-sidebar').length){
	// if(jQuery(document).width() > 768){
	// var sidebar = jQuery('.search-sidebar');
	// var pos = sidebar.offset();
	// //console.log(pos);
	// var h = pos.top;

	// jQuery(window).scroll(function () {
	// //console.log(sidebar.offset().top );

	// if (jQuery(this).scrollTop() > h && ( jQuery(this).scrollTop() + jQuery("#footer").height() ) < jQuery("#footer").offset().top ) {
	// sidebar.css('width', sidebar.width());
	// sidebar.addClass("fixed");

	// } else {
	// sidebar.removeClass("fixed");
	// sidebar.css('width', 'auto');
	// }
	// });
	// }
	// }

	// })
	jQuery(document).ready(function() {
		jQuery(".search-click").on("click", function() {
			if ((jQuery(".show-search")).is(":visible")) {
				jQuery(".show-search").hide();
			} else {
				jQuery(".show-search").show();
			}
		});
	});
</script>


<script src="https://uhchat.net/code.php?f=b98756"></script>
<?php wp_footer(); ?>
</body>

</html>