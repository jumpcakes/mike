<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array( 'associate-gray' => 'Gray', 'associate-green' => 'Green', 'associate-red' => 'Red' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Associate Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/associate' );

$content_width = apply_filters( 'content_width', 580, 0, 910 );

/** Unregister 3-column site layouts */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

/** Add new featured image sizes */
add_image_size( 'home-bottom', 150, 130, TRUE );
add_image_size( 'home-middle', 287, 120, TRUE );
add_image_size( 'home-middle', 80, 80, TRUE );


/** Add suport for custom background */
add_custom_background();

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array( 'width' => 960, 'height' => 120 ) );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

/** Register widget areas */
genesis_register_sidebar( array(
	'id'			=> 'featured',
	'name'			=> __( 'Featured', 'associate' ),
	'description'	=> __( 'This is the featured section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-1',
	'name'			=> __( 'Home Middle #1', 'associate' ),
	'description'	=> __( 'This is the first column of the home middle section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-2',
	'name'			=> __( 'Home Middle #2', 'associate' ),
	'description'	=> __( 'This is the second column of the home middle section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-3',
	'name'			=> __( 'Home Middle #3', 'associate' ),
	'description'	=> __( 'This is the third column of the home middle section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-bottom-1',
	'name'			=> __( 'Home Bottom #1', 'associate' ),
	'description'	=> __( 'This is the first column of the home bottom section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-bottom-2',
	'name'			=> __( 'Home Bottom #2', 'associate' ),
	'description'	=> __( 'This is the second column of the home bottom section.', 'associate' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'testimonials-sidebar',
	'name' 			=> 'Testimonials Sidebar',
	'description' 	=> 'This is the sidebar for testimonial pages.',
) );

add_action('get_header','cd_change_genesis_sidebar');
function cd_change_genesis_sidebar() {
    if ( is_singular('testimonials-widget')) { // Check if we're on a single post for my CPT called "jobs"
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); //remove the default genesis sidebar
        add_action( 'genesis_sidebar', 'cd_do_sidebar' ); //add an action hook to call the function for my custom sidebar
    }
}

//Function to output my custom sidebar
function cd_do_sidebar() {
	dynamic_sidebar( 'testimonials-sidebar' );
}


function replace_content($content)
{
$content = str_replace('three-fifths', 'location-content', $content);
$content = str_replace('one-fourth', 'location-map', $content);
return $content;
}
add_filter('the_content','replace_content');


/*
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
*/



function ds_enqueue_jquery_in_footer( &$scripts ) {
	 
	if ( ! is_admin() )
		$scripts->add_data( 'jquery', 'group', 1 );
}
add_action( 'wp_default_scripts', 'ds_enqueue_jquery_in_footer' );

