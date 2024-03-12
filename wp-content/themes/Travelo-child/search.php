<?php get_header(); ?>



<section id="content">
	<div class="container">
		<div class="row">
			<div id="main" class="col-sm-8 col-md-9">
				<!-- <div class="travelo-box">
					<h3><?php echo __( 'New Search', 'trav' );?></h3>
					<p><?php echo __( 'If you are not happy with the results below please do another search.', 'trav' ) ?></p>
					<div class="row">
						<div class="col-md-5">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div> -->
				<div class="page">
					<div class="post-content">
						
							
							<?php 
								if(isset($_GET['s'])){
									$search_term = $_GET['s'];
									
									$args = array(
										'post_type'     => 'tour',
										'posts_per_page'	=> 5,
										's' => $search_term,
									);
									
									global $tour_list,$before_article, $after_article;

									$tour_list = get_posts($args);
									
									if(count($tour_list) > 0){
										echo '<div class="section">';
										echo '<h2 class="section-title">Tour</h2>';
										echo '<div class="tour-packages listing-style3 image-box">';
										$before_article = '';
										$after_article = '';
											trav_get_template('tour-list.php', '/templates/tour/');
										echo  '</div>';
										echo  '</div>';
										wp_reset_postdata();
									}
									
									
									
									
								}
							?>
						</div>
						
							
							<?php 
								global $acc_list,$before_article, $after_article;
								if(isset($_GET['s'])){
									$search_term = $_GET['s'];
									
									$args = array(
										'post_type'     => 'accommodation',
										'posts_per_page'	=> 5,
										's' => $search_term,
									);
									

									$results = get_posts($args);
									
									if(count($results)) {
										echo '<div class="section">';
										echo '<h2 class="section-title">Khách sạn</h2>';
										$acc_list = array();
									
										foreach ( $results as $result ) {
											$acc_list[] = $result->ID;
										} 
										
										//print_r($acc_list);
										
										echo '<div class="image-box listing-style3 hotel">';
										$before_article = '';
										$after_article = '';
										//trav_get_template('tour-list.php', '/templates/tour/');
										trav_get_template( 'accommodation-list.php', '/templates/accommodation/'); 
										echo  '</div>';
										echo '</div>';
										wp_reset_postdata();
									}
									
									
								}
							?>
						
						
							
							<?php 
							$search_term = $_GET['s'];
									
							$args = array(
								'post_type'     => 'post',
								'posts_per_page'	=> 5,
								's' => $search_term,
							);
							$query = new WP_Query($args);
							if (  $query->have_posts() ) :
							echo '<div class="section">';
							echo '<h2 class="section-title">Khám phá</h2>';
							while ( $query->have_posts() ) : $query->the_post();
								trav_get_template( 'loop-blog.php', '/templates' );                              
							endwhile;
							echo '</div>';
							else:
							endif;
							wp_reset_query();
							?>
						</div>
					
			
			</div>
			<div class="sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'sidebar-post' ); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer();