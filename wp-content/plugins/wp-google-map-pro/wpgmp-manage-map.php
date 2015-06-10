<?php
/**
 * This class used to display list of added maps in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
class Wpgmp_Maps_Table extends WP_List_Table {
var $table_data;
function __construct(){
global $status, $page,$wpdb;
parent::__construct( array(
  'singular'  => __( 'googlemap', 'wpgmp_google_map' ),    
  'plural'    => __( 'googlemaps', 'wpgmp_google_map' ),  
  'ajax'      => false       
    ) );
if( $_GET['page']=='wpgmp_google_wpgmp_manage_map' && $_POST['s']!='' )
{
$query = "SELECT * FROM ".$wpdb->prefix."create_map WHERE map_title LIKE '%".$_POST['s']."%' OR  map_type LIKE '%".$_POST['s']."%' OR map_width LIKE '%".$_POST['s']."%' OR map_height LIKE '%".$_POST['s']."%' ";
}
else
{
$query = "SELECT * FROM ".$wpdb->prefix."create_map ORDER BY map_id DESC";
}
$this->table_data = $wpdb->get_results($wpdb->prepare($query,NULL),ARRAY_A );
add_action( 'admin_head', array( &$this, 'admin_header' ) );            
}
function admin_header()
{
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'location' != $page )
    return;
    echo '<style type="text/css">';
	echo '.wp-list-table .column-map_title  { width: 20%; }';
	echo '.wp-list-table .column-map_width { width: 20%;}';
	echo '.wp-list-table .column-map_height { width: 20%; }';
	echo '.wp-list-table .column-map_zoom_level { width: 20%; }';
	echo '.wp-list-table .column-map_type  { width: 20%;}';
	echo '.wp-list-table .column-shortcodes  { width: 20%;}';
	
    echo '</style>';
}
function no_items()
{
    _e( 'No Records for Maps.' ,'wpgmp_google_map');
}
function column_default( $item, $column_name )
{
switch( $column_name )
{
case 'map_title': 
case 'map_width':
case 'map_height':
case 'map_zoom_level': 
case 'map_type':
case 'shortcodes':
return $this->custom_column_value($column_name,$item);
default:
return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
}
}
function custom_column_value($column_name,$item)
{
	if($column_name=='post_title ')
	return "<a href='".get_permalink( $item[ 'post_id' ] )."'>".$item[ $column_name ]."</a>";
	elseif($column_name=='user_login')
	return "<a href='".get_author_posts_url($item[ 'user_id' ])."'>".$item[ $column_name ]."</a>";
	else
	return $item[ $column_name ];
}
function get_sortable_columns()
{
  $sortable_columns = array(
    'map_title'   => array('map_title',false),
	'map_width'   => array('map_width',false),
	'map_height'   => array('map_height',false),
	'map_zoom_level'   => array('map_zoom_level',false),
	'map_type'   => array('map_type',false),
	'shortcodes'   => array('shortcodes',false),
	
  );
  return $sortable_columns;
}
function get_columns()
{
	$columns = array(
	
	'cb'        => '<input type="checkbox" />',
	
	'map_title'      => __( 'Title', 'wpgmp_google_map' ),
					
	'map_width'      => __( 'Map Width', 'wpgmp_google_map' ),
	
	'map_height'      => __( 'Map Height', 'wpgmp_google_map' ),
	
	'map_zoom_level'      => __( 'Map Zoom Level', 'wpgmp_google_map' ),
	
	'map_type'      => __( 'Map Type', 'wpgmp_google_map' ),
	
	'shortcodes'      => __( 'Shortcodes', 'wpgmp_google_map' ),
	
	);
         return $columns;
}
function usort_reorder( $a, $b )
{
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : '';
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  $result = strcmp( $a[$orderby], $b[$orderby] );
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_map_title($item)
{
$actions = array(
		'edit'      => sprintf('<a href="?page=%s&action=%s&map=%s">Edit</a>',$_REQUEST['page'],'edit',$item['map_id']),
		'delete'    => sprintf('<a href="?page=%s&action=%s&map=%s">Delete</a>',$_REQUEST['page'],'delete',$item['map_id']),
	);
  return sprintf('%1$s %2$s', $item['map_title'], $this->row_actions($actions) );
}
function get_bulk_actions()
{
$actions = array(
'delete'    => 'Delete',
);
return $actions;
}
function column_cb($item)
{
	return sprintf(
		'<input type="checkbox" name="map[]" value="%s" />', $item['map_id']
	);
}
function column_shortcodes($item)
{
	return sprintf(
		'[put_wpgm id='.$item['map_id'].']'
	);
}
function prepare_items()
{
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  usort( $this->table_data, array( &$this, 'usort_reorder' ) );
  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count( $this->table_data );
  $this->found_data = array_slice( $this->table_data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $this->found_data;
}
}
/**
 * This function used to edit map using manage maps page.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_manage_map()
{
global $wpdb; 
if($_GET['action']=='delete' && $_GET['map']!='')
{
	$id = (int)$_GET['map'];
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."create_map WHERE map_id=%d",$id));
	$success= __( 'Selected Records Deleted Successfully.', 'wpgmp_google_map' );
}
if( $_POST['action'] == 'delete' && $_POST['map']!='' )
{
	foreach($_POST['map'] as $id)
		{
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."create_map WHERE map_id=%d",$id));
		}
	$success= __( 'Selected Records Deleted Successfully.', 'wpgmp_google_map' );
}
if( isset($_POST['update_map']) && $_POST['update_map']=='Update Map' )
{
	
global $wpdb;
if( $_POST['map_title']=="" )
{
   $error[]= __( 'Please enter title.', 'wpgmp_google_map' );
}
if( !intval($_POST['map_width']) && $_POST['map_width']!='' )
{
	$error[]= __( 'Please enter Integer value in map width.', 'wpgmp_google_map' );
}
if( $_POST['map_height']=='' )
{
	$error[]= __( 'Please enter map height.', 'wpgmp_google_map' );
}
else if( !intval($_POST['map_height']) )
{
	$error[]= __( 'Please enter Integer value in map height.', 'wpgmp_google_map' );
}
if( $_POST['direction_servics']['route_direction']=="" )
{
	$_POST['direction_servics']['route_direction'] = 'false';
	$_POST['direction_servics']['route_direction_stroke_color'] = "#0000FF";
	$_POST['direction_servics']['route_direction_stroke_opacity'] = 1.0;
	$_POST['direction_servics']['route_direction_stroke_weight'] = 2;
}
else
{
	if(count($_POST['locations'])<2)
	{
		$error[]= __( 'please add two locations for route directions.', 'wpgmp_google_map' );
	}
	else
	{
		$_POST['direction_servics']['route_direction'] = $_POST['direction_servics']['route_direction'];
	}
}
if( $_POST['scrolling_wheel']=="" )
{
	$_POST['scrolling_wheel'] = 'true';
}
else
{
	$_POST['scrolling_wheel'] = $_POST['scrolling_wheel'];
}
if( $_POST['visual_refresh']=="" )
{
    $_POST['visual_refresh'] = 'false';
}
else
{
	$_POST['visual_refresh'] = $_POST['visual_refresh'];
}
if( $_POST['street_view_control']['street_control']=="" )
{
   $_POST['street_view_control']['street_control'] = 'false';
}
else
{
	$_POST['street_view_control']['street_control'] = $_POST['street_view_control']['street_control'];
}
if( $_POST['street_view_control']['street_view_close_button']=="" )
{
   $_POST['street_view_control']['street_view_close_button'] = 'false';
}
else
{
	$_POST['street_view_control']['street_view_close_button'] = $_POST['street_view_control']['street_view_close_button'];
}
if( $_POST['street_view_control']['links_control']=="" )
{
   $_POST['street_view_control']['links_control'] = 'true';
}
else
{
	$_POST['street_view_control']['links_control'] = $_POST['street_view_control']['links_control'];
}
if( $_POST['street_view_control']['street_view_pan_control']=="" )
{
   $_POST['street_view_control']['street_view_pan_control'] = 'true';
}
else
{
	$_POST['street_view_control']['street_view_pan_control'] = $_POST['street_view_control']['street_view_pan_control'];
}
if( $_POST['control']['pan_control']=="" )
{
   $_POST['control']['pan_control'] = 'true';
}
else
{
	$_POST['control']['pan_control'] = $_POST['control']['pan_control'];
}
if( $_POST['control']['zoom_control']=="" )
{
   $_POST['control']['zoom_control'] = 'true';
}
else
{
	$_POST['control']['zoom_control'] = $_POST['control']['zoom_control'];
}
if( $_POST['control']['map_type_control']=="" )
{
   $_POST['control']['map_type_control'] = 'true';
}
else
{
	$_POST['control']['map_type_control'] = $_POST['control']['map_type_control'];
}
if( $_POST['control']['scale_control']=="" )
{
   $_POST['control']['scale_control'] = 'true';
}
else
{
	$_POST['control']['scale_control'] = $_POST['control']['scale_control'];
}
if( $_POST['control']['street_view_control']=="" )
{
   $_POST['control']['street_view_control'] = 'true';
}
else
{
	$_POST['control']['street_view_control'] = $_POST['control']['street_view_control'];
}
if( $_POST['control']['overview_map_control']=="" )
{
   $_POST['control']['overview_map_control'] = 'true';
}
else
{
	$_POST['control']['overview_map_control'] = $_POST['control']['overview_map_control'];
}
if( $_POST['info_window_setting']['info_window']=="" )
{
   $_POST['info_window_setting']['info_window'] = 'true';
}
else
{
	$_POST['info_window_setting']['info_window'] = $_POST['info_window_setting']['info_window'];
}
if( $_POST['info_window_setting']['enable_info_window_setting']=="" )
{
   $_POST['info_window_setting']['enable_info_window_setting'] = 'false';
   $_POST['info_window_setting']['info_window_width'] = 300;
   $_POST['info_window_setting']['info_window_height'] = '';
   $_POST['info_window_setting']['info_window_shadow_style'] = 0;
   $_POST['info_window_setting']['info_window_border_radious'] = 10;
   $_POST['info_window_setting']['info_window_border_width'] = 1;
   $_POST['info_window_setting']['info_window_border_color'] = "#CCCCCC";
   $_POST['info_window_setting']['info_window_background_color'] = "#FFFFFF";
   $_POST['info_window_setting']['info_window_arrow_size'] = 20;
   $_POST['info_window_setting']['info_window_arrow_position'] = 50;
   $_POST['info_window_setting']['info_window_arrow_style'] = 0;
}
else
{
	$_POST['info_window_setting']['enable_info_window_setting'] = $_POST['info_window_setting']['enable_info_window_setting'];
}
if( $_POST['locations']=="" )
{
   $error[]= __( 'Please check any one location.', 'wpgmp_google_map' );
}
if( $_POST['group_map_setting']['enable_group_map']=='true' )
{
	if( $_POST['group_map_setting']['select_group_map']=="" )
	{
		$error[]= __( 'Please check at least one group map.', 'wpgmp_google_map' );
	}
}
if( $_POST['layer_setting']['choose_layer']=="KmlLayer" && $_POST['layer_setting']['map_links']=="" )
{
	$error[]= __( 'Please insert KML link.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['fusion_select']=="" )
{
	$error[]= __( 'Please insert Fusion Select.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['fusion_from']=="" )
{
	$error[]= __( 'Please insert Fusion From.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['heat_map']=="" )
{
	$_POST['layer_setting']['heat_map'] = 'false';
}
if( $_POST['polygon_setting']['draw_polygon']=="" )
{
	$_POST['polygon_setting']['draw_polygon'] = 'false';
	$_POST['polygon_setting']['polygon_border_color'] = 'f22800';		
	$_POST['polygon_setting']['polygon_background_color'] = 'f22800';
}
else
{
	$_POST['polygon_setting']['draw_polygon'] = $_POST['polygon_setting']['draw_polygon'];
}
if( $_POST['polyline_setting']['draw_polyline']=="" )
{
	$_POST['polyline_setting']['draw_polyline'] = 'false';
	$_POST['polyline_setting']['polyline_stroke_color'] = 'f22800';
	$_POST['polyline_setting']['polyline_stroke_opacity'] = 1.0;
	$_POST['polyline_setting']['polyline_stroke_weight'] = 2;
}
else
{
	$_POST['polyline_setting']['draw_polyline'] = $_POST['polyline_setting']['draw_polyline'];
}
if( $_POST['cluster_setting']['marker_cluster']=="" )
{
	$_POST['cluster_setting']['marker_cluster'] = 'false';
}
else
{
	$_POST['cluster_setting']['marker_cluster'] = $_POST['cluster_setting']['marker_cluster'];
}
if( $_POST['cluster_setting']['marker_cluster']=='true' )
{
	if( $_POST['cluster_setting']['grid']=="" )	{
	   $error[]= __( 'Please enter grid.', 'wpgmp_google_map' );
	} else if( !intval($_POST['cluster_setting']['grid']) )	{
		$error[]= __( 'Please enter Integer value in grid.', 'wpgmp_google_map' );
	}
}
else
{
	$_POST['cluster_setting']['grid'] = '15';
	$_POST['cluster_setting']['map_style'] = '-1';
	$_POST['cluster_setting']['max_zoom'] = '1';
}
if( !empty($_POST['overlay_setting']['overlay']) )
{
	$_POST['overlay_setting']['overlay'] = $_POST['overlay_setting']['overlay'];
	
	if( $_POST['overlay_setting']['overlay_width']=="" ) {
	   $error[]= __( 'Please enter overlay width.', 'wpgmp_google_map' );
	} else if( !intval($_POST['overlay_setting']['overlay_width']) ) {
		$error[]= __( 'Please enter Integer value in overlay width.', 'wpgmp_google_map' );
	} 
	if( $_POST['overlay_setting']['overlay_height']=="" ) {
	   $error[]= __( 'Please enter overlay height.', 'wpgmp_google_map' );
	} else if( !intval($_POST['overlay_setting']['overlay_height']) ) {
		$error[]= __( 'Please enter Integer value in overlay height.', 'wpgmp_google_map' );
	} 
	if( $_POST['overlay_setting']['overlay_fontsize']=="" )	{
	   $error[]= __( 'Please enter overlay Font Size.', 'wpgmp_google_map' );
	} else if( !intval($_POST['overlay_setting']['overlay_fontsize']) ) {
		$error[]= __( 'Please enter Integer value in overlay Font Size.', 'wpgmp_google_map' );
	} 
	if( $_POST['overlay_setting']['overlay_border_width']=="" ) {
	   $error[]= __( 'Please enter overlay border width.', 'wpgmp_google_map' );
	} else if( !intval($_POST['overlay_setting']['overlay_border_width']) ) {
		$error[]= __( 'Please enter Integer value in overlay border width.', 'wpgmp_google_map' );
	}
}
else
{
	$_POST['overlay_setting']['overlay'] = 'false';
	$_POST['overlay_setting']['overlay_border_color'] = 'f22800';
	$_POST['overlay_setting']['overlay_width'] = '200';	
	$_POST['overlay_setting']['overlay_height'] = '200';
	$_POST['overlay_setting']['overlay_fontsize'] = '16';
	$_POST['overlay_setting']['overlay_border_width'] = '2';
	$_POST['overlay_setting']['overlay_border_style'] = 'default';
}			
if( empty($error) )
{
$map_update_table=$wpdb->prefix."create_map";
$wpdb->update( 
$map_update_table, 
array( 
	'map_title' => htmlspecialchars(stripslashes($_POST['map_title'])),
	'map_width' => $_POST['map_width'],
	'map_height' => $_POST['map_height'],
	'map_zoom_level' => $_POST['zoom_level'],
	'map_type' => $_POST['choose_map'],
	'map_scrolling_wheel' => $_POST['scrolling_wheel'],
	'map_visual_refresh' => $_POST['visual_refresh'],
	'map_45imagery' => $_POST['45imagery'],
	'map_street_view_setting' => serialize($_POST['street_view_control']),
	'map_route_direction_setting' => serialize($_POST['direction_servics']),
	'map_all_control' => serialize($_POST['control']),
	'map_info_window_setting' => serialize($_POST['info_window_setting']),
	'style_google_map' => serialize($_POST['style_array_type']),
	'map_locations' => serialize($_POST['locations']),
	'map_layer_setting' => serialize($_POST['layer_setting']),
	'map_polygon_setting' => serialize($_POST['polygon_setting']),
	'map_polyline_setting' => serialize($_POST['polyline_setting']),
	'map_cluster_setting' => serialize($_POST['cluster_setting']),
	'map_overlay_setting' => serialize($_POST['overlay_setting'])
), 
array( 'map_id' => $_GET['map'] ) 
);	
$success= __( 'Map Updated Successfully.', 'wpgmp_google_map' );
}
}
?>
<style type="text/css">
.success{
	background-color:#CF9 !important;
	border:1px solid #903 !important;
}
</style>
<div class="wrap">  
<?php
if( $_GET['action']=='edit' && $_GET['map']!='' )
{
$map_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$_GET['map']));
$unserialize_map_street_view_setting = unserialize($map_record->map_street_view_setting);
$unserialize_map_route_direction_setting = unserialize($map_record->map_route_direction_setting);
$unserialize_map_control_setting = unserialize($map_record->map_all_control);
$unserialize_map_info_window_setting = unserialize($map_record->map_info_window_setting);
$unserialize_map_layer_setting = unserialize($map_record->map_layer_setting);
$unserialize_google_map_style = unserialize($map_record->style_google_map);
$unserialize_map_polygon_setting = unserialize($map_record->map_polygon_setting);
$unserialize_map_polyline_setting = unserialize($map_record->map_polyline_setting);
$unserialize_map_cluster_setting = unserialize($map_record->map_cluster_setting);
$unserialize_map_overlay_setting = unserialize($map_record->map_overlay_setting);
?>
<div id="icon-options-general" class="icon32"><br></div>
<h2><?php _e('Edit Map', 'wpgmp_google_map')?></h2><br />
<form method="post">
<?php
if( !empty($error) )
{
	$error_msg=implode('<br>',$error);
	
	wpgmp_showMessage($error_msg,true);
}
if( !empty($success) )
{
    
    wpgmp_showMessage($success);
}
?>
<div class="map_table">
<fieldset>
    <legend><?php _e('General Setting', 'wpgmp_google_map')?></legend>
    
    <label for="title"><?php _e('Map Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
	<input type="text" name="map_title" value="<?php echo stripslashes($map_record->map_title); ?>" class="create_map" />
	<p class="description"><?php _e('Insert here the title', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Map Width', 'wpgmp_google_map')?></label>
	<input type="text" name="map_width" value="<?php echo $map_record->map_width; ?>" class="create_map" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here the map width', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Map Height', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
	<input type="text" name="map_height" value="<?php echo $map_record->map_height; ?>" class="create_map" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here the map height', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Map Zoom Level', 'wpgmp_google_map')?></label>
    <select name="zoom_level">
        <option value="1"<?php selected($map_record->map_zoom_level,'1') ?>>1</option>
        <option value="2"<?php selected($map_record->map_zoom_level,'2') ?>>2</option>
        <option value="3"<?php selected($map_record->map_zoom_level,'3') ?>>3</option>
        <option value="4"<?php selected($map_record->map_zoom_level,'4') ?>>4</option>
        <option value="5"<?php selected($map_record->map_zoom_level,'5') ?>>5</option>
        <option value="6"<?php selected($map_record->map_zoom_level,'6') ?>>6</option>
        <option value="7"<?php selected($map_record->map_zoom_level,'7') ?>>7</option>
        <option value="8"<?php selected($map_record->map_zoom_level,'8') ?>>8</option>
        <option value="9"<?php selected($map_record->map_zoom_level,'9') ?>>9</option>
        <option value="10"<?php selected($map_record->map_zoom_level,'10') ?>>10</option>
        <option value="11"<?php selected($map_record->map_zoom_level,'11') ?>>11</option>
        <option value="12"<?php selected($map_record->map_zoom_level,'12') ?>>12</option>
        <option value="13"<?php selected($map_record->map_zoom_level,'13') ?>>13</option>
        <option value="14"<?php selected($map_record->map_zoom_level,'14') ?>>14</option>
    </select>
    <p class="description"><?php _e('(Available options - 1,2,3,4,5,6,8,9,10,11,12,13,14).', 'wpgmp_google_map')?></p>
	
    <label for="title"><?php _e('Choose Map Type', 'wpgmp_google_map')?></label>
    <select name="choose_map">
        <option value="ROADMAP"<?php selected($map_record->map_type,'ROADMAP') ?>><?php _e('ROADMAP', 'wpgmp_google_map')?></option>
        <option value="SATELLITE"<?php selected($map_record->map_type,'SATELLITE') ?>><?php _e('SATELLITE', 'wpgmp_google_map')?></option>
        <option value="HYBRID"<?php selected($map_record->map_type,'HYBRID') ?>><?php _e('HYBRID', 'wpgmp_google_map')?></option>
        <option value="TERRAIN"<?php selected($map_record->map_type,'TERRAIN') ?>><?php _e('TERRAIN', 'wpgmp_google_map')?></option>
    </select>
	<p class="description"><?php _e('(Available options - ROADMAP,SATELLITE,HYBRID,TERRAIN {Default is roadmap type}).', 'wpgmp_google_map')?></p>
	
    
    
	<label for="title"><?php _e('Turn Off Scrolling Wheel', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="scrolling_wheel" value="false"<?php checked($map_record->map_scrolling_wheel,'false') ?>/>
	<p class="description"><?php _e('Please Check TO Disable Scroll Wheel Zooms.', 'wpgmp_google_map')?></p>
    
	<label for="title"><?php _e('Enable Visual Refresh', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="visual_refresh" value="true"<?php checked($map_record->map_visual_refresh,'true') ?>/>
	<p class="description"><?php _e('Please check to enable visual refresh.', 'wpgmp_google_map')?></p>
	
	<label for="title"><?php _e('45&deg; Imagery', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="45imagery" value="45"<?php checked($map_record->map_45imagery,'45') ?> />
	<p class="description"><?php _e('Apply 45&deg; Imagery ? (only available for map type SATELLITE and HYBRID).', 'wpgmp_google_map')?></p>
</fieldset>
<fieldset>
    <legend><?php _e('Choose Locations', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></legend>
    <ul>
    <?php
    global $wpdb;
    $un_maploc = unserialize($map_record->map_locations);
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations",NULL));
    for($i = 0; $i < count($results); $i++)
	{
    ?>
    	<li>    
        	<?php if( in_array($results[$i]->location_id,$un_maploc) )
			{
			?>
        	<input type="checkbox" name="locations[]" value="<?php echo $results[$i]->location_id; ?>" checked="checked"/>&nbsp;&nbsp;<?php echo stripcslashes($results[$i]->location_address); ?>
        	<?php
			}
			else
			{
			?>
            <input type="checkbox" name="locations[]" value="<?php echo $results[$i]->location_id; ?>"/>&nbsp;&nbsp;<?php echo $results[$i]->location_address; ?>
        	<?php
            }
			?>
    	</li>    
     <?php    
      }
      ?>
      </ul>
</fieldset>
<fieldset>
    <legend><?php _e('Layers', 'wpgmp_google_map')?></legend>
    
    <label for="title"><?php _e('Select Layers', 'wpgmp_google_map')?></label>
    <select name="layer_setting[choose_layer]" onchange="mylayer(this.value)">
        <option value=""><?php _e('Select Layers', 'wpgmp_google_map')?></option>
        <option value="KmlLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'KmlLayer') ?>><?php _e('KML Layers', 'wpgmp_google_map')?></option>
        <option value="FusionTablesLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'FusionTablesLayer') ?>><?php _e('Fusion Tables Layers', 'wpgmp_google_map')?></option>
        <option value="TrafficLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'TrafficLayer') ?>><?php _e('Traffic Layers', 'wpgmp_google_map')?></option>
        <option value="TransitLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'TransitLayer') ?>><?php _e('Transit Layers', 'wpgmp_google_map')?></option>
        <option value="WeatherLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'WeatherLayer') ?>><?php _e('Weather Layers', 'wpgmp_google_map')?></option>
        <option value="BicyclingLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'BicyclingLayer') ?>><?php _e('Bicycling Layers', 'wpgmp_google_map')?></option>
        <option value="PanoramioLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'PanoramioLayer') ?>><?php _e('Panoramio Layers', 'wpgmp_google_map')?></option>
    </select>
	<p class="description"><?php _e('(Available options - KML Layers,Fusion Tables Layers,Traffic Layers,Transit Layers,Weather Layers,Bicycling Layers,Panoramio Layers).', 'wpgmp_google_map')?></p>
    
	<?php
	if( $unserialize_map_layer_setting['choose_layer']=="KmlLayer" )
	{
	?>
	<div id="kmldisplay">
		<label for="title"><?php _e('Kml Links', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[map_links]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['map_links']); ?>" />
	<p class="description"><?php _e('Insert here the Kml Link if you select KML Layer.', 'wpgmp_google_map')?></p>
	</div>
	<?php
	}
	else
	{
	?>
	<div id="kmldisplay" style="display:none;">
		<label for="title"><?php _e('Kml Links', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[map_links]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['map_links']); ?>" />
	<p class="description"><?php _e('Insert here the Kml Link if you select KML Layer.', 'wpgmp_google_map')?></p>
	</div>
	<?php
	}
	?>
    
	<?php
	if( $unserialize_map_layer_setting['choose_layer']=="FusionTablesLayer" )
	{
	?>
	<div id="fusiondisplay">	
    	<label for="title"><?php _e('Select', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[fusion_select]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['fusion_select']); ?>" />
	<p class="description"><?php _e('Insert here the fusion select.', 'wpgmp_google_map')?></p>
    
    	<label for="title"><?php _e('From', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[fusion_from]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['fusion_from']); ?>" />
	<p class="description"><?php _e('Insert here the fusion from.', 'wpgmp_google_map')?></p>
    
    
    <label for="title"><?php _e('Heat Map', 'wpgmp_google_map')?></label>
		<input type="checkbox" name="layer_setting[heat_map]" value="true"<?php checked($unserialize_map_layer_setting['heat_map'],'true'); ?> />
	<p class="description"><?php _e('Please check if heat map is enable.', 'wpgmp_google_map')?></p>
	</div>
	<?php
	}
	else
	{
	?>
	<div id="fusiondisplay" style="display:none;">	
    	<label for="title"><?php _e('Select', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[fusion_select]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['fusion_select']); ?>" />
	<p class="description"><?php _e('Insert here the fusion select.', 'wpgmp_google_map')?></p>
    
    	<label for="title"><?php _e('From', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
		<input type="text" name="layer_setting[fusion_from]" class="create_map" value="<?php echo stripslashes($unserialize_map_layer_setting['fusion_from']); ?>" />
	<p class="description"><?php _e('Insert here the fusion from.', 'wpgmp_google_map')?></p>
		 <label for="title"><?php _e('Heat Map', 'wpgmp_google_map')?></label>
		<input type="checkbox" name="layer_setting[heat_map]" value="true"<?php checked($unserialize_map_layer_setting['heat_map'],'true'); ?> />
	<p class="description"><?php _e('Please check if heat map is enable.', 'wpgmp_google_map')?></p>
	</div>
	<?php
	}
	?>
	<?php
	if( $unserialize_map_layer_setting['choose_layer']=='WeatherLayer' )
	{
	?>
    <div id="weatherlayer">
    
        <label for="title"><?php _e('Temperature units:', 'wpgmp_google_map')?></label>
        <input type="radio" name="layer_setting[temp]" value="CELSIUS"<?php checked($unserialize_map_layer_setting['temp'],'CELSIUS'); ?> /><?php _e('&nbsp;Celsius&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[temp]" value="FAHRENHEIT"<?php checked($unserialize_map_layer_setting['temp'],'FAHRENHEIT'); ?> /><?php _e('&nbsp;Fahrenheit', 'wpgmp_google_map')?>
    	<p class="description"><?php _e('Please check temperature unit.', 'wpgmp_google_map')?></p>
        
        <label for="title"><?php _e('Wind speed units:', 'wpgmp_google_map')?></label>
        <input type="radio" name="layer_setting[wind]" value="MILES_PER_HOUR"<?php checked($unserialize_map_layer_setting['wind'],'MILES_PER_HOUR'); ?> /><?php _e('&nbsp;mph&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[wind]" value="KILOMETERS_PER_HOUR"<?php checked($unserialize_map_layer_setting['wind'],'KILOMETERS_PER_HOUR'); ?> /><?php _e('&nbsp;km/h&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[wind]" value="METERS_PER_SECOND"<?php checked($unserialize_map_layer_setting['wind'],'METERS_PER_SECOND'); ?> /><?php _e('&nbsp;m/s', 'wpgmp_google_map')?>
        <p class="description"><?php _e('Please check wind speed unit.', 'wpgmp_google_map')?></p>
    
    </div>
    
    <?php
	}
	else
	{
	?>
    <div id="weatherlayer" style="display:none;">
    
        <label for="title"><?php _e('Temperature units:', 'wpgmp_google_map')?></label>
        <input type="radio" name="layer_setting[temp]" value="CELSIUS"<?php checked($unserialize_map_layer_setting['temp'],'CELSIUS'); ?>  /><?php _e('&nbsp;Celsius&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[temp]" value="FAHRENHEIT"<?php checked($unserialize_map_layer_setting['temp'],'FAHRENHEIT'); ?> /><?php _e('&nbsp;Fahrenheit', 'wpgmp_google_map')?>
    	<p class="description"><?php _e('Please check temperature unit.', 'wpgmp_google_map')?></p>
        
        <label for="title"><?php _e('Wind speed units:', 'wpgmp_google_map')?></label>
        <input type="radio" name="layer_setting[wind]" value="MILES_PER_HOUR"<?php checked($unserialize_map_layer_setting['wind'],'MILES_PER_HOUR'); ?>  /><?php _e('&nbsp;mph&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[wind]" value="KILOMETERS_PER_HOUR"<?php checked($unserialize_map_layer_setting['wind'],'KILOMETERS_PER_HOUR'); ?>/><?php _e('&nbsp;km/h&nbsp;&nbsp;&nbsp;', 'wpgmp_google_map')?>
        <input type="radio" name="layer_setting[wind]" value="METERS_PER_SECOND"<?php checked($unserialize_map_layer_setting['wind'],'METERS_PER_SECOND'); ?> /><?php _e('&nbsp;m/s', 'wpgmp_google_map')?>
        <p class="description"><?php _e('Please check wind speed unit.', 'wpgmp_google_map')?></p>
    
    </div>
    <?php
	}
	?>
</fieldset>
<fieldset>
    <legend><?php _e('Control Setting', 'wpgmp_google_map')?></legend>
    
     <label for="title"><?php _e('Turn Off Pan Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[pan_control]" value="false"<?php checked($unserialize_map_control_setting['pan_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable pan control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Zoom Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[zoom_control]" value="false"<?php checked($unserialize_map_control_setting['zoom_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable zoom control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Map Type Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[map_type_control]" value="false"<?php checked($unserialize_map_control_setting['map_type_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable map type control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Scale Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[scale_control]" value="false"<?php checked($unserialize_map_control_setting['scale_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable scale control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Street View Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[street_view_control]" value="false"<?php checked($unserialize_map_control_setting['street_view_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable street view control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Overview Map Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="control[overview_map_control]" value="false"<?php checked($unserialize_map_control_setting['overview_map_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable overview map control.', 'wpgmp_google_map')?></p>
    
</fieldset>
<fieldset>
    <legend><?php _e('Map Style Setting', 'wpgmp_google_map')?></legend>
<?php
$featuredtype=array("administrative",'administrative.country','administrative.land_parcel','administrative.locality','administrative.neighborhood','administrative.province','all',
'landscape','landscape.man_made','landscape.natural','landscape.natural.landcover','landscape.natural.terrain','poi','poi.attraction','poi.business','poi.government','poi.medical','poi.park','poi.place_of_worship','poi.school','poi.sports_complex','road','road.arterial','road.highway','road.highway.controlled_access','road.local','transit','transit.line','transit.station','transit.station.airport','transit.station.bus','transit.station.rail','water');
$elementstype=array('all','geometry','geometry.fill','geometry.stroke','labels','labels.icon','labels.text','labels.text.fill','labels.text.stroke');
?>
<table class="style_table" style="width:100%;">
<tr>
     <td>
    	<label for="title"><?php _e('Style #', 'wpgmp_google_map')?></label>
    </td>
    <td>
    	<label for="title"><?php _e('Feature Type', 'wpgmp_google_map')?></label>
    </td>
    <td>
    	<label for="title"><?php _e('Element Type', 'wpgmp_google_map')?></label>
    </td>
    <td>
    	<label for="title"><?php _e('Color', 'wpgmp_google_map')?></label>
    </td>
     <td>
    	<label for="title"><?php _e('Visibility', 'wpgmp_google_map')?></label>
    </td>
</tr>
<?php
for( $i=0; $i<10; $i++)
{
?>
<tr>
<td><?php _e('Style', 'wpgmp_google_map')?> <?php echo $i+1; ?></td>
    <td>
    	<select name="style_array_type[mapfeaturetype][<?php echo $i; ?>]">
    	<option value=""><?php _e('Select Featured Type', 'wpgmp_google_map')?></option>
    	<?php 
    	foreach($featuredtype as $key=>$value)
    	{	
    	
    	if($value==$unserialize_google_map_style['mapfeaturetype'][$i])
    	$s="selected='selected'";
    	else
    	$s='';
    	
    	?>
    	<option <?php echo $s; ?> value="<?php echo $value;?>"><?php echo $value;?></option>
    	<?php 
    	}
    	?>
    	</select>
    </td>
    
    <td>
    	<select name="style_array_type[mapelementtype][<?php echo $i; ?>]">
    	<option value=""><?php _e('Select Element Type', 'wpgmp_google_map')?></option>
    	<?php 
    	
    	foreach($elementstype as $key=>$value)
    	{
    	
    	if($value==$unserialize_google_map_style['mapelementtype'][$i])
    	$s="selected='selected'";
    	else
    	$s='';
    	
    	?>
    	<option <?php echo $s; ?> value="<?php echo $value;?>"><?php echo $value;?></option>
    	<?php 
    	}
    	?>
    	
    	</select>
    </td>
    
    <td>
		<?php
        if( $unserialize_google_map_style['color'][$i]!='' )
        {
        ?>
        <input type="text" value="<?php echo $unserialize_google_map_style['color'][$i]; ?>" name="style_array_type[color][<?php echo $i; ?>]" class="color {pickerClosable:true}" />
        <?php
        }
        else
        {
        ?>
        <input type="text" value="" name="style_array_type[color][<?php echo $i; ?>]" class="color {pickerClosable:true}" />
        <?php
        }
        ?>
    </td>
    <td>
    	<select name="style_array_type[visibility][]" class="style_select_map">
        	<option value="on"<?php selected($unserialize_google_map_style['visibility'][$i],'on') ?>><?php _e('Yes', 'wpgmp_google_map')?></option>
	    	<option value="off"<?php selected($unserialize_google_map_style['visibility'][$i],'off') ?>><?php _e('No', 'wpgmp_google_map')?></option>
	    	<option value="simplifed" <?php selected($unserialize_google_map_style['visibility'][$i],'simplifed') ?>><?php _e('Simplifed', 'wpgmp_google_map')?></option>
     	</select>
    </td>
</tr>
<?php
}
?>
</table>
</fieldset>
<fieldset>
    <legend><?php _e('Info Window Setting', 'wpgmp_google_map')?></legend>
    
	 <label for="title"><?php _e('Info Window Setting', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="info_window_setting[enable_info_window_setting]" class="info_window_toggle" value="true"<?php checked($unserialize_map_info_window_setting['enable_info_window_setting'],'true') ?>/>
	<p class="description"><?php _e('Please check to enable Info window settings.', 'wpgmp_google_map')?></p>
<?php
if( $unserialize_map_info_window_setting['enable_info_window_setting']=='true' )
{
?>  
<div id="disply_info_window">
    
       <label for="title"><?php _e('Turn Off Info Window', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="info_window_setting[info_window]"  value="false"<?php checked($unserialize_map_info_window_setting['info_window'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable infowindow messages.', 'wpgmp_google_map')?></p>
    <label><?php _e('Info Window Width:', 'wpgmp_google_map')?></label>
    <input type="text" name="info_window_setting[info_window_width]" value="<?php echo $unserialize_map_info_window_setting['info_window_width']; ?>" class="create_map"/>&nbsp;px
    <p class="description"><?php _e('Please insert info window Width.', 'wpgmp_google_map')?></p>
    
    <label><?php _e('Info Window Height:', 'wpgmp_google_map')?></label>
    <input type="text" name="info_window_setting[info_window_height]" class="create_map" value="<?php echo $unserialize_map_info_window_setting['info_window_height']; ?>" />&nbsp;px
    <p class="description"><?php _e('Please insert info window height.', 'wpgmp_google_map')?></p>
    
     <label><?php _e('Info Window ShadowStyle:', 'wpgmp_google_map')?></label>
        <select name="info_window_setting[info_window_shadow_style]">
          <option value=""><?php _e('Select Shawdow Style', 'wpgmp_google_map')?></option>
          <option value="0"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],0) ?>>0</option>
          <option value="1"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],1) ?>>1</option>
          <option value="2"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],2) ?>>2</option>
        </select>
     <p class="description"><?php _e('Please select info window shawdow style.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Radius:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_border_radious]" value="<?php echo $unserialize_map_info_window_setting['info_window_border_radious']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window border radious.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Width:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_border_width]" value="<?php echo $unserialize_map_info_window_setting['info_window_border_width']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window border width.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Color:', 'wpgmp_google_map')?></label>
        <input type="text" value="<?php echo $unserialize_map_info_window_setting['info_window_border_color']; ?>" name="info_window_setting[info_window_border_color]" class="color {pickerClosable:true}" />
        <p class="description"><?php _e('Please insert info window border color.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Background Color:', 'wpgmp_google_map')?></label>
        <input type="text" value="<?php echo $unserialize_map_info_window_setting['info_window_background_color']; ?>" name="info_window_setting[info_window_background_color]" class="color {pickerClosable:true}" />
        <p class="description"><?php _e('Please insert info window background color.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Size:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_arrow_size]" value="<?php echo $unserialize_map_info_window_setting['info_window_arrow_size']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window arrow size.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Position:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_arrow_position]" value="<?php echo $unserialize_map_info_window_setting['info_window_arrow_position']; ?>" class="create_map"/>&nbsp;%
        <p class="description"><?php _e('Please insert info window arrow position.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Style:', 'wpgmp_google_map')?></label>
        <select name="info_window_setting[info_window_arrow_style]">
          <option value=""><?php _e('Select Arrow Style', 'wpgmp_google_map')?></option>
          <option value="0"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],0) ?>>0</option>
          <option value="1"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],1) ?>>1</option>
          <option value="2"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],2) ?>>2</option>
        </select>
        <p class="description"><?php _e('Please select info window arrow style.', 'wpgmp_google_map')?></p>
  </div>
 <?php
}
else
{
?>
<div id="disply_info_window" style="display:none;">
    
    <label for="title"><?php _e('Turn Off Info Window', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="info_window_setting[info_window]"  value="false"<?php checked($unserialize_map_info_window_setting['info_window'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable infowindow messages.', 'wpgmp_google_map')?></p>
    <label><?php _e('Info Window Width:', 'wpgmp_google_map')?></label>
    <input type="text" name="info_window_setting[info_window_width]" value="<?php echo $unserialize_map_info_window_setting['info_window_width']; ?>" class="create_map"/>&nbsp;px
    <p class="description"><?php _e('Please insert info window Width.', 'wpgmp_google_map')?></p>
    
    <label><?php _e('Info Window Height:', 'wpgmp_google_map')?></label>
    <input type="text" name="info_window_setting[info_window_height]" class="create_map" value="<?php echo $unserialize_map_info_window_setting['info_window_height']; ?>" />&nbsp;px
    <p class="description"><?php _e('Please insert info window height.', 'wpgmp_google_map')?></p>
    
     <label><?php _e('Info Window ShadowStyle:', 'wpgmp_google_map')?></label>
        <select name="info_window_setting[info_window_shadow_style]">
          <option value=""><?php _e('Select Shawdow Style', 'wpgmp_google_map')?></option>
          <option value="0"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],0) ?>>0</option>
          <option value="1"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],1) ?>>1</option>
          <option value="2"<?php selected($unserialize_map_info_window_setting['info_window_shadow_style'],2) ?>>2</option>
        </select>
     <p class="description"><?php _e('Please select info window shawdow style.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Radius:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_border_radious]" value="<?php echo $unserialize_map_info_window_setting['info_window_border_radious']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window border radious.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Width:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_border_width]" value="<?php echo $unserialize_map_info_window_setting['info_window_border_width']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window border width.', 'wpgmp_google_map')?></p>
      
        <label><?php _e('Info Window Border Color:', 'wpgmp_google_map')?></label>
        <input type="text" value="<?php echo $unserialize_map_info_window_setting['info_window_border_color']; ?>" name="info_window_setting[info_window_border_color]" class="color {pickerClosable:true}" />
        <p class="description"><?php _e('Please insert info window border color.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Background Color:', 'wpgmp_google_map')?></label>
        <input type="text" value="<?php echo $unserialize_map_info_window_setting['info_window_background_color']; ?>" name="info_window_setting[info_window_background_color]" class="color {pickerClosable:true}" />
        <p class="description"><?php _e('Please insert info window background color.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Size:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_arrow_size]" value="<?php echo $unserialize_map_info_window_setting['info_window_arrow_size']; ?>" class="create_map"/>&nbsp;px
        <p class="description"><?php _e('Please insert info window arrow size.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Position:', 'wpgmp_google_map')?></label>
        <input type="text" name="info_window_setting[info_window_arrow_position]" value="<?php echo $unserialize_map_info_window_setting['info_window_arrow_position']; ?>" class="create_map"/>&nbsp;%
        <p class="description"><?php _e('Please insert info window arrow position.', 'wpgmp_google_map')?></p>
     
        <label><?php _e('Info Window Arrow Style:', 'wpgmp_google_map')?></label>
        <select name="info_window_setting[info_window_arrow_style]">
          <option value=""><?php _e('Select Arrow Style', 'wpgmp_google_map')?></option>
          <option value="0"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],0) ?>>0</option>
          <option value="1"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],1) ?>>1</option>
          <option value="2"<?php selected($unserialize_map_info_window_setting['info_window_arrow_style'],2) ?>>2</option>
        </select>
        <p class="description"><?php _e('Please select info window arrow style.', 'wpgmp_google_map')?></p>
  </div>
<?php
}
?>	
</fieldset>
<fieldset>
    <legend><?php _e('Street View Setting', 'wpgmp_google_map')?></legend>
    
     <label for="title"><?php _e('Turn On Street View', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[street_control]"  class="street_view_toggle" value="true"<?php checked($unserialize_map_street_view_setting['street_control'],'true') ?>/>
	<p class="description"><?php _e('Please check to enable Street View control.', 'wpgmp_google_map')?></p>
 
<?php
if( $unserialize_map_street_view_setting['street_control']=='true' )
{
?>   
<div id="disply_street_view">
  
        <label for="title"><?php _e('Turn On Close Button', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[street_view_close_button]" value="true"<?php checked($unserialize_map_street_view_setting['street_view_close_button'],'true') ?>/>
	<p class="description"><?php _e('Please check to enable Close button.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off links Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[links_control]" value="false"<?php checked($unserialize_map_street_view_setting['links_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable links control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Street View Pan Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[street_view_pan_control]" value="false"<?php checked($unserialize_map_street_view_setting['street_view_pan_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable Street View Pan control.', 'wpgmp_google_map')?></p>
   </div> 
 <?php
}
else
{
?>
<div id="disply_street_view" style="display:none;">
  
        <label for="title"><?php _e('Turn On Close Button', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[street_view_close_button]" value="true"<?php checked($unserialize_map_street_view_setting['street_view_close_button'],'true') ?>/>
	<p class="description"><?php _e('Please check to enable Close button.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off links Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[links_control]" value="false"<?php checked($unserialize_map_street_view_setting['links_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable links control.', 'wpgmp_google_map')?></p>
    
    <label for="title"><?php _e('Turn Off Street View Pan Control', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="street_view_control[street_view_pan_control]" value="false"<?php checked($unserialize_map_street_view_setting['street_view_pan_control'],'false') ?>/>
	<p class="description"><?php _e('Please check to disable Street View Pan control.', 'wpgmp_google_map')?></p>
   </div>
<?php
}
?>
    
</fieldset>
<fieldset>
    <legend><?php _e('Route Direction Setting', 'wpgmp_google_map')?></legend>  
	
    <label for="title"><?php _e('Turn On Map Route Directions', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="direction_servics[route_direction]" class="route_direction_toggle" value="true"<?php checked($unserialize_map_route_direction_setting['route_direction'],'true') ?>/>
	<p class="description"><?php _e('Please Check TO Enable Map Route Directions.', 'wpgmp_google_map')?></p>
    
<?php
if( $unserialize_map_route_direction_setting['route_direction']=='true' )
{
?>   
<div id="disply_route_direction">
	<label for="title"><?php _e('Route Direction Stroke Color', 'wpgmp_google_map')?></label>
	
	<input type="text" value="<?php echo $unserialize_map_route_direction_setting['route_direction_stroke_color']; ?>" name="direction_servics[route_direction_stroke_color]" class="color {pickerClosable:true}" />
	
	<p class="description"><?php _e('Choose Route Direction Stroke Color.(Default is Blue)', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Route Direction Stroke Opacity', 'wpgmp_google_map')?></label>
	
	    <select name="direction_servics[route_direction_stroke_opacity]">
        <option value="0.1"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.1') ?>>0.1</option>
        <option value="0.2"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.2') ?>>0.2</option>
        <option value="0.3"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.3') ?>>0.3</option>
        <option value="0.4"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.4') ?>>0.4</option>
        <option value="0.5"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.5') ?>>0.5</option>
        <option value="0.6"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.6') ?>>0.6</option>
        <option value="0.7"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.7') ?>>0.7</option>
        <option value="0.8"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.8') ?>>0.8</option>
        <option value="0.9"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.9') ?>>0.9</option>
        <option value="1.0"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'1.0') ?>>1.0</option>
    </select>
	<p class="description"><?php _e('Please Select Route Direction Stroke Opacity.', 'wpgmp_google_map')?></p>
    
    
    <label for="title"><?php _e('Route Direction Stroke Weight', 'wpgmp_google_map')?></label>
		    <select name="direction_servics[route_direction_stroke_weight]">
        <option value="1"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'1.0') ?>>10</option>
    </select>
	
	<p class="description"><?php _e('Please Select Route Direction Stroke Weight.', 'wpgmp_google_map')?></p>
</div>
<?php
}
else
{
?>
<div id="disply_route_direction" style="display:none;">
	<label for="title"><?php _e('Route Direction Stroke Color', 'wpgmp_google_map')?></label>
	
	<input type="text" value="<?php echo $unserialize_map_route_direction_setting['route_direction_stroke_color']; ?>" name="direction_servics[route_direction_stroke_color]" class="color {pickerClosable:true}" />
	
	<p class="description"><?php _e('Choose Route Direction Stroke Color.(Default is Blue)', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Route Direction Stroke Opacity', 'wpgmp_google_map')?></label>
	
	    <select name="direction_servics[route_direction_stroke_opacity]">
        <option value="0.1"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.1') ?>>0.1</option>
        <option value="0.2"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.2') ?>>0.2</option>
        <option value="0.3"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.3') ?>>0.3</option>
        <option value="0.4"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.4') ?>>0.4</option>
        <option value="0.5"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.5') ?>>0.5</option>
        <option value="0.6"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.6') ?>>0.6</option>
        <option value="0.7"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.7') ?>>0.7</option>
        <option value="0.8"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.8') ?>>0.8</option>
        <option value="0.9"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'0.9') ?>>0.9</option>
        <option value="1.0"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_opacity'],'1.0') ?>>1.0</option>
    </select>
	<p class="description"><?php _e('Please Select Route Direction Stroke Opacity.', 'wpgmp_google_map')?></p>
    
    
    <label for="title"><?php _e('Route Direction Stroke Weight', 'wpgmp_google_map')?></label>
		    <select name="direction_servics[route_direction_stroke_weight]">
        <option value="1"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_route_direction_setting['route_direction_stroke_weight'],'1.0') ?>>10</option>
    </select>
	
	<p class="description"><?php _e('Please Select Route Direction Stroke Weight.', 'wpgmp_google_map')?></p>
</div>
<?php
}
?>
</fieldset>
<fieldset>
    <legend><?php _e('Polygon Setting', 'wpgmp_google_map')?></legend>  
	<label for="title"><?php _e('Draw Polygon', 'wpgmp_google_map')?></label>
 	<input type="checkbox" name="polygon_setting[draw_polygon]" class="polygon_toggle" value="true"<?php checked($unserialize_map_polygon_setting['draw_polygon'],'true') ?>/>
 	<p class="description"><?php _e('Draw Polygon&nbsp;&nbsp;(if you checked,a polygon will be drawn on selected locations like this http://developers.google.com/documentation/javascript/examples/polygon-arrays)', 'wpgmp_google_map')?></p>
 <?php
if( $unserialize_map_polygon_setting['draw_polygon']=='true' )
{
?>   
<div id="disply_polygon">
	<label for="title"><?php _e('Polygon Border Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_polygon_setting['polygon_border_color']; ?>" name="polygon_setting[polygon_border_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Polygon Background Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_polygon_setting['polygon_background_color']; ?>" name="polygon_setting[polygon_background_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
</div>
<?php
}
else
{
?>
<div id="disply_polygon" style="display:none;">
	<label for="title"><?php _e('Polygon Border Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_polygon_setting['polygon_border_color']; ?>" name="polygon_setting[polygon_border_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Polygon Background Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_polygon_setting['polygon_background_color']; ?>" name="polygon_setting[polygon_background_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
</div>
<?php
}
?>
</fieldset>
<fieldset>
    <legend><?php _e('Polyline Setting', 'wpgmp_google_map')?></legend>  
	
    <label for="title"><?php _e('Draw Polyline', 'wpgmp_google_map')?></label>
 	<input type="checkbox" name="polyline_setting[draw_polyline]" class="polyline_toggle" value="true"<?php checked($unserialize_map_polyline_setting['draw_polyline'],'true') ?>/>
 	<p class="description"><?php _e('Draw Polyline&nbsp;&nbsp;(if you checked,a polyline will be drawn on selected locations)', 'wpgmp_google_map')?></p>
<?php
if( $unserialize_map_polyline_setting['draw_polyline']=='true' )
{
?>
<div id="disply_polyline">
	<label for="title"><?php _e('Polyline Stroke Color', 'wpgmp_google_map')?></label>
	
	<input type="text" value="<?php echo $unserialize_map_polyline_setting['polyline_stroke_color']; ?>" name="polyline_setting[polyline_stroke_color]" class="color {pickerClosable:true}" />
	
	<p class="description"><?php _e('Choose polyline Stroke Color.(Default is Red)', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Polyline Stroke Opacity', 'wpgmp_google_map')?></label>
	<select name="polyline_setting[polyline_stroke_opacity]">
        <option value="0.1"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.1') ?>>0.1</option>
        <option value="0.2"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.2') ?>>0.2</option>
        <option value="0.3"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.3') ?>>0.3</option>
        <option value="0.4"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.4') ?>>0.4</option>
        <option value="0.5"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.5') ?>>0.5</option>
        <option value="0.6"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.6') ?>>0.6</option>
        <option value="0.7"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.7') ?>>0.7</option>
        <option value="0.8"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.8') ?>>0.8</option>
        <option value="0.9"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.9') ?>>0.9</option>
        <option value="1.0"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'1.0') ?>>1.0</option>
    </select>
	<p class="description"><?php _e('Please insert Polyline Stroke Opacity.', 'wpgmp_google_map')?></p>
    
    
    <label for="title"><?php _e('Polyline Stroke Weight', 'wpgmp_google_map')?></label>
	
	<select name="polyline_setting[polyline_stroke_weight]">
        <option value="1"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'1.0') ?>>10</option>
    </select>
	<p class="description"><?php _e('Please insert Polyline Stroke Weight.', 'wpgmp_google_map')?></p>
</div>
<?php
}
else
{
?>
<div id="disply_polyline" style="display:none;">
	<label for="title"><?php _e('Polyline Stroke Color', 'wpgmp_google_map')?></label>
	
	<input type="text" value="<?php echo $unserialize_map_polyline_setting['polyline_stroke_color']; ?>" name="polyline_setting[polyline_stroke_color]" class="color {pickerClosable:true}" />
	
	<p class="description"><?php _e('Choose polyline Stroke Color.(Default is Red)', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Polyline Stroke Opacity', 'wpgmp_google_map')?></label>
	<select name="polyline_setting[polyline_stroke_opacity]">
        <option value="0.1"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.1') ?>>0.1</option>
        <option value="0.2"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.2') ?>>0.2</option>
        <option value="0.3"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.3') ?>>0.3</option>
        <option value="0.4"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.4') ?>>0.4</option>
        <option value="0.5"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.5') ?>>0.5</option>
        <option value="0.6"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.6') ?>>0.6</option>
        <option value="0.7"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.7') ?>>0.7</option>
        <option value="0.8"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.8') ?>>0.8</option>
        <option value="0.9"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'0.9') ?>>0.9</option>
        <option value="1.0"<?php selected($unserialize_map_polyline_setting['polyline_stroke_opacity'],'1.0') ?>>1.0</option>
    </select>
	<p class="description"><?php _e('Please insert Polyline Stroke Opacity.', 'wpgmp_google_map')?></p>
    
    
    <label for="title"><?php _e('Polyline Stroke Weight', 'wpgmp_google_map')?></label>
	
	<select name="polyline_setting[polyline_stroke_weight]">
        <option value="1"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_polyline_setting['polyline_stroke_weight'],'1.0') ?>>10</option>
    </select>
	<p class="description"><?php _e('Please insert Polyline Stroke Weight.', 'wpgmp_google_map')?></p>
</div>
<?php
}
?>
</fieldset>	    
<fieldset>
    <legend><?php _e('Marker Cluster Setting', 'wpgmp_google_map')?></legend>
	<label for="title"><?php _e('Marker Cluster', 'wpgmp_google_map')?></label>    
	<input type="checkbox" name="cluster_setting[marker_cluster]" class="marker_cluster_toggle" value="true"<?php checked($unserialize_map_cluster_setting['marker_cluster'],'true') ?> />
	<p class="description"><?php _e('Apply Marker Cluster ?', 'wpgmp_google_map')?></p>
<?php
if( $unserialize_map_cluster_setting['marker_cluster']=='true' )
{
?>
<div id="disply_marker_cluster">
	<label for="title"><?php _e('Grid', 'wpgmp_google_map')?><span style="color:#F00;">*</span></label>
	<input type="text" size="15" name="cluster_setting[grid]" value="<?php echo $unserialize_map_cluster_setting['grid']; ?>" class="create_map" />
	<p class="description"><?php _e('Insert grid here.', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Style', 'wpgmp_google_map')?></label>
	<select name="cluster_setting[map_style]"> 
        <option value="-1"<?php selected($unserialize_map_cluster_setting['map_style'],'-1') ?>><?php _e('Default', 'wpgmp_google_map')?></option>
        <option value="0"<?php selected($unserialize_map_cluster_setting['map_style'],'0') ?>><?php _e('People', 'wpgmp_google_map')?></option>
        <option value="1"<?php selected($unserialize_map_cluster_setting['map_style'],'1') ?>><?php _e('Conversation', 'wpgmp_google_map')?></option>
        <option value="2"<?php selected($unserialize_map_cluster_setting['map_style'],'2') ?>><?php _e('Heart', 'wpgmp_google_map')?></option>
    </select>
    <p class="description"><?php _e('(Available options - Default,People,Conversation,Herat).', 'wpgmp_google_map')?></p>
    
	<label for="title"><?php _e('Max Zoom Level', 'wpgmp_google_map')?></label>
	<select name="cluster_setting[max_zoom]">
        <option value="1"<?php selected($unserialize_map_cluster_setting['max_zoom'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_cluster_setting['max_zoom'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_cluster_setting['max_zoom'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_cluster_setting['max_zoom'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_cluster_setting['max_zoom'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_cluster_setting['max_zoom'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_cluster_setting['max_zoom'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_cluster_setting['max_zoom'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_cluster_setting['max_zoom'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_cluster_setting['max_zoom'],'10') ?>>10</option>
        <option value="11"<?php selected($unserialize_map_cluster_setting['max_zoom'],'11') ?>>11</option>
        <option value="12"<?php selected($unserialize_map_cluster_setting['max_zoom'],'12') ?>>12</option>
        <option value="13"<?php selected($unserialize_map_cluster_setting['max_zoom'],'13') ?>>13</option>
        <option value="14"<?php selected($unserialize_map_cluster_setting['max_zoom'],'14') ?>>14</option>
    </select>
    <p class="description"><?php _e('(Available options - 1,2,3,4,5,6,8,9,10,11,12,13,14).', 'wpgmp_google_map')?></p>
</div>
<?php
}
else
{
?>
<div id="disply_marker_cluster" style="display:none;">
	<label for="title"><?php _e('Grid', 'wpgmp_google_map')?><span style="color:#F00;">*</span></label>
	<input type="text" size="15" name="cluster_setting[grid]" value="<?php echo $unserialize_map_cluster_setting['grid']; ?>" class="create_map" />
	<p class="description"><?php _e('Insert grid here.', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Style', 'wpgmp_google_map')?></label>
	<select name="cluster_setting[map_style]"> 
        <option value="-1"<?php selected($unserialize_map_cluster_setting['map_style'],'-1') ?>><?php _e('Default', 'wpgmp_google_map')?></option>
        <option value="0"<?php selected($unserialize_map_cluster_setting['map_style'],'0') ?>><?php _e('People', 'wpgmp_google_map')?></option>
        <option value="1"<?php selected($unserialize_map_cluster_setting['map_style'],'1') ?>><?php _e('Conversation', 'wpgmp_google_map')?></option>
        <option value="2"<?php selected($unserialize_map_cluster_setting['map_style'],'2') ?>><?php _e('Heart', 'wpgmp_google_map')?></option>
    </select>
    <p class="description"><?php _e('(Available options - Default,People,Conversation,Herat).', 'wpgmp_google_map')?></p>
    
	<label for="title"><?php _e('Max Zoom Level', 'wpgmp_google_map')?></label>
	<select name="cluster_setting[max_zoom]">
        <option value="1"<?php selected($unserialize_map_cluster_setting['max_zoom'],'1') ?>>1</option>
        <option value="2"<?php selected($unserialize_map_cluster_setting['max_zoom'],'2') ?>>2</option>
        <option value="3"<?php selected($unserialize_map_cluster_setting['max_zoom'],'3') ?>>3</option>
        <option value="4"<?php selected($unserialize_map_cluster_setting['max_zoom'],'4') ?>>4</option>
        <option value="5"<?php selected($unserialize_map_cluster_setting['max_zoom'],'5') ?>>5</option>
        <option value="6"<?php selected($unserialize_map_cluster_setting['max_zoom'],'6') ?>>6</option>
        <option value="7"<?php selected($unserialize_map_cluster_setting['max_zoom'],'7') ?>>7</option>
        <option value="8"<?php selected($unserialize_map_cluster_setting['max_zoom'],'8') ?>>8</option>
        <option value="9"<?php selected($unserialize_map_cluster_setting['max_zoom'],'9') ?>>9</option>
        <option value="10"<?php selected($unserialize_map_cluster_setting['max_zoom'],'10') ?>>10</option>
        <option value="11"<?php selected($unserialize_map_cluster_setting['max_zoom'],'11') ?>>11</option>
        <option value="12"<?php selected($unserialize_map_cluster_setting['max_zoom'],'12') ?>>12</option>
        <option value="13"<?php selected($unserialize_map_cluster_setting['max_zoom'],'13') ?>>13</option>
        <option value="14"<?php selected($unserialize_map_cluster_setting['max_zoom'],'14') ?>>14</option>
    </select>
    <p class="description"><?php _e('(Available options - 1,2,3,4,5,6,8,9,10,11,12,13,14).', 'wpgmp_google_map')?></p>
</div>
<?php
}
?>
</fieldset>
<fieldset>
    <legend><?php _e('Overlay Setting', 'wpgmp_google_map')?></legend>
	<label for="title"><?php _e('Overlay', 'wpgmp_google_map')?></label>
	<input type="checkbox" name="overlay_setting[overlay]" class="overlays_toggle" value="true"<?php checked($unserialize_map_overlay_setting['overlay'],'true') ?> />
	<p class="description"><?php _e('Overlays (if you checked,below information can not be empty).', 'wpgmp_google_map')?></p>
<?php
if( $unserialize_map_overlay_setting['overlay']=='true' )
{
?>
<div id="disply_overlays">
	<label for="title"><?php _e('Overlay Border Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_overlay_setting['overlay_border_color']; ?>" name="overlay_setting[overlay_border_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Width', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_width]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_width']; ?>" />&nbsp;px
	<p class="description"><?php _e('Insert here Overlay Width', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Height', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_height]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_height']; ?>" />&nbsp;px
	<p class="description"><?php _e('Insert here Overlay Height', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Font size', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_fontsize]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_fontsize']; ?>" />&nbsp;px
	<p class="description"><?php _e('Insert here Overlay Font Size', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Border width', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_border_width]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_border_width']; ?>" />&nbsp;px
	<p class="description"><?php _e('Insert here Overlay Border Width', 'wpgmp_google_map')?></p>
    <label for="title"><?php _e('Overlay Border Style', 'wpgmp_google_map')?></label>
    <select name="overlay_setting[overlay_border_style]">
        <option value="default"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'default') ?>><?php _e('Default', 'wpgmp_google_map')?></option>
        <option value="solid"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'solid') ?>><?php _e('Solid', 'wpgmp_google_map')?></option>
        <option value="dashed"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'dashed') ?>><?php _e('Dashed', 'wpgmp_google_map')?></option>
    </select>
    <p class="description"><?php _e('(Available options - Default,Solid,Dashed).', 'wpgmp_google_map')?></p>
</div>
<?php
}
else
{
?>
<div id="disply_overlays" style="display:none;">
	<label for="title"><?php _e('Overlay Border Color', 'wpgmp_google_map')?></label>
	<input type="text" value="<?php echo $unserialize_map_overlay_setting['overlay_border_color']; ?>" name="overlay_setting[overlay_border_color]" class="color {pickerClosable:true}" />
	<p class="description"><?php _e('(Default is red).', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Width', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_width]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_width']; ?>" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here Overlay Width', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Height', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_height]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_height']; ?>" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here Overlay Height', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Font size', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_fontsize]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_fontsize']; ?>" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here Overlay Font Size', 'wpgmp_google_map')?></p>
	<label for="title"><?php _e('Overlay Border width', 'wpgmp_google_map')?></label>
	<input type="text" name="overlay_setting[overlay_border_width]" class="create_map" value="<?php echo $unserialize_map_overlay_setting['overlay_border_width']; ?>" /><?php _e('&nbsp;px', 'wpgmp_google_map')?>
	<p class="description"><?php _e('Insert here Overlay Border Width', 'wpgmp_google_map')?></p>
    <label for="title"><?php _e('Overlay Border Style', 'wpgmp_google_map')?></label>
    <select name="overlay_setting[overlay_border_style]">
        <option value="default"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'default') ?>><?php _e('Default', 'wpgmp_google_map')?></option>
        <option value="solid"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'solid') ?>><?php _e('Solid', 'wpgmp_google_map')?></option>
        <option value="dashed"<?php selected($unserialize_map_overlay_setting['overlay_border_style'],'dashed') ?>><?php _e('Dashed', 'wpgmp_google_map')?></option>
    </select>
    <p class="description"><?php _e('(Available options - Default,Solid,Dashed).', 'wpgmp_google_map')?></p>
</div>
<?php
}
?>
</fieldset>
	<p class="submit">
	<input type="submit" name="update_map" id="submit" class="button button-primary" value="<?php _e('Update Map', 'wpgmp_google_map')?>">
	
    </p>
</div>
</form>
</div>
<?php } else {  ?>
<div class="wrap">
 
<div id="icon-options-general" class="icon32"><br></div>
<h2><?php _e('Manage Maps', 'wpgmp_google_map')?></h2><br />
<?php
$location_list_table = new Wpgmp_Maps_Table();
$location_list_table->prepare_items();
?>
<form method="post">
<?php
$location_list_table->search_box( 'search', 'search_id' );
$location_list_table->display();
?> 
</form> 
</div>
<?php
} 
}