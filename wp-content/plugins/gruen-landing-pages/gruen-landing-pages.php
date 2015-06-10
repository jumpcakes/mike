<?php
/**
* Plugin Name: Gruen Landing Pages
* Description: Adds the Landing Page post type. Requires Gravity Forms
* Version: 1.0
* Author: Gruen Agency
* Author URI: www.gruenagency.com
*/

// Check if Advanced Custom Fields is activated. If it is not, error message will display in admin
if( !class_exists('acf') ) {
	function glp_admin_notice() {
	    ?>
	    <div class="error">
	        <p><?php _e( 'Advanced Custom Fields is required with the Gruen Landing Pages plugin. Please install ACF or disable GLP.', 'my-text-domain' ); ?></p>
	    </div>
	    <?php
	}
	add_action( 'admin_notices', 'glp_admin_notice' );
}

// Register Custom Post Type
function glp_landingpage_post_type() {

	$labels = array(
		'name'                => _x( 'Landing Pages', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Landing Page', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Landing Pages', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'add_new_item'        => __( 'Add New Landing Page', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                => 'landing',
		'with_front'          => true,
		'pages'               => false,
		'feeds'               => false,
	);
	$args = array(
		'label'               => __( 'landing_page', 'text_domain' ),
		'description'         => __( 'Create landing pages easily on top of existing themes.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-welcome-widgets-menus',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'landing_page', $args );

}

// Hook into the 'init' action
add_action( 'init', 'glp_landingpage_post_type', 0 );


//Override Theme Template for Single Landing Page
function glp_change_post_type_template($single_template) 
{
     global $post;

     if ($post->post_type == 'landing_page') 
     {
          $single_template = plugin_dir_path( __FILE__ ) . 'templates/landing-default.php';
     }

     return $single_template;
}
add_filter( 'single_template', 'glp_change_post_type_template' );
