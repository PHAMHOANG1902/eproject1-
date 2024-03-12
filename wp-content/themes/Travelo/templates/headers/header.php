<?php

/**
 * Default Header
 */
global $trav_options, $logo_url, $my_account_page, $login_url, $signup_url, $language_count;
?>
<header id="header" class="navbar-static-top">
	<div class="topnav hidden-xs">
		<div class="container">
			<?php
			$defaults = array(
				'theme_location'  => 'left-header-menu',
				'menu'            => '',
				'container'       => '',
				'container_class' => '',
				'container_id'    => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul class="quick-menu pull-left">%3$s</ul>',
				'depth'           => 0,
				'walker'          => ''
			);
			wp_nav_menu($defaults);
			?>

		</div>
	</div>
	<div id="main-header">

		<div class="main-header">
			<a href="#mobile-menu-01" data-toggle="collapse" class="mobile-menu-toggle">
				Mobile Menu Toggle
			</a>

			<div class="container">
				<div class="logo navbar-brand">
					<a href="<?php echo esc_url(home_url()); ?>">
						<img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" />
					</a>
				</div>
				<?php if ($trav_options['woo_show_mini_cart'] && class_exists('WooCommerce')) { ?>
					<div class="mini-cart">
						<a href="<?php echo wc_get_cart_url() ?>" class="cart-contents" title="<?php _e('View Cart', 'trav') ?>">
							<i class="soap-icon-shopping"></i>
							<div class="item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></div>
						</a>
					</div>
				<?php }	?>
				<?php if (has_nav_menu('header-menu')) {
					echo '<div class="header-menu-wrap">';
					wp_nav_menu(array('theme_location' => 'header-menu', 'container' => 'nav', 'container_id' => 'main-menu', 'menu_class' => 'menu', 'walker' => new Trav_Walker_Nav_Menu));
					echo '<div class="clearfix"></div></div>';
				} else { ?>
					<nav id="main-menu" class="menu-my-menu-container">
						<ul class="menu">
							<li class="menu-item"><a href="<?php echo esc_url(home_url()); ?>">Home</a></li>
							<li class="menu-item"><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php _e('Configure', "trav"); ?></a></li>

						</ul>

					</nav>
				<?php } ?>
				<div id="top-search"> <a class="search-click"><i class="fa fa-search"></i></a>
					<div class="show-search" style="display: none;">
						<form role="search" method="get" id="searchform" action="<?php echo get_site_url(); ?>">
							<div> <input type="text" class="search-input" placeholder="Nhập từ khóa và enter" name="s" id="s"></div>
						</form> <a class="search-click close-search"><i class="fa fa-close"></i></a>
					</div>
				</div>
			</div><!-- .container -->

		</div><!-- .main-header -->
	</div><!-- #main-header -->