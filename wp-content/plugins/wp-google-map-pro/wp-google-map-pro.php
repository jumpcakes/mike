<?php 
/*
Plugin Name: WP Google Map Pro
Description: A complete Google Map Solution for Basic to Advance Google Map.
Author: flippercode
Version: 1.2.1
Author URI: http://www.flippercode.com
*/

register_activation_hook( __FILE__, 'wpgmp_activation' );

add_action( 'plugins_loaded', 'wpgmp_load_plugin_languages' );

function wpgmp_load_plugin_languages() {
  load_plugin_textdomain( 'wpgmp_google_map', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
}

/**
 * This function used to install required tables in the database on time of activation.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_activation() {
  global $wpdb;	
  $map_location = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."map_locations` (
  				  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  				  `location_title` varchar(255) NOT NULL,
  				  `location_address` varchar(255) NOT NULL,
  				  `location_draggable` varchar(255) NOT NULL,
 				  `location_latitude` varchar(255) NOT NULL,
  				  `location_longitude` varchar(255) NOT NULL, 
  				  `location_messages` text NOT NULL,
  				  `location_marker_image` text NOT NULL,
  				  `location_group_map` int(11) NOT NULL,
  				  `location_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  				  PRIMARY KEY (`location_id`)
				  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
 $wpdb->query($map_location);
 
 $create_map = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."create_map` (
  			   `map_id` int(11) NOT NULL AUTO_INCREMENT,
  			   `map_title` varchar(255) NOT NULL,
  			   `map_width` varchar(255) NOT NULL,
  			   `map_height` varchar(255) NOT NULL,
  			   `map_zoom_level` varchar(255) NOT NULL,
  			   `map_type` varchar(255) NOT NULL,
  			   `map_scrolling_wheel` varchar(255) NOT NULL,
 			   `map_visual_refresh` varchar(255) NOT NULL,
  			   `map_45imagery` varchar(255) NOT NULL,
  			   `map_street_view_setting` text NOT NULL,
  			   `map_route_direction_setting` text NOT NULL,
  			   `map_all_control` text NOT NULL,
  			   `map_info_window_setting` text NOT NULL,
  			   `style_google_map` text NOT NULL,
  			   `map_locations` text NOT NULL,
  			   `map_layer_setting` text NOT NULL,
  			   `map_polygon_setting` text NOT NULL,
  			   `map_polyline_setting` text NOT NULL,
  			   `map_cluster_setting` text NOT NULL,
  			   `map_overlay_setting` text NOT NULL,
  			   PRIMARY KEY (`map_id`)
			   ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
 $wpdb->query($create_map);

 $group_map = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."group_map` (
  			  `group_map_id` int(11) NOT NULL AUTO_INCREMENT,
  			  `group_map_title` varchar(255) NOT NULL,
  			  `group_marker` text NOT NULL,
  			  `group_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  			  PRIMARY KEY (`group_map_id`)
			  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
 $wpdb->query($group_map);
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * This function used to register required scripts.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}

/**
 * This function used to register required styles in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_admin_styles() {
	wp_enqueue_style('thickbox');
}

$wpgmp_containers=array('map'); 

/**
 * This function used to display navigations menu in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */

function wpgmp_google_map_page() {
    define("wpgmp_plugin_permissions", "add_users");
    add_menu_page(
        __("WP Google Map", "wpgmp_google_map"),
        __("WP Google Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_map_pro",
        "wpgmp_admin_overview"
    );
    add_submenu_page(
        "wpgmp_google_map_pro",
        __("Add Locations", "wpgmp_google_map"),
        __("Add Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_add_location",
        "wpgmp_add_locations"
    );
   add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Locations", "wpgmp_google_map"),
        __("Manage Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_manage_location",
        "wpgmp_manage_locations"
    );
    
	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Create Map", "wpgmp_google_map"),
        __("Create Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_create_map",
        "wpgmp_create_map"
    );
	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Map", "wpgmp_google_map"),
        __("Manage Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_map",
        "wpgmp_manage_map"
    );
	
	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Create Marker Group", "wpgmp_google_map"),
        __("Create Marker Group", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_create_group_map",
        "wpgmp_create_group_map"
    );
	
	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Marker Group", "wpgmp_google_map"),
        __("Manage Marker Group", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_group_map",
        "wpgmp_manage_group_map"
    );
	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Setting", "wpgmp_google_map"),
        __("Setting", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_settings",
        "wpgmp_settings"
    );
}

/**
 * This function used to show map on front end side.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_show_location_in_map($atts, $content=null){
 ob_start();
 global $wpdb;
 	
 extract( shortcode_atts( array(
		'zoom' => get_option('wpgmp_zoomlevel'),
		'width' => get_option('wpgmp_mapwidth'),
		'height' => get_option('wpgmp_mapheight'),
		'title' => 'WP Google Map Pro',
		'class' => 'map',
		'center_latitude' => get_option('wpgmp_centerlatitude'),
		'center_longitude' => get_option('wpgmp_centerlongitude'),
		'container_id' => 'map',
		'polygon' => 'true',
		'id' => ''
 ),$atts));
	
 $icon=$atts['icon'];
 include_once dirname(__FILE__).'/class-google-map.php';
 $map = new Wpgmp_Google_Map();
  
 $map_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));
  
 $unserialize_group_map_setting = unserialize($map_data[0]->group_map_setting);
 $unserialize_map_street_view_setting = unserialize($map_data[0]->map_street_view_setting);
 $unserialize_map_route_direction_setting = unserialize($map_data[0]->map_route_direction_setting);
 $unserialize_map_control_setting = unserialize($map_data[0]->map_all_control);
 $unserialize_map_info_window_setting = unserialize($map_data[0]->map_info_window_setting);
 $unserialize_map_layer_setting = unserialize($map_data[0]->map_layer_setting);
 $unserialize_google_map_style = unserialize($map_data[0]->style_google_map);
 $unserialize_map_polygon_setting = unserialize($map_data[0]->map_polygon_setting);
 $unserialize_map_polyline_setting = unserialize($map_data[0]->map_polyline_setting);
 $unserialize_map_cluster_setting = unserialize($map_data[0]->map_cluster_setting);
 $unserialize_map_overlay_setting = unserialize($map_data[0]->map_overlay_setting);
  
 if( !empty($map_data) )
 {
	$un_loc_add = unserialize($map_data[0]->map_locations);
 	$loc_data = $wpdb->get_row($wpdb->prepare("SELECT location_address,location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$un_loc_add[0]));
 
 if( !empty($center_latitude) ) {
	$map->center_lat = $center_latitude;
 } else {
	$map->center_lat = $loc_data->location_latitude;
 }
 
 if( !empty($center_longitude) ) {
	 $map->center_lng = $center_longitude;
 } else {
	$map->center_lng = $loc_data->location_longitude;
 }
    $map->map_language=$map_data[0]->map_languages;
  
 if( $unserialize_group_map_setting['enable_group_map']=='true' ) {
	 
  	$map->enable_group_map = $unserialize_group_map_setting['enable_group_map'];
  	$select_group_map = $unserialize_group_map_setting['select_group_map'];
	foreach($select_group_map as $key => $select_group) {
			$group_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."group_map where group_map_id=%d",$select_group));
			$map->group_data[] = $group_data;
	}
 }
  
 if( !empty($unserialize_map_street_view_setting['street_control']) ) {
	 
	$map->street_control = $unserialize_map_street_view_setting['street_control'];
	$map->street_view_close_button = $unserialize_map_street_view_setting['street_view_close_button'];
	$map->links_control = $unserialize_map_street_view_setting['links_control'];
	$map->street_view_pan_control = $unserialize_map_street_view_setting['street_view_pan_control'];  
 }
  
 if( $unserialize_map_route_direction_setting['route_direction'] =='true' ) {
	 
	$map->route_direction = $unserialize_map_route_direction_setting['route_direction'];
		
	foreach($un_loc_add as $key => $un_loc_ad) {
			
		$map->map_way_point[] = $wpdb->get_row($wpdb->prepare("SELECT location_address FROM ".$wpdb->prefix."map_locations where location_id=%d",$un_loc_ad));
	}
		
	$map->route_direction_stroke_color = $unserialize_map_route_direction_setting['route_direction_stroke_color'];
	$map->route_direction_stroke_opacity = $unserialize_map_route_direction_setting['route_direction_stroke_opacity'];
	$map->route_direction_stroke_weight = $unserialize_map_route_direction_setting['route_direction_stroke_weight'];
 }
  
  if( !empty($unserialize_map_polygon_setting[draw_polygon]) && $unserialize_map_polygon_setting[draw_polygon]=='true' )
  {
	 $map->map_draw_polygon = $unserialize_map_polygon_setting[draw_polygon];
 	 $map->polygon_border_color = $unserialize_map_polygon_setting[polygon_border_color];
  	 $map->polygon_background_color = $unserialize_map_polygon_setting[polygon_background_color];
  }
  
  if( !empty($unserialize_map_polyline_setting['draw_polyline']) && $unserialize_map_polyline_setting['draw_polyline']=='true' )
  {
  	$map->map_draw_polyline=$unserialize_map_polyline_setting['draw_polyline'];
  	$map->map_polyline_stroke_color=$unserialize_map_polyline_setting['polyline_stroke_color'];
  	$map->map_polyline_stroke_opacity=$unserialize_map_polyline_setting['polyline_stroke_opacity'];
  	$map->map_polyline_stroke_weight=$unserialize_map_polyline_setting['polyline_stroke_weight'];
  }
  
  $map->map_type=$map_data[0]->map_type;
  
  if( $map_data[0]->map_45imagery=='45' && ($map_data[0]->map_type=='SATELLITE' || $map_data[0]->map_type=='HYBRID') )
  {
	 $map->map_45=$map_data[0]->map_45imagery;
  }
	
  if( empty($map_data[0]->map_width) ) {
	 $map->map_width = $width;
  } else {
	 $map->map_width = $map_data[0]->map_width;
  }	
	
  if( empty($map_data[0]->map_height) ) {
	 $map->map_height = $height;
  } else {
	 $map->map_height = $map_data[0]->map_height;
  }
  
  $map->map_scrolling_wheel =$map_data[0]->map_scrolling_wheel;
  $map->map_pan_control =$unserialize_map_control_setting['pan_control'];
  $map->map_zoom_control =$unserialize_map_control_setting['zoom_control'];
  $map->map_type_control =$unserialize_map_control_setting['map_type_control'];
  $map->map_scale_control =$unserialize_map_control_setting['scale_control'];
  $map->map_street_view_control =$unserialize_map_control_setting['street_view_control'];
  $map->map_overview_control =$unserialize_map_control_setting['overview_map_control'];
  
  if( $unserialize_map_info_window_setting['enable_info_window_setting']=="true" ) {
  
	  $map->map_enable_info_window_setting = $unserialize_map_info_window_setting['enable_info_window_setting'];
	  $map->map_info_window_width = $unserialize_map_info_window_setting['info_window_width'];
	  $map->map_info_window_height = $unserialize_map_info_window_setting['info_window_height'];
	  $map->map_info_window_shadow_style = $unserialize_map_info_window_setting['info_window_shadow_style'];
	  $map->map_info_window_border_radius = $unserialize_map_info_window_setting['info_window_border_radious'];
	  $map->map_info_window_border_width = $unserialize_map_info_window_setting['info_window_border_width'];
	  $map->map_info_window_border_color = $unserialize_map_info_window_setting['info_window_border_color'];
	  $map->map_info_window_background_color = $unserialize_map_info_window_setting['info_window_background_color'];
	  $map->map_info_window_arrow_size = $unserialize_map_info_window_setting['info_window_arrow_size'];
	  $map->map_info_window_arrow_position = $unserialize_map_info_window_setting['info_window_arrow_position'];
	  $map->map_info_window_arrow_style = $unserialize_map_info_window_setting['info_window_arrow_style'];
  }
  
  $map->map_style_google_map = unserialize($map_data[0]->style_google_map);
  $map->visualrefresh =$map_data[0]->map_visual_refresh;
  $map->map_layers=$unserialize_map_layer_setting['choose_layer'];
  $map->kml_layers_links=$unserialize_map_layer_setting['map_links'];
  $map->fusion_select=$unserialize_map_layer_setting['fusion_select'];
  $map->fusion_from=$unserialize_map_layer_setting['fusion_from'];
  $map->heat_map=$unserialize_map_layer_setting['heat_map'];;
  $map->temperature_unit=$unserialize_map_layer_setting['temp'];
  $map->wind_speed_unit=$unserialize_map_layer_setting['wind'];
	
  if( empty($map_data[0]->map_zoom_level) ) {
		$map->zoom = $zoom;
  } else {
		$map->zoom = $map_data[0]->map_zoom_level;
  }
  
  if( !empty($unserialize_map_cluster_setting['marker_cluster']) && $unserialize_map_cluster_setting['marker_cluster']=="true" )
  {
	  $map->marker_cluster=$unserialize_map_cluster_setting['marker_cluster'];
	  $map->grid=$unserialize_map_cluster_setting['grid'];
	  $map->max_zoom=$unserialize_map_cluster_setting['max_zoom'];
	  $map->style=$unserialize_map_cluster_setting['map_style'];
  }
  if( !empty($unserialize_map_overlay_setting['overlay']) && $unserialize_map_overlay_setting['overlay'] == 'true' )
  {
	  $map->map_overlay=$unserialize_map_overlay_setting['overlay'];
	  $map->map_overlay_border_color=$unserialize_map_overlay_setting['overlay_border_color'];
	  $map->map_overlay_width=$unserialize_map_overlay_setting['overlay_width'];
	  $map->map_overlay_height=$unserialize_map_overlay_setting['overlay_height'];
	  $map->map_overlay_fontsize=$unserialize_map_overlay_setting['overlay_fontsize'];
	  $map->map_overlay_border_width=$unserialize_map_overlay_setting['overlay_border_width'];
	  $map->map_overlay_border_style=$unserialize_map_overlay_setting['overlay_border_style'];
  }
}

if( !empty($atts['id']) ) {
	
$map_locations = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));
$un_group_map_setting = unserialize($map_locations->group_map_setting);
$un_info_window_setting = unserialize($map_locations->map_info_window_setting);
$un_map_polygon_setting = unserialize($map_locations->map_polygon_setting);
$un_map_polyline_setting = unserialize($map_locations->map_polyline_setting);
$un_map_cluster_setting = unserialize($map_locations->map_cluster_setting);
 
if( $un_group_map_setting['enable_group_map']=='true' ) {
	
  foreach($un_group_map_setting['select_group_map'] as $key => $select_group_map) {
		
	$all_group_data_markers = $wpdb->get_results($wpdb->prepare("SELECT ml.*,gm.* FROM ".$wpdb->prefix."map_locations as ml INNER JOIN ".$wpdb->prefix."group_map  as gm ON ml.location_group_map=gm.group_map_id where ml.location_group_map=%d",$select_group_map));

	foreach($all_group_data_markers as $key => $group_data_markers) {
	
	$map->addmarker($group_data_markers->location_latitude,$group_data_markers->location_longitude,$un_info_window_setting['info_window'],$title,$group_data_markers->location_address,$group_data_markers->group_marker,'',$dragg,$animation,$group_data_markers->group_map_id);
	
	}
  }
} elseif( get_option('wpgmp_mashup')=='true' ) {
	
  $mashup_posts =  new wp_query(array('meta_key' => 'wpgmp_mashup_map_id', 'meta_value' => $atts['id']));
  if($mashup_posts->have_posts()) {
	 add_filter( 'excerpt_more', 'wpgmp_excerpt_more' );
	 while($mashup_posts->have_posts()) : $mashup_posts->the_post();
	 $mashup_content = get_the_mashup_content();
	 $mashup_content = str_replace('"',"'",$mashup_content);
	 $loc_id = get_post_meta(get_the_ID(), 'wpgmp_mashup_location_id', true);
	 $loc_list = $wpdb->get_row($wpdb->prepare('select location_latitude, location_longitude,location_marker_image from '.$wpdb->prefix.'map_locations where location_id=%d',$loc_id));
	
	 if( empty($loc_list->location_marker_image) ) {
		
		$loc_mashup_image_src = get_option('wpgmp_default_marker');
	 } else {
		$loc_mashup_image_src = $loc_list->location_marker_image;
	 }		
		$map->addMarker($loc_list->location_latitude,$loc_list->location_longitude,'true',get_the_title(),$mashup_content,$loc_mashup_image_src,'',$dragg,$animation);
		endwhile;
  }
} elseif( $un_map_polygon_setting['draw_polygon']=='true' ) {
	
	$map_locs = unserialize($map_locations->map_locations);
	if($map_locs!='') {
	  foreach($map_locs as $map_loc) {
		$lat_lat = $wpdb->get_row($wpdb->prepare("SELECT location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_loc));
		$latitude = $lat_lat->location_latitude;
		$longitude = $lat_lat->location_longitude;
		$map->addPolygon($latitude,$longitude);
	  }
    }	
} elseif( $un_map_polyline_setting['draw_polyline']=='true' ) {
	
	$map_locs = unserialize($map_locations->map_locations);
	if($map_locs!='') {
	  foreach($map_locs as $map_loc) {
		$lat_lat = $wpdb->get_row($wpdb->prepare("SELECT location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_loc));
		$latitude = $lat_lat->location_latitude;
		$longitude = $lat_lat->location_longitude;
		$map->addpolyline($latitude,$longitude);
	  }
    }	  
} else {
	
   	$map_address = unserialize($map_locations->map_locations);
	
	if( $map_address!='' ) {
		$address[] = array();
		
   	foreach($map_address as $map_ad) {
   	
	$map_locations_records = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_ad));
    
	$group_marker = $wpdb->get_row($wpdb->prepare("SELECT group_marker FROM ".$wpdb->prefix."group_map where group_map_id=%d",$map_locations_records->location_group_map));
	
	$wpgm_marker =  get_option('wpgmp_default_marker');
	
	$unmess_info_message = unserialize(base64_decode($map_locations_records->location_messages));
	
	$loc_image_src = '';
	
	if( !empty($group_marker->group_marker) ) {
		$loc_image_src = $group_marker->group_marker;
	} elseif( !empty($map_locations_records->location_marker_image) ) {
		$loc_image_src = $map_locations_records->location_marker_image;
	} elseif( !empty($wpgm_marker) ) {
		$loc_image_src = $wpgm_marker;
	}
	
	$latitude = $map_locations_records->location_latitude;
	$longitude = $map_locations_records->location_longitude;
	$title = $map_locations_records->location_title;
	$dragg = $map_locations_records->location_draggable;
	$animation = $map_locations_records->location_animation;
	$address['first']['title'] = $unmess_info_message['googlemap_infowindow_title_one'];
	$address['first']['message'] = $unmess_info_message['googlemap_infowindow_message_one'];
	$address['second']['title'] = $unmess_info_message['googlemap_infowindow_title_two'];
	$address['second']['message'] = $unmess_info_message['googlemap_infowindow_message_two'];
	$address['third']['title'] = $unmess_info_message['googlemap_infowindow_title_three'];
	$address['third']['message'] = $unmess_info_message['googlemap_infowindow_message_three'];
	$address['fourth']['title'] = $unmess_info_message['googlemap_infowindow_title_four'];
	$address['fourth']['message'] = $unmess_info_message['googlemap_infowindow_message_four'];
	$address['fifth']['title'] = $unmess_info_message['googlemap_infowindow_title_five'];
	$address['fifth']['message'] = $unmess_info_message['googlemap_infowindow_message_five'];
	$address = array_filter($address);
	
	if( $un_map_cluster_setting['marker_cluster']=='true' ){
		
		$map->addMarkerCluster($latitude,$longitude,'true',$title,$address,'',$dragg,$animation);
	} else {
		
		if( $address['first']['title']!='' || $address['first']['message']!='' || $address['second']['title']!='' || $address['second']['message']!='' || $address['third']['title']!='' || $address['third']['message']!='' || $address['fourth']['title']!='' || $address['fourth']['title']!='' || $address['fifth']['title']!='' || $address['fifth']['message']!='' ) {
			
			$map->addMarker($latitude,$longitude,$un_info_window_setting['info_window'],$title,$address,$loc_image_src,'',$dragg,$animation);
		} else {
			
			wp_print_scripts( 'wpgmp_map' );
			
			$new_loc_adds = array();
			
			$new_loc_adds = $map_locations_records->location_address;
			
			$address_coordinates = wpgmp_get_address_coordinates( $new_loc_adds );
			
			$map->addMarker($address_coordinates['lat'],$address_coordinates['lng'],$un_info_window_setting['info_window'],$title,$address_coordinates['address'],$loc_image_src,'',$dragg,$animation);
				
	    }
	}
   }
  }
 }
} elseif( $content ) {
	wp_print_scripts( 'wpgmp_map' );
	if( empty($zoom) || empty($width) || empty($height) || empty($title) ) {
		$map->zoom = 14;
		$map->width = '600';
		$map->height = '400';
		$map->title = 'WP Google Map Pro';
	} else {
		$map->zoom = $zoom;
		$map->width = $width;
		$map->height = $height;
		$map->title = $title;
	}
	
	$address = '';
	$coordinates = wpgmp_get_coordinates( $content );
	$address = '<h3>'.$coordinates["address"].'</h3>';
	$address .= '<p>Latitude='.$coordinates["lat"].'</p>';
	$address .= '<p>Longitude='.$coordinates["lng"].'</p>';
	$map->center_lat = $coordinates['lat'];
	$map->center_lng = $coordinates['lng'];
	$map->addMarker($map->center_lat,$map->center_lng,'true',$map->title,$address);
 
   if( !is_array( $coordinates ) )
   return;
} else {
	
	return "Thank you for using this plugin. Please <a href='".admin_url('admin.php?page=wpgmp_add_location')."'>Add your locations</a> or set plugin <a href='".admin_url('admin.php?page=wpgmp_google_settings')."'>Settings</a>.";
}
	
 echo $map->showmap();
 $content =  ob_get_contents();
 ob_clean();
 
 return $content;
}

/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_settings(){
?>
<div class="wrap">  
<div id="icon-options-general" class="icon32"><br></div><h2><?php _e( 'Google WP Map Pro Settings', 'wpgmp_google_map' ) ?></h2>
        <form method="post" action="options.php">  
            <?php wp_nonce_field('update-options') ?>  
      <p>
<a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=wpgmp_add_location"><?php _e( 'Click Here', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'to add a new location or', 'wpgmp_google_map' ) ?>&nbsp;<a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=location"><?php _e( 'Browse', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'your existings locations.', 'wpgmp_google_map' ) ?>
 </p>
       
      <table class="form-table">
<tbody>    
            <tr valign="top">
<th scope="row"><label for="wpgmp_zoomlevel"><?php _e( 'Zoom Level', 'wpgmp_google_map' ) ?></label></th>
<td><input type="text" name="wpgmp_zoomlevel" size="45" value="<?php echo get_option('wpgmp_zoomlevel'); ?>" />
<p class="description"><?php _e( 'Choose Zoom Level between 1 to 14. Default is 4.', 'wpgmp_google_map' ) ?> </p></td>
</tr>
<tr valign="top">
<th scope="row"><label for="wpgmp_centerlatitude"><?php _e( 'Center Latitude', 'wpgmp_google_map' ) ?></label></th>
<td><input type="text" name="wpgmp_centerlatitude" size="45" value="<?php echo get_option('wpgmp_centerlatitude'); ?>" />
<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p></td>
</tr>
<tr valign="top">
<th scope="row"><label for="wpgmp_centerlongitude"><?php _e( 'Center Longitude', 'wpgmp_google_map' ) ?></label></th>
<td><input type="text" name="wpgmp_centerlongitude" size="45" value="<?php echo get_option('wpgmp_centerlongitude'); ?>" />
<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p></td>
</tr>
<tr valign="top">
<th scope="row"><label for="wpgmp_language"><?php _e( 'Select Language', 'wpgmp_google_map' ) ?></label>
</th>
<td>
<select name="wpgmp_language">
 <option value="en"<?php selected(get_option('wpgmp_language'),'en') ?>><?php _e( 'ENGLISH', 'wpgmp_google_map' ) ?></option>
 <option value="ar"<?php selected(get_option('wpgmp_language'),'ar') ?>><?php _e( 'ARABIC', 'wpgmp_google_map' ) ?></option>
 <option value="eu"<?php selected(get_option('wpgmp_language'),'eu') ?>><?php _e( 'BASQUE', 'wpgmp_google_map' ) ?></option>
 <option value="bg"<?php selected(get_option('wpgmp_language'),'bg') ?>><?php _e( 'BULGARIAN', 'wpgmp_google_map' ) ?></option>
 <option value="bn"<?php selected(get_option('wpgmp_language'),'bn') ?>><?php _e( 'BENGALI', 'wpgmp_google_map' ) ?></option>
 <option value="ca"<?php selected(get_option('wpgmp_language'),'ca') ?>><?php _e( 'CATALAN', 'wpgmp_google_map' ) ?></option>
 <option value="cs"<?php selected(get_option('wpgmp_language'),'cs') ?>><?php _e( 'CZECH', 'wpgmp_google_map' ) ?></option>
 <option value="da"<?php selected(get_option('wpgmp_language'),'da') ?>><?php _e( 'DANISH', 'wpgmp_google_map' ) ?></option>
 <option value="de"<?php selected(get_option('wpgmp_language'),'de') ?>><?php _e( 'GERMAN', 'wpgmp_google_map' ) ?></option>
 <option value="el"<?php selected(get_option('wpgmp_language'),'el') ?>><?php _e( 'GREEK', 'wpgmp_google_map' ) ?></option>
 <option value="en-AU"<?php selected(get_option('wpgmp_language'),'en-AU') ?>><?php _e( 'ENGLISH (AUSTRALIAN)', 'wpgmp_google_map' ) ?></option>
 <option value="en-GB"<?php selected(get_option('wpgmp_language'),'en-GB') ?>><?php _e( 'ENGLISH (GREAT BRITAIN)', 'wpgmp_google_map' ) ?></option>
 <option value="es"<?php selected(get_option('wpgmp_language'),'es') ?>><?php _e( 'SPANISH', 'wpgmp_google_map' ) ?></option>
 <option value="fa"<?php selected(get_option('wpgmp_language'),'fa') ?>><?php _e( 'FARSI', 'wpgmp_google_map' ) ?></option>
 <option value="fi"<?php selected(get_option('wpgmp_language'),'fi') ?>><?php _e( 'FINNISH', 'wpgmp_google_map' ) ?></option>
 <option value="fil"<?php selected(get_option('wpgmp_language'),'fil') ?>><?php _e( 'FILIPINO', 'wpgmp_google_map' ) ?></option>
 <option value="fr"<?php selected(get_option('wpgmp_language'),'fr') ?>><?php _e( 'FRENCH', 'wpgmp_google_map' ) ?></option>
 <option value="gl"<?php selected(get_option('wpgmp_language'),'gl') ?>><?php _e( 'GALICIAN', 'wpgmp_google_map' ) ?></option>
 <option value="gu"<?php selected(get_option('wpgmp_language'),'gu') ?>><?php _e( 'GUJARATI', 'wpgmp_google_map' ) ?></option>
 <option value="hi"<?php selected(get_option('wpgmp_language'),'hi') ?>><?php _e( 'HINDI', 'wpgmp_google_map' ) ?></option>
 <option value="hr"<?php selected(get_option('wpgmp_language'),'hr') ?>><?php _e( 'CROATIAN', 'wpgmp_google_map' ) ?></option>
 <option value="hu"<?php selected(get_option('wpgmp_language'),'hu') ?>><?php _e( 'HUNGARIAN', 'wpgmp_google_map' ) ?></option>
 <option value="id"<?php selected(get_option('wpgmp_language'),'id') ?>><?php _e( 'INDONESIAN', 'wpgmp_google_map' ) ?></option>
 <option value="it"<?php selected(get_option('wpgmp_language'),'it') ?>><?php _e( 'ITALIAN', 'wpgmp_google_map' ) ?></option>
 <option value="iw"<?php selected(get_option('wpgmp_language'),'iw') ?>><?php _e( 'HEBREW', 'wpgmp_google_map' ) ?></option>
 <option value="ja"<?php selected(get_option('wpgmp_language'),'ja') ?>><?php _e( 'JAPANESE', 'wpgmp_google_map' ) ?></option>
 <option value="kn"<?php selected(get_option('wpgmp_language'),'kn') ?>><?php _e( 'KANNADA', 'wpgmp_google_map' ) ?></option>
 <option value="ko"<?php selected(get_option('wpgmp_language'),'ko') ?>><?php _e( 'KOREAN', 'wpgmp_google_map' ) ?></option>
 <option value="lt"<?php selected(get_option('wpgmp_language'),'lt') ?>><?php _e( 'LITHUANIAN', 'wpgmp_google_map' ) ?></option>
 <option value="lv"<?php selected(get_option('wpgmp_language'),'lv') ?>><?php _e( 'LATVIAN', 'wpgmp_google_map' ) ?></option>
 <option value="ml"<?php selected(get_option('wpgmp_language'),'ml') ?>><?php _e( 'MALAYALAM', 'wpgmp_google_map' ) ?></option>
 <option value="mr"<?php selected(get_option('wpgmp_language'),'mr') ?>><?php _e( 'MARATHI', 'wpgmp_google_map' ) ?></option>
 <option value="nl"<?php selected(get_option('wpgmp_language'),'nl') ?>><?php _e( 'DUTCH', 'wpgmp_google_map' ) ?></option>
 <option value="no"<?php selected(get_option('wpgmp_language'),'no') ?>><?php _e( 'NORWEGIAN', 'wpgmp_google_map' ) ?></option>
 <option value="pl"<?php selected(get_option('wpgmp_language'),'pl') ?>><?php _e( 'POLISH', 'wpgmp_google_map' ) ?></option>
 <option value="pt"<?php selected(get_option('wpgmp_language'),'pt') ?>><?php _e( 'PORTUGUESE', 'wpgmp_google_map' ) ?></option>
 <option value="pt-BR"<?php selected(get_option('wpgmp_language'),'pt-BR') ?>><?php _e( 'PORTUGUESE (BRAZIL)', 'wpgmp_google_map' ) ?></option>
 <option value="pt-PT"<?php selected(get_option('wpgmp_language'),'pt-PT') ?>><?php _e( 'PORTUGUESE (PORTUGAL)', 'wpgmp_google_map' ) ?></option>
 <option value="ro"<?php selected(get_option('wpgmp_language'),'ro') ?>><?php _e( 'ROMANIAN', 'wpgmp_google_map' ) ?></option>
 <option value="ru"<?php selected(get_option('wpgmp_language'),'ru') ?>><?php _e( 'RUSSIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sk"<?php selected(get_option('wpgmp_language'),'sk') ?>><?php _e( 'SLOVAK', 'wpgmp_google_map' ) ?></option>
 <option value="sl"<?php selected(get_option('wpgmp_language'),'sl') ?>><?php _e( 'SLOVENIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sr"<?php selected(get_option('wpgmp_language'),'sr') ?>><?php _e( 'SERBIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sv"<?php selected(get_option('wpgmp_language'),'sv') ?>><?php _e( 'SWEDISH', 'wpgmp_google_map' ) ?></option>
 <option value="tl"<?php selected(get_option('wpgmp_language'),'tl') ?>><?php _e( 'TAGALOG', 'wpgmp_google_map' ) ?></option>
 <option value="ta"<?php selected(get_option('wpgmp_language'),'ta') ?>><?php _e( 'TAMIL', 'wpgmp_google_map' ) ?></option>
 <option value="te"<?php selected(get_option('wpgmp_language'),'te') ?>><?php _e( 'TELUGU', 'wpgmp_google_map' ) ?></option>
 <option value="th"<?php selected(get_option('wpgmp_language'),'th') ?>><?php _e( 'THAI', 'wpgmp_google_map' ) ?></option>
 <option value="tr"<?php selected(get_option('wpgmp_language'),'tr') ?>><?php _e( 'TURKISH', 'wpgmp_google_map' ) ?></option>
 <option value="uk"<?php selected(get_option('wpgmp_language'),'uk') ?>><?php _e( 'UKRAINIAN', 'wpgmp_google_map' ) ?></option>
 <option value="vi"<?php selected(get_option('wpgmp_language'),'vi') ?>><?php _e( 'VIETNAMESE', 'wpgmp_google_map' ) ?></option>
 <option value="zh-CN"<?php selected(get_option('wpgmp_language'),'zh-CN') ?>><?php _e( 'CHINESE (SIMPLIFIED)', 'wpgmp_google_map' ) ?></option>
 <option value="zh-TW"<?php selected(get_option('wpgmp_language'),'zh-TW') ?>><?php _e( 'CHINESE (TRADITIONAL)', 'wpgmp_google_map' ) ?></option>
 </select>
<p class="description"><?php _e( 'Default is english.', 'wpgmp_google_map' ) ?></p>
</td>
</tr>
</tbody>
   
</table>        
<input type="hidden" name="action" value="update" />  
<input type="hidden" name="page_options" value="wpgmp_zoomlevel,wpgmp_centerlatitude,wpgmp_centerlongitude,wpgmp_mapwidth,wpgmp_mapheight,wpgmp_language,wpgmp_default_marker,wpgmp_mashup" />  
     
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'wpgmp_google_map' ) ?>"></p>
 		   </form> 
 <p>
 
</fieldset>
 </p>
    </div>  
<?php
}
/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_widget(){
	register_widget('wpgmp_google_map_widget');
}
/**
 * This class used to add widget support in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
class wpgmp_google_map_widget extends WP_Widget{
	public function __construct()
	{
		parent::__construct(
			'wpgmp_google_map_widget',
			'WP Google Map Pro',
			array('description' => __('A widget that displays the google map' , 'wpgmp_google_map'))
		);
	}
	function widget( $args, $instance )
	{
		global $wpdb;
		extract($args);
		$title=$instance['title'];
		$map_title = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."create_map where map_id='".$title."'");
		
		if($title)
			echo $before_title . $map_title->map_title . $after_title;
		
		echo do_shortcode('[put_wpgm id='.$title.']' );
	
	}
	function update( $new_instance, $old_instance )
	{
		$instance=$old_instance;
		$instance['title']=strip_tags($new_instance['title']);
		update_option('wpgmp_short_mapselect_marker' , $mark);
		return $instance;
	}
	function form( $instance )
	{
	
	global $wpdb;
	$map_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map",NULL));
	?>
	
		<p>
			<label for="<?php echo $this->get_field_id('title');?>" style="font-weight:bold;"><?php _e('Select Your Map:' , 'wpgmp_google_map');?>
			</label> 
				<select id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" style="width:80%;">
                <option value=""><?php _e( 'Select map', 'wpgmp_google_map' ) ?></option>
				<?php foreach($map_records as $key => $map_record){  ?>
 				<option value="<?php echo $map_record->map_id; ?>"<?php selected($map_record->map_id,$instance['title']); ?>><?php echo $map_record->map_title; ?></option>
				<?php } ?>	
				</select>
        </p>        
	<?php	
	}
}
/**
 * This function used to register google map script.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_scripts_method(){
    wp_enqueue_script('wpgmp_map','http://www.google.com/jsapi');
}
/**
 * This function used to enable marker clusters.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_markercluster_script(){
	wp_enqueue_script('wpgmp_markercluster_script',plugins_url('/js/markerclusterer.js', __FILE__));
	wp_enqueue_script('wpgmp_markercluster_script2',plugins_url('/data.json', __FILE__));
}
/**
 * This function used to display multiple info windows.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_info_bubble_script(){
	wp_enqueue_script('wpgmp_info_bubble_script',plugins_url('/js/infobubble.js', __FILE__));
	
	wp_enqueue_script('wpgmp_info_bubble_script3',plugins_url('/js/jscolor.js', __FILE__ ));
}
/**
 * This function used to load css in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_load(){
wp_enqueue_style(
		'google_map_css',
		plugins_url( '/css/google-map.css' , __FILE__ ));
}
  
function wpgmp_excerpt_more(){
 return '<br /><a class="read-more" href="'. get_permalink( get_the_ID() ) . '" target="_blank">Read More</a>';
}
function get_the_mashup_content(){
  $msg_str = '<div style="width:400px; height:300px;">';
  $msg_str .= '<a href="'.get_permalink(get_the_ID()).'" target="_blank">'.get_the_title().'</a><br />';
  
  if(has_post_thumbnail()){
   $attach_id = get_post_thumbnail_id();
   $thumb = wp_get_attachment_image_src( $attach_id, 'thumbnail');
   $msg_str .= '<br /><a href="'.get_permalink(get_the_ID()).'" target="_blank"><img src="'.$thumb[0].'" style="float:left; margin-right:20px; margin-top:7px;" /></a>';
  }
  
  $msg_str .= get_the_excerpt();
  $msg_str .= '</div>';
  return $msg_str;
}
function wpgmp_mashup_meta_boxes(){
  
  add_meta_box('wpgmp_mashup_meta_box', 'Google Map Mashup', 'show_gmap_metabox_content', 'post');
}
function show_gmap_metabox_content($post){
  global $wpdb;
 
  $locations =  $wpdb->get_results($wpdb->prepare('SELECT location_title,location_address,location_id FROM '.$wpdb->prefix.'map_locations',NULL));
  
  $maps =  $wpdb->get_results($wpdb->prepare('SELECT map_id, map_title FROM '.$wpdb->prefix.'create_map',NULL));
 
  $location_id = get_post_meta($post->ID, 'wpgmp_mashup_location_id', true);
  $map_id = get_post_meta($post->ID, 'wpgmp_mashup_map_id', true);
  
  wp_nonce_field( plugin_basename( __FILE__ ), 'wpgmp_mashup_nonce' );
?>  
<div style="margin:20px 20px 0 20px;">
<?php if( $locations ): ?>
   <label><?php _e( 'Select Location:', 'wpgmp_google_map' ) ?></label>
   <select name="gmap_mashup_location_id" style="margin-left:20px;">
   		<option value=""></option>
   <?php foreach($locations as $location): ?>
 		<option value="<?php echo $location->location_id ?>" <?php selected($location->location_id,$location_id) ?>><?php echo $location->location_title?>&nbsp;(<?php echo $location->location_address; ?>)</option>
   <?php endforeach; ?>  
   </select>
<?php endif; ?> 
<br />   
<?php if( $maps ): ?>
    <label><?php _e( 'Select Map:', 'wpgmp_google_map' ) ?></label>
    <select name="gmap_mashup_map_id" style="margin-left:45px;">
 		<option value=""></option>
 	<?php foreach($maps as $map): ?>
 		<option value="<?php echo $map->map_id ?>" <?php selected($map->map_id,$map_id) ?>><?php echo $map->map_title; ?></option>
     <?php endforeach; ?>  
 	</select>
<?php endif; ?>  
 </div>
<?php  
}
function wpgmp_mashup_save_post($post_id){
  
  if( $_REQUEST['post_type'] == 'post' ){
    
 if( !current_user_can('edit_post',$post_id) )
 return;
    
 if( !isset($_POST['wpgmp_mashup_nonce']) || !wp_verify_nonce($_POST['wpgmp_mashup_nonce'],plugin_basename(__FILE__)) )
 return;
 
 
 update_post_meta($post_id, 'wpgmp_mashup_location_id', $_POST['gmap_mashup_location_id']);
 update_post_meta($post_id, 'wpgmp_mashup_map_id', $_POST['gmap_mashup_map_id']);
  }
}
 
 
 
/**
 * This function used to create tab.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_tabs_filter($tabs)
{
        $newtab = array('ell_insert_gmap_tab' => __('Choose Icons','wpgmp_google_map'));
        return array_merge($tabs,$newtab);
}
 
function wpgmp_google_map_media_upload_tab() {
	
    return wp_iframe('wpgmp_google_map_icon', $errors );
}
function wpgmp_google_map_icon()
{
echo media_upload_header();
$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=ell_insert_gmap_tab", 'admin');
?>
<script type="text/javascript">
 function add_icon_to_images()
 {
	  if(jQuery('.read_icons').hasClass('active'))
	  {	  
	  	imgsrc = jQuery('.active').find('img').attr('src');
   		
		var win = window.dialogArguments || opener || parent || top;
   		
		win.send_icon_to_map(imgsrc);
  		
	  }
	  else
	  {
   		alert('Choose your icon.');
  	  }
 }
</script>
<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form" id="library-form">
<h3 class="media-title" style="color: #5A5A5A; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: normal; font-size: 1.6em; margin-left: 10px;"><?php _e( 'Select Icons', 'wpgmp_google_map' ) ?></h3>
<div style="margin-bottom:30px; float:left;">
<ul style="margin-left:10px; float:left;" id="select_icons">
<?php
$dir = plugin_dir_path( __FILE__ ) . 'icons/';
if ( is_dir($dir)  )
{
  if ( $dh = opendir($dir) )
  {
    while (($file = readdir($dh)) !== false)
	{
?>	
<li class="read_icons" style="float:left;">	
      <img src="<?php echo plugins_url('/icons/'.$file.'', __FILE__ ); ?>" style="cursor:pointer;" />
</li>
<?php
    }
?>
<?php
    closedir($dh);
  }
}
?>
</ul>
<button type="button" class="button" style="margin-left:10px;" value="1" onclick="add_icon_to_images();" name="send[<?php echo $picid ?>]"><?php _e( 'Insert into Post', 'wpgmp_google_map' ) ?></button>
</div>
</form>
<?php
}  
 
/**
 * This function used to registered all action.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
  
 
 
function wpgmp_load_actions()
{
wpgmp_google_map_load();
wpgmp_markercluster_script();
wpgmp_info_bubble_script();
wpgmp_scripts_method();
add_action('save_post', 'wpgmp_mashup_save_post');
add_action('add_meta_boxes', 'wpgmp_mashup_meta_boxes');
add_action('media_upload_ell_insert_gmap_tab', 'wpgmp_google_map_media_upload_tab');
add_filter('media_upload_tabs', 'wpgmp_google_map_tabs_filter');
add_action('admin_menu', 'wpgmp_google_map_page');
add_shortcode('put_wpgm','wpgmp_show_location_in_map');
add_shortcode('display_map','wpgmp_display_map');
add_action('admin_print_scripts', 'wpgmp_admin_scripts');
add_action('admin_print_styles', 'wpgmp_admin_styles');
add_action('admin_head', 'wpgmp_js_head');
}
add_action('widgets_init' , 'wpgmp_google_map_widget');
add_action('init', 'wpgmp_load_actions');
include_once("wpgmp-all-js.php");
include_once("wpgmp-add-location.php");
include_once("wpgmp-manage-location.php");
include_once("wpgmp-create-map.php");
include_once("wpgmp-manage-map.php");
include_once("wpgmp-create-group-map.php");
include_once("wpgmp-manage-group-map.php");
include_once("wpgmp-display-map.php");

/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_showMessage($message, $errormsg = false)
{
	if( empty($message) )
	return;
	
	if ( $errormsg ) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}
	echo "<p><strong>$message</strong></p></div>";
} 
/**
 * This function used to show basic instruction for how to use this plugin.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_admin_overview()  {
	?>
	<div class="wrap wpgmp-wrap">
		<h2><?php _e('How to Use', 'wpgmp_google_map') ?></h2>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('To create your first map. Please go with following steps.', 'wpgmp_google_map') ?>
							<p>
							
							<b><?php _e('Step 1', 'wpgmp_google_map') ?></b> - <?php _e('First use our auto suggession enabled location box to add your location', 'wpgmp_google_map') ?><a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"><?php _e('Here', 'wpgmp_google_map') ?></a>.<?php _e('You may add as many as locations you want to add. All those locations will be available to choose when you will create your map.', 'wpgmp_google_map') ?> </li>
							
							</p>
							<p>
							<b><?php _e('Step 2', 'wpgmp_google_map') ?></b> - <?php _e('Now', 'wpgmp_google_map') ?> <a href="<?php echo admin_url('admin.php?page=wpgmp_create_map') ?>"><?php _e('Click Here', 'wpgmp_google_map') ?></a><?php _e('to create your map. You may create as many as maps you want to add. Using shortcode, you can add maps on posts/pages.', 'wpgmp_google_map') ?> </li>
							</p>
							<p>
							<b><?php _e('Step 3', 'wpgmp_google_map') ?></b> - <?php _e('Once you have done administrative tasks, you can display map on posts/pages using', 'wpgmp_google_map') ?> <a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_manage_map') ?>"><?php _e('Shortcode', 'wpgmp_google_map') ?></a><?php _e('and in sidebar, using widgets section', 'wpgmp_google_map') ?> .</li>
							</p>
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
		
		<div style="clear:both"></div>
			<h2><?php _e('Online Documentation', 'wpgmp_google_map') ?></h2>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('You can find a documentation with zip package your purchases or visit', 'wpgmp_google_map') ?> <a href="http://www.flippercode.com" target="_blank"><?php _e('Online Documentation', 'wpgmp_google_map') ?></a> on <a href="http://www.flippercode.com" target="_blank"><?php _e('Our Website', 'wpgmp_google_map') ?></a>
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
	</div>
	
	<?php
}
function wpgmp_is_mobile() {
	        static $is_mobile;
	
	        if ( isset($is_mobile) )
	                return $is_mobile;
	
	        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
	                $is_mobile = false;
	        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
	                        $is_mobile = true;
	        } else {
	                $is_mobile = false;
	        }
	
	        return $is_mobile;
}
function wpgmp_get_coordinates( $content, $force_refresh = false ) {
    $address_hash = md5( $content );
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $content ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
function wpgmp_get_address_coordinates( $new_loc_add, $force_refresh = false ) {
    $address_hash = md5( $new_loc_add );
	
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $new_loc_add ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
