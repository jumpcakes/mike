<?php


// READ: this template was used to change the post_meta page template, genesis layout, and sidebar setting for all locations pages so that they are more organized. Dont use for template
 
function locations_test() {
 	global $post;
	$args = array(
		'post_type'		=> 'page',
		'nopaging'		=> 'true',
		'order'		=> 'ASC',
	);
 
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ) {
 
		// loop through posts
		while( $loop->have_posts() ): $loop->the_post();
			$pageid = get_the_ID();
			
			//echo '<pre>' . $pageid . '</pre>';
			$pbcityexists = get_metadata('post', $pageid, 'pb-city');
			if (!empty($pbcityexists)) {
				echo '<h5><pre style="display:inline-block;">Page ID: </pre>' . $pageid . '</h5>';
				
				///////
				// Enable lines below to parse through locations pages
				//////
				
				update_post_meta($pageid, '_wp_page_template', 'locations.php');
				update_post_meta($pageid, '_genesis_layout', 'content-sidebar');
				update_post_meta($pageid, '_ss_sidebar', 'locationspage');			
				$content_to_replace = the_content();
				replace_content($content_to_replace);
			}
 
		endwhile;
	}
 
	wp_reset_postdata();

}
// Add our custom loop
add_action( 'wp_head', 'locations_test' );

genesis();
