<?php
class Wpgmp_Google_Map
{

var $code='';  // Do not edit this.

var $zoom=14; // Zoop Level.

var $center_lat = '37.09024'; // google map center location

var $center_lng = '-95.712891'; // google map center location

var $center_address = '';

var $divID='map'; // The div id where you want to 	place your google map

var $groupID = 'groupmap';

var $marker=array(); // Array to store markers information. 

var $instance=1;

var $width="";

var $height="";

var $title = 'WP Google Map Pro';

var $polygon = array();

var $polyline = array();

var $routedirections = array();

var $map_draw_polygon = "";

var $kml_layers_links="";

var $fusion_select="";

var $fusion_from="";

var $heat_map="";

var $temperature_unit="";

var $wind_speed_unit="";

var $map_width = "";

var $map_height = "";

var $map_start_point="";

var $map_end_point="";

var $map_multiple_point="";

var $map_scrolling_wheel="true";

var $map_pan_control="true";

var $map_zoom_control="true";

var $map_type_control="true"; 

var $map_scale_control="true";

var $map_street_view_control="true";

var $map_overview_control="true";

var $map_enable_info_window_setting = "";

var $map_info_window_width="";

var $map_info_window_height="";

var $map_info_window_shadow_style="";

var $map_info_window_border_radius="";

var $map_info_window_border_width=""; 

var $map_info_window_border_color="";

var $map_info_window_background_color="";

var $map_info_window_arrow_size="";

var $map_info_window_arrow_position="";

var $map_info_window_arrow_style="";

var $map_style_google_map="";

var $map_language="en";

var $polygon_border_color="#f22800";

var $polygon_background_color="#f22800";

var $map_draw_polyline="";

var $map_polyline_stroke_color="";

var $map_polyline_stroke_opacity="";

var $map_polyline_stroke_weight="";

var $map_type="ROADMAP";

var $map_45="";

var $map_layers="";

var $marker_cluster="";

var $grid="";

var $max_zoom="14";

var $style="default";

var $map_overlay = "";

var $map_overlay_border_color="#F22800";

var $map_overlay_width="200";

var $map_overlay_height="200";

var $map_overlay_fontsize="16";

var $map_overlay_border_width="200";

var $map_overlay_border_style="";

var $polygontriangle="polygontriangle"; 

var $visualrefresh = "false";

var $directionsDisplay = "directionsDisplay";

var $directionsService = "directionsService";

var $route_direction = "";

var $map_way_point = "";

var $route_direction_stroke_color = "";

var $route_direction_stroke_opacity = "";

var $route_direction_stroke_weight = "";

var $street_control = "";

var $street_view_close_button = "";

var $links_control = "";

var $street_view_pan_control = "";

var $enable_group_map = "";

var $group_data = "";

var $groups_markers = array();

var $infowindow = "infowindow";
	
function __construct()
{
	global $wpgmp_containers;
	
	$this->divID="map".(count($wpgmp_containers)+1);
	
	$wpgmp_containers[]=$this->divID;

}

// Intialized google map scripts.

private function start()
{

if( $this->center_address )
{ 
	$output = $this->getData($this->center_address);	

if( $output->status == 'OK' )
{
	$this->center_lat = $output->results[0]->geometry->location->lat;
	$this->center_lng = $output->results[0]->geometry->location->lng;
}

}

if( $this->map_width!='' && $this->map_height!='' )
{
  
	  $width = $this->map_width."px";
	  $height = $this->map_height."px";
   
}
elseif( $this->map_width=='' && $this->map_height!='' )
{
  
	  $width = "100%";
	  $height = $this->map_height."px";
   
}
elseif( $this->map_width=='' && $this->map_height=='' )
{
  
	  $width = "100%";
	  $height = "300px";
   
}
else
{
	  $width =  $this->map_width."px";
	  $height = "300px";
	  
}

$this->code='
<style>
#'.$this->divID.'
img {
max-width: none;
}
</style>'.'
<div id='.$this->divID.' style="width:'.$width.'; height:'.$height.';"></div>';

if( $this->enable_group_map=='true' )
{
	
	$this->code.='<div id='.$this->groupID.' style="width:'.$width.'; height:50px;">';
	
	for($gm=0; $gm<count($this->group_data); $gm++)
	{
		
		$this->code.='<img src="'.$this->group_data[$gm]->group_marker.'" onclick="maps_group_id('.$this->group_data[$gm]->group_map_id.')" style="padding:5px 8px 0px 8px; cursor:pointer;">';
	}
	
	$this->code.='</div>';
}

$this->code.='

<script type="text/javascript">';
	
$this->code.='google.load("maps", "3.7", {"other_params" : "sensor=false&libraries=places,weather,panoramio&language='.get_option('wpgmp_language').'"});

google.setOnLoadCallback(initialize);';	
	
if( $this->enable_group_map == 'true' )
{	
	$this->code.='var groups = [];';
}

if( !empty($this->visualrefresh) && $this->visualrefresh=='true' )
{
	$this->code.='google.maps.visualRefresh = '.$this->visualrefresh.';';
}

if( $this->marker_cluster=='true' )
{
$this->code.='var styles = [[{

        url: "'.plugins_url('/images/people35.png', __FILE__ ).'",

        height: 35,

        width: 35,

        anchor: [16, 0],

        textColor: "#ff00ff",

        textSize: 10

      }, {

        url: "'.plugins_url('images/people45.png', __FILE__ ).'",

        height: 45,

        width: 45,

        anchor: [24, 0],

        textColor: "#ff0000",

        textSize: 11

      }, {

        url: "'.plugins_url('/images/people55.png', __FILE__ ).'",

        height: 55,

        width: 55,

        anchor: [32, 0],

        textColor: "#ffffff",

        textSize: 12

      }], [{

        url: "'.plugins_url('/images/conv30.png', __FILE__ ).'",

        height: 27,

        width: 30,

        anchor: [3, 0],

        textColor: "#ff00ff",

        textSize: 10

      }, {

        url: "'.plugins_url('/images/conv40.png', __FILE__ ).'",

        height: 36,

        width: 40,

        anchor: [6, 0],

        textColor: "#ff0000",

        textSize: 11

      }, {

        url: "'.plugins_url('/images/conv50.png', __FILE__ ).'",

        width: 50,

        height: 45,

        anchor: [8, 0],

        textSize: 12

      }], [{

        url: "'.plugins_url('/images/heart30.png', __FILE__ ).'",

        height: 26,

        width: 30,

        anchor: [4, 0],

        textColor: "#ff00ff",

        textSize: 10

      }, {

        url: "'.plugins_url('/images/heart40.png', __FILE__ ).'",

        height: 35,

        width: 40,

        anchor: [8, 0],

        textColor: "#ff0000",

        textSize: 11

      }, {

        url: "'.plugins_url('/images/heart50.png', __FILE__ ).'",

        width: 50,

        height: 44,

        anchor: [12, 0],

        textSize: 12

      }]];

	  var markerClusterer = null;

      var imageUrl = "http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=FFFFFF,008CFF,000000&ext=.png";

	  var markclus = [];';
}

$this->code.='

function initialize() {';
	
if( $this->route_direction == 'true' )
{
	$this->code.=''.$this->directionsService.' = new google.maps.DirectionsService();';
	
	if(count($this->marker)<3)
	{
		
	  $this->code.='
		var start = "'.$this->map_way_point[0]->location_address.'";
		var end = "'.$this->map_way_point[1]->location_address.'"

		var request = {
			origin: start,
			destination: end,
			travelMode: google.maps.TravelMode.DRIVING,
			unitSystem: google.maps.DirectionsUnitSystem.METRIC,
			optimizeWaypoints: false
		};
		
		'.$this->directionsService.'.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK)
			{
				   var polyOpts = {
						strokeColor: "#'.$this->route_direction_stroke_color.'",
						strokeOpacity: '.$this->route_direction_stroke_opacity.',
						strokeWeight: '.$this->route_direction_stroke_weight.'
					}
	
				   var rendererOptions = {
						draggable: true,
						suppressMarkers: false, 
						suppressInfoWindows: false, 
						preserveViewport: false, 
						polylineOptions: polyOpts
					};
			
			'.$this->directionsDisplay.' = new google.maps.DirectionsRenderer(rendererOptions);
			'.$this->directionsDisplay.'.setMap('.$this->divID.');
			'.$this->directionsDisplay.'.setDirections(response);
					
			}
			else
			{
			console.info("could not get route");
			console.info(response);
			}
	  });
	';
	}
	elseif( count($this->marker)>2 )
	{
		
		$start_point = current($this->map_way_point);
		$end_point = end($this->map_way_point);
		$newarray = array_slice($this->map_way_point, 1, -1);
		foreach($newarray as $newarr)
		{
		 $new_array_value[] = $newarr->location_address;
		}
		
		$js_array = json_encode($new_array_value);

	  $this->code.='
		var start = "'.$start_point->location_address.'";
		var end = "'.$end_point->location_address.'";
		var waypts = [];
		checkboxArray = '.$js_array.';
		
		for(var mp=0; mp<checkboxArray.length; mp++){
			waypts.push({
				location:checkboxArray[mp],
				stopover:true});
		}
		

		var request = {
			origin: start,
			destination: end,
			waypoints: waypts,
			optimizeWaypoints: true,
			travelMode: google.maps.TravelMode.DRIVING
		};
		
		'.$this->directionsService.'.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK)
			{
				   var polyOpts = {
						strokeColor: "#'.$this->route_direction_stroke_color.'",
						strokeOpacity: '.$this->route_direction_stroke_opacity.',
						strokeWeight: '.$this->route_direction_stroke_weight.'
					}
	
				   var rendererOptions = {
						draggable: true,
						suppressMarkers: false, 
						suppressInfoWindows: false, 
						preserveViewport: false, 
						polylineOptions: polyOpts
					};
			
			'.$this->directionsDisplay.' = new google.maps.DirectionsRenderer(rendererOptions);
			'.$this->directionsDisplay.'.setMap('.$this->divID.');
			'.$this->directionsDisplay.'.setDirections(response);
					
			}
			else
			{
			console.info("could not get route");
			console.info(response);
			}
	  });
	';
	
	}
}

if( array($this->map_style_google_map) )
{
	$total_rows=count($this->map_style_google_map['mapfeaturetype']);

	for($i=0;$i<$total_rows;$i++)
	{
	
		if( empty($this->map_style_google_map['mapfeaturetype'][$i]) or empty($this->map_style_google_map['mapelementtype'][$i]) )
		continue;
	
		$map_stylers[]="{   featureType: '".$this->map_style_google_map['mapfeaturetype'][$i]."',  elementType: '".$this->map_style_google_map['mapelementtype'][$i]."',  stylers: [  { color: '#".$this->map_style_google_map['color'][$i]."' } ,{ visibility: '".$this->map_style_google_map['visibility'][$i]."' } ]  }";
	}
}

if( is_array($map_stylers) )
{

	$map_styles="var map_styles = [ ".implode(',',$map_stylers)." ];  ";

}

	$this->code.=$map_styles;
			  
	$this->code.='var latlng = new google.maps.LatLng('.$this->center_lat.','.$this->center_lng.');';

if( $this->street_control!='true' )
{		

	$this->code.='var mapOptions = {';
		
if( empty($this->map_45) )
{

	$this->code.='zoom: '.$this->zoom.',';

}
else
{

	$this->code.='zoom: 18,';	

}
		
$this->code.='scrollwheel: '.$this->map_scrolling_wheel.',
		
		panControl: '.$this->map_pan_control.',
		
		zoomControl: '.$this->map_zoom_control.',
		
		mapTypeControl: '.$this->map_type_control.',
		
		scaleControl: '.$this->map_scale_control.',
		
		streetViewControl: '.$this->map_street_view_control.',
		
		overviewMapControl: '.$this->map_overview_control.',

		center: latlng,

		mapTypeId: google.maps.MapTypeId.'.$this->map_type.'

		}

		'.$this->divID.' = new google.maps.Map(document.getElementById("'.$this->divID.'"), mapOptions);';
}
else
{		
		$this->code.='var panoOptions = {
    			position: latlng,
    			addressControlOptions: {
      			position: google.maps.ControlPosition.BOTTOM_CENTER
    		},
    			linksControl: '.$this->links_control.',
    			panControl: '.$this->street_view_pan_control.',
    			zoomControlOptions: {
      			style: google.maps.ZoomControlStyle.SMALL
    		},
    			enableCloseButton: '.$this->street_view_close_button.'
  		};

  		var panorama = new google.maps.StreetViewPanorama(document.getElementById("'.$this->divID.'"), panoOptions);
		';
}
		 		
if( !empty($this->map_45) )
{

	$this->code.=''.$this->divID.'.setTilt('.$this->map_45.');';

}

if( $map_styles )
$this->code.=''.$this->divID.'.setOptions({styles: map_styles});';

if( $this->map_layers=="KmlLayer" )
{

	$this->code.='
	
	var georssLayer = new google.maps.'.$this->map_layers.'({
	
	url: "'.$this->kml_layers_links.'"
	
	});
	
	georssLayer.setMap('.$this->divID.');';

}

if( $this->map_layers=="FusionTablesLayer" )
{
	$this->code.='fusionlayer = new google.maps.'.$this->map_layers.'({

	query: {

	select: "'.$this->fusion_select.'",

	from: "'.$this->fusion_from.'"
	
	},
	
	heatmap: {
		
	  enabled: '.$this->heat_map.'
	
	}
	
  });

	fusionlayer.setMap('.$this->divID.');';
}

if( $this->map_layers=="TrafficLayer" )
{
	$this->code.='
	
	var trafficLayer = new google.maps.'.$this->map_layers.'();
	
	trafficLayer.setMap('.$this->divID.');';
}

if( $this->map_layers=="TransitLayer" )
{
	$this->code.='

	var transitLayer = new google.maps.'.$this->map_layers.'();

	transitLayer.setMap('.$this->divID.');';
}

if( $this->map_layers=="WeatherLayer" )
{
	$this->code.='

	var weatherLayer = new google.maps.weather.'.$this->map_layers.'({

	windSpeedUnit: google.maps.weather.WindSpeedUnit.'.$this->wind_speed_unit.',

	temperatureUnits: google.maps.weather.TemperatureUnit.'.$this->temperature_unit.'

	});

	weatherLayer.setMap('.$this->divID.');

	var cloudLayer = new google.maps.weather.CloudLayer();

	cloudLayer.setMap('.$this->divID.');';
}

if( $this->map_layers=="BicyclingLayer" )
{
	$this->code.='

	var bikeLayer = new google.maps.'.$this->map_layers.'();

	bikeLayer.setMap('.$this->divID.');';

}

if( $this->map_layers=="PanoramioLayer" )
{
	$this->code.='

	var panoramioLayer = new google.maps.panoramio.'.$this->map_layers.'();

	panoramioLayer.setMap('.$this->divID.');';

}

if( $this->map_overlay=="true" )
{
	$this->code.='

	function CoordMapType(tileSize)
	{
		this.tileSize = tileSize;
	}
	
	CoordMapType.prototype.getTile = function(coord, zoom, ownerDocument)
	{
		var div = ownerDocument.createElement("div");

		div.innerHTML = coord;

		div.style.width = "200px";

		div.style.height = "300px";

		div.style.fontSize = "'.$this->map_overlay_fontsize.'px";

		div.style.borderStyle = "'.$this->map_overlay_border_style.'";

		div.style.borderWidth = "'.$this->map_overlay_border_width.'px";

		div.style.borderColor = "#'.$this->map_overlay_border_color.'";

		return div;
	};
	
	'.$this->divID.'.overlayMapTypes.insertAt(0, new CoordMapType(new google.maps.Size('.$this->map_overlay_width.', '.$this->map_overlay_height.')));';
}

if( $this->map_draw_polygon=='true' )
{
	if(is_array($this->polygon))
	{
		$this->code .=  'var triangleCoords = [';
		for($n=0;$n<count($this->polygon);$n++)
		{
		$this->code  .='new google.maps.LatLng('.$this->polygon[$n]['lat'].', '.$this->polygon[$n]['lng'].'),';
		}		  
		$this->code  .= '];'; 
		$this->code .= ''.$this->polygontriangle.' = new google.maps.Polygon({
			paths: triangleCoords,
			strokeColor: "#'.$this->polygon_border_color.'",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: "#'.$this->polygon_background_color.'",
			fillOpacity: 0.35
		});
		'.$this->polygontriangle.'.setMap('.$this->divID.');';
	}
}

if( $this->displaymarker!='' ) {
	$displaymarker = $this->displaymarker[0];
	$this->code.='
	var latLng = new google.maps.LatLng('.$displaymarker['lat'].','.$displaymarker['long'].')
	var marker = new google.maps.Marker({
				 map:'.$this->divID.',
				 position: latLng,
				 title:"'.$displaymarker['title'].'"
	});';
	
	$infos = str_replace(array("\r","\n"),'"+"',$displaymarker['message']);
						
	$this->code.='
	'.$this->infowindow.' =  new google.maps.InfoWindow({
												content: "'.$infos.'"
												});	';
												
	$this->code.="google.maps.event.addListener(marker, 'click', function() { ";											
	
	$this->code.="".$this->infowindow.".open(".$this->divID.",marker);
	
	});";
}

if( $this->marker_cluster=='true' )
{
	for($i=0; $i<count($this->markclus); $i++)
	{
		$this->code.='
		var latLng = new google.maps.LatLng('.$this->markclus[$i][lat].','.$this->markclus[$i][lng].')
		var marker'.$i.$this->divID.' = new google.maps.Marker({
		position: latLng,
		draggable:false,
		clickable: '.$this->markclus[$i]['click'].',
		size:new google.maps.Size(24, 32),
		icon: "'.$this->markclus[$i]['icon'].'",
		});';
		
		if( $this->markclus[$i]['info']!='' )
		{
			$infos = $this->markclus[$i]['info'];
			
			if( is_array($infos) )
			{
				
				$this->code.='
					var infoBubble;
					infoBubble'.$i.'= new InfoBubble({padding:20});';
					if( $this->map_enable_info_window_setting=='true' )
					{
						if( !empty($this->map_info_window_width) )
						{
							$this->code.='infoBubble'.$i.'.setMaxWidth('.$this->map_info_window_width.');';
						}
						
						if( !empty($this->map_info_window_height) )
						{
							$this->code.='infoBubble'.$i.'.setMaxHeight('.$this->map_info_window_height.');';
						}
						
						if(	!empty($this->map_info_window_shadow_style) )
						{
							$this->code.='infoBubble'.$i.'.setShadowStyle('.$this->map_info_window_shadow_style.');';
						}
						if( !empty($this->map_info_window_border_radius) )
						{
							$this->code.='infoBubble'.$i.'.setBorderRadius('.$this->map_info_window_border_radius.');';
						}
						if( !empty($this->map_info_window_border_width) )
						{
							$this->code.='infoBubble'.$i.'.setBorderWidth('.$this->map_info_window_border_width.');';
						}
						if( !empty($this->map_info_window_border_color) )
						{
							$this->code.='infoBubble'.$i.'.setBorderColor("#'.$this->map_info_window_border_color.'");';
						}
						
						if( !empty($this->map_info_window_background_color) )
						{
							$this->code.='infoBubble'.$i.'.setBackgroundColor("#'.$this->map_info_window_background_color.'");';
						}
						if( !empty($this->map_info_window_arrow_size) )
						{
							$this->code.='infoBubble'.$i.'.setArrowSize('.$this->map_info_window_arrow_size.');';
						}
						if( !empty($this->map_info_window_arrow_position) )
						{
							$this->code.='infoBubble'.$i.'.setArrowPosition('.$this->map_info_window_arrow_position.');';
						}
						if( !empty($this->map_info_window_arrow_style) )
						{
							$this->code.='infoBubble'.$i.'.setArrowStyle('.$this->map_info_window_arrow_style.');';
						}
					}
					else
					{
						$this->code.='infoBubble'.$i.'.setMinWidth(300);';
						$this->code.='infoBubble'.$i.'.setMaxWidth(400);';
						$this->code.='infoBubble'.$i.'.setMinHeight(200);';
						$this->code.='infoBubble'.$i.'.setBorderColor("#cccccc");';
					}
		
		
					if( !empty($infos['first']['title']) && !empty($infos['first']['message']) )
					{
						$infos_title_one = str_replace(array("\r","\n"),'"+"',$infos['first']['title']);
						$infos_mess_one = str_replace(array("\r","\n"),'"+"',$infos['first']['message']);
						$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_one.'", "'.$infos_mess_one.'");'."\n";
					}
					elseif( empty($infos['first']['title']) && !empty($infos['first']['message']) )
					{
						$infos_mess_one = str_replace(array("\r","\n"),'"+"',$infos['first']['message']);
						$this->code.='
						'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
							content: "'.$infos_mess_one.'"
						});';
					}
					
					if( !empty($infos['second']['title']) && !empty($infos['second']['message']) )
					{
						$infos_title_two = str_replace(array("\r","\n"),'"+"',$infos['second']['title']);
						$infos_mess_two = str_replace(array("\r","\n"),'"+"',$infos['second']['message']);
						$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_two.'", "'.$infos_mess_two.'");'."\n";
					}
					elseif( empty($infos['second']['title']) && !empty($infos['second']['message']) )
					{
						$infos_mess_two = str_replace(array("\r","\n"),'"+"',$infos['second']['message']);
						$this->code.='
						'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
							content: "'.$infos_mess_two.'"
						});';
					}
					
					if( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
					{
						$infos_title_three = str_replace(array("\r","\n"),'"+"',$infos['third']['title']);
						$infos_mess_three = str_replace(array("\r","\n"),'"+"',$infos['third']['message']);
						$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_three.'", "'.$infos_mess_three.'");'."\n";
					}
					elseif( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
					{
						$infos_mess_three = str_replace(array("\r","\n"),'"+"',$infos['third']['message']);
						$this->code.='
						'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
							content: "'.$infos_title_three.'"
						});';
					}
					
					if( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
					{
						$infos_title_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['title']);
						$infos_mess_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['message']);
						$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_four.'", "'.$infos_mess_four.'");'."\n";
					}
					elseif( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
					{
						$infos_mess_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['message']);
						$this->code.='
						'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
							content: "'.$infos_mess_four.'"
						});';
					}
					
					if( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
					{
						$infos_title_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['title']);
						$infos_mess_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['message']);
						$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_five.'", "'.$infos_mess_five.'");'."\n";
					}
					elseif( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
					{
						$infos_mess_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['message']);
						$this->code.='
						'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
							content: "'.$infos_title_five.'"
						});';
					}
			}
			elseif( $infos!='' )
			{
				$infos = str_replace(array("\r","\n"),'"+"',$infos);
					
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos.'"
				});
				';
			}
			
			
			$this->code.="google.maps.event.addListener(marker".$i.$this->divID.", 'click', function() { ";
					
			if( is_array($infos) )
			{
				if( !empty($infos['first']['title']) && !empty($infos['first']['message']) )
				{
					$this->code.="
							if (!infoBubble".$i.".isOpen()) {
							infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
						}";
				}
				elseif( empty($infos['first']['title']) && !empty($infos['first']['message']) )
				{
					$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
				}
				
				if( !empty($infos['second']['title']) && !empty($infos['second']['message']) )
				{
					$this->code.="
							if (!infoBubble".$i.".isOpen()) {
							infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
						}";
				}
				elseif( empty($infos['second']['title']) && !empty($infos['second']['message']) )
				{
					$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
				}
				
				if( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
				{
					$this->code.="
							if (!infoBubble".$i.".isOpen()) {
							infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
						}";
				}
				elseif( empty($infos['third']['title']) && !empty($infos['third']['message']) )
				{
					$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
				}
				
				if( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
				{
					$this->code.="
							if (!infoBubble".$i.".isOpen()) {
							infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
						}";
				}
				elseif( empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
				{
					$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
				}
				
				if( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
				{
					$this->code.="
							if (!infoBubble".$i.".isOpen()) {
							infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
						}";
				}
				elseif( empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
				{
					$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
				}
		
				$this->code.="
					google.maps.event.addListener(".$this->divID.", 'click', function() {
					infoBubble".$i.".close();
				});";
			}
			elseif( $infos!='' )
			{
				$this->code.="
						".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");
					google.maps.event.addListener(".$this->divID.", 'click', function() {
					".$this->infowindow."".$i.$this->divID.".close();
				});";
			}
			$this->code.="});"; 
		}
		
			$this->code.='markclus.push(marker'.$i.$this->divID.');';
	}	 
}
		
if( $this->map_draw_polyline=='true' )
{
	$this->code.='var path = [';

	for($i=0; $i < count($this->polyline); $i++)
	{
	
		  $this->code.='new google.maps.LatLng('.$this->polyline[$i][lat].','.$this->polyline[$i][lng].'),';
	}

	$this->code.='];
		
	var line = new google.maps.Polyline({
	  path: path,
	  strokeColor: "#'.$this->map_polyline_stroke_color.'",
	  strokeOpacity: '.$this->map_polyline_stroke_opacity.',
	  strokeWeight: '.$this->map_polyline_stroke_weight.'
	});
	line.setMap('.$this->divID.');';
}
		
		
for($i=0; $i < count($this->marker); $i++)
{
  
  if( empty($this->marker[$i]['draggable']) )
	 $this->marker[$i]['draggable']='false';

	 $this->code.='marker'.$i.$this->divID.'=new google.maps.Marker({
		map: '.$this->divID.',
		draggable:'.$this->marker[$i]['draggable'].',';
		$this->code.='position: new google.maps.LatLng('.$this->marker[$i]['lat'].', '.$this->marker[$i]['lng'].'), 
		title: "'.$this->marker[$i]['title'].'",
		clickable: '.$this->marker[$i]['click'].',
		icon: "'.$this->marker[$i]['icon'].'",
	  });';
  
 if( $this->enable_group_map=='true' )
 {
	  if($this->marker[$i]['group_id'])
	  {
	   $group_id = $this->marker[$i]['group_id'];
	  
	  $this->code .= "\n".'if(typeof groups.group'.$group_id.' == "undefined")
					  groups.group'.$group_id.' = [];
	  ';	  
		  
	   $this->code .= "\n".'groups.group'.$group_id.'.push(marker'.$i.$this->divID.');';	  
	 }
 }
 
// Creating an InfoWindow object

if( $this->marker[$i]['info']!='' )
{
	$infos = $this->marker[$i]['info'];
	
	if( is_array($infos) )
	{
		
		$this->code.='
			var infoBubble;
			infoBubble'.$i.'= new InfoBubble({padding:20});';
			if( $this->map_enable_info_window_setting=='true' )
			{
				if( !empty($this->map_info_window_width) )
				{
					$this->code.='infoBubble'.$i.'.setMaxWidth('.$this->map_info_window_width.');';
				}
				
				if( !empty($this->map_info_window_height) )
				{
					$this->code.='infoBubble'.$i.'.setMaxHeight('.$this->map_info_window_height.');';
				}
				
				if( !empty($this->map_info_window_shadow_style) )
				{
					$this->code.='infoBubble'.$i.'.setShadowStyle('.$this->map_info_window_shadow_style.');';
				}
				if( !empty($this->map_info_window_border_radius) )
				{
					$this->code.='infoBubble'.$i.'.setBorderRadius('.$this->map_info_window_border_radius.');';
				}
				if( !empty($this->map_info_window_border_width) )
				{
					$this->code.='infoBubble'.$i.'.setBorderWidth('.$this->map_info_window_border_width.');';
				}
				if( !empty($this->map_info_window_border_color) )
				{
					$this->code.='infoBubble'.$i.'.setBorderColor("#'.$this->map_info_window_border_color.'");';
				}
				
				if( !empty($this->map_info_window_background_color) )
				{
					$this->code.='infoBubble'.$i.'.setBackgroundColor("#'.$this->map_info_window_background_color.'");';
				}
				if( !empty($this->map_info_window_arrow_size) )
				{
					$this->code.='infoBubble'.$i.'.setArrowSize('.$this->map_info_window_arrow_size.');';
				}
				if( !empty($this->map_info_window_arrow_position) )
				{
					$this->code.='infoBubble'.$i.'.setArrowPosition('.$this->map_info_window_arrow_position.');';
				}
				if( !empty($this->map_info_window_arrow_style) )
				{
					$this->code.='infoBubble'.$i.'.setArrowStyle('.$this->map_info_window_arrow_style.');';
				}
			}
			else
			{
				$this->code.='infoBubble'.$i.'.setMinWidth(300);';
				$this->code.='infoBubble'.$i.'.setMaxWidth(400);';
				$this->code.='infoBubble'.$i.'.setMinHeight(200);';
				$this->code.='infoBubble'.$i.'.setBorderColor("#cccccc");';
			}


			if( !empty($infos['first']['title']) && !empty($infos['first']['message']) )
			{
				$infos_title_one = str_replace(array("\r","\n"),'"+"',$infos['first']['title']);
				$infos_mess_one = str_replace(array("\r","\n"),'"+"',$infos['first']['message']);
				$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_one.'", "'.$infos_mess_one.'");'."\n";
			}
			elseif( empty($infos['first']['title']) && !empty($infos['first']['message']) )
			{
				$infos_mess_one = str_replace(array("\r","\n"),'"+"',$infos['first']['message']);
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_mess_one.'"
				});';
			}
			
			if( !empty($infos['second']['title']) && !empty($infos['second']['message']) )
			{
				$infos_title_two = str_replace(array("\r","\n"),'"+"',$infos['second']['title']);
				$infos_mess_two = str_replace(array("\r","\n"),'"+"',$infos['second']['message']);
				$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_two.'", "'.$infos_mess_two.'");'."\n";
			}
			elseif( empty($infos['second']['title']) && !empty($infos['second']['message']) )
			{
				$infos_mess_two = str_replace(array("\r","\n"),'"+"',$infos['second']['message']);
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_mess_two.'"
				});';
			}
			
			if( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
			{
				$infos_title_three = str_replace(array("\r","\n"),'"+"',$infos['third']['title']);
				$infos_mess_three = str_replace(array("\r","\n"),'"+"',$infos['third']['message']);
				$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_three.'", "'.$infos_mess_three.'");'."\n";
			}
			elseif( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
			{
				$infos_mess_three = str_replace(array("\r","\n"),'"+"',$infos['third']['message']);
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_title_three.'"
				});';
			}
			
			if( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
			{
				$infos_title_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['title']);
				$infos_mess_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['message']);
				$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_four.'", "'.$infos_mess_four.'");'."\n";
			}
			elseif( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
			{
				$infos_mess_four = str_replace(array("\r","\n"),'"+"',$infos['fourth']['message']);
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_mess_four.'"
				});';
			}
			
			if( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
			{
				$infos_title_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['title']);
				$infos_mess_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['message']);
				$this->code.='infoBubble'.$i.'.addTab("'.$infos_title_five.'", "'.$infos_mess_five.'");'."\n";
			}
			elseif( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
			{
				$infos_mess_five = str_replace(array("\r","\n"),'"+"',$infos['fifth']['message']);
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_title_five.'"
				});';
			}

	}
	elseif( $infos!='' )
	{
		$infos = str_replace(array("\r","\n"),'"+"',$infos);
			
		$this->code.='
		'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
			content: "'.$infos.'"
		});
		';
	}
	
	
	$this->code.="google.maps.event.addListener(marker".$i.$this->divID.", 'click', function() { ";
			
	if( is_array($infos) )
	{
		if( !empty($infos['first']['title']) && !empty($infos['first']['message']) )
		{
			$this->code.="
					if (!infoBubble".$i.".isOpen()) {
					infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
				}";
		}
		elseif( empty($infos['first']['title']) && !empty($infos['first']['message']) )
		{
			$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
		}
		
		if( !empty($infos['second']['title']) && !empty($infos['second']['message']) )
		{
			$this->code.="
					if (!infoBubble".$i.".isOpen()) {
					infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
				}";
		}
		elseif( empty($infos['second']['title']) && !empty($infos['second']['message']) )
		{
			$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
		}
		
		if( !empty($infos['third']['title']) && !empty($infos['third']['message']) )
		{
			$this->code.="
					if (!infoBubble".$i.".isOpen()) {
					infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
				}";
		}
		elseif( empty($infos['third']['title']) && !empty($infos['third']['message']) )
		{
			$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
		}
		
		if( !empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
		{
			$this->code.="
					if (!infoBubble".$i.".isOpen()) {
					infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
				}";
		}
		elseif( empty($infos['fourth']['title']) && !empty($infos['fourth']['message']) )
		{
			$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
		}
		
		if( !empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
		{
			$this->code.="
					if (!infoBubble".$i.".isOpen()) {
					infoBubble".$i.".open(".$this->divID.",marker".$i.$this->divID.");
				}";
		}
		elseif( empty($infos['fifth']['title']) && !empty($infos['fifth']['message']) )
		{
			$this->code.="".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");";
		}

		$this->code.="
			google.maps.event.addListener(".$this->divID.", 'click', function() {
			infoBubble".$i.".close();
		});";
	}
	elseif( $infos!='' )
	{
		$this->code.="
				".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");
			google.maps.event.addListener(".$this->divID.", 'click', function() {
			".$this->infowindow."".$i.$this->divID.".close();
		});";
	}
	$this->code.="});"; 
}

}

if( $this->marker_cluster=='true' )
{
	$this->code.='markerClusterer = new MarkerClusterer('.$this->divID.', markclus , {
	
	gridSize:'.$this->grid.',
	
	maxZoom:'.$this->max_zoom.',
	
	styles: styles['.$this->style.']
	
	});';
}
	
$this->code.='}';


if( $this->enable_group_map == 'true' )
{	

$this->code.='
function maps_group_id(group_id)
{
	
position = false;
var bounds = new google.maps.LatLngBounds();
if(groups)
{	
 for( var n in groups){
 
   if(n.indexOf("group") != "-1"){
	 if(n == "group"+group_id)
	 {
	   for(i = 0; i <groups[n].length; i++){
		if( typeof groups[n][i].getMap() == "null");
		groups[n][i].setMap('.$this->divID.');
		bounds.extend(groups[n][i].getPosition());
	   } 
	   position = true;  
	 }else{
	   for(i = 0; i <groups[n].length; i++){
		groups[n][i].setMap(null);
	   }
	 }
   }
 }
}	
		
if( position == true ) 
'.$this->divID.'.fitBounds(bounds);
}';

}

$this->code.='</script>';

}

// Add markers to google map.

public function addMarkerCluster($lat,$lng,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map')
{
	$count=count($this->markclus);	

	$this->markclus[$count]['lat']=$lat;

	$this->markclus[$count]['lng']=$lng;

	$this->markclus[$count]['map']=$map;

	$this->markclus[$count]['title']=$title;

	$this->markclus[$count]['click']=$click;

	$this->markclus[$count]['icon']=$icon;

	$this->markclus[$count]['info']=$info;
}

public function addDisplayMarker($lat,$long,$title,$message)
{
	$count=count($this->displaymarker);	
	
	$this->displaymarker[$count]['lat']=$lat;
	
	$this->displaymarker[$count]['long']=$long;
	
	$this->displaymarker[$count]['title']=$title;
	
	$this->displaymarker[$count]['message']=$message;
}

public function addMarker($lat,$lng,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map',$draggable='',$animation='',$group_id='')
{
	$count=count($this->marker);	
	
	$this->marker[$count]['lat']=$lat;
	
	$this->marker[$count]['lng']=$lng;
	
	$this->marker[$count]['map']=$map;
	
	$this->marker[$count]['title']=$title;
	
	$this->marker[$count]['click']=$click;
	
	$this->marker[$count]['icon']=$icon;
	
	$this->marker[$count]['info']=$info;
	
	$this->marker[$count]['draggable']=$draggable;
	
	$this->marker[$count]['animation']=$animation;
	
	if($group_id)
	$this->marker[$count]['group_id']=$group_id;
}

public function addMarkerByAddress($address,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map')
{
	$status = false;

	$output = $this->getData($address);

	if( $output->status == 'OK' )
	{
	   $lat = $output->results[0]->geometry->location->lat;

	   $lng = $output->results[0]->geometry->location->lng;

	   $status = true;
	}

	if( $status )
	{
		$count=count($this->marker);	

		$this->marker[$count]['lat']=$lat;

		$this->marker[$count]['lng']=$lng;

		$this->marker[$count]['map']=$map;

		$this->marker[$count]['title']=$title;

		$this->marker[$count]['click']=$click;

		$this->marker[$count]['icon']=$icon;

		$this->marker[$count]['info']=$info;
    }		
}

public function addroutedirections($lat,$lng)
{
	$count=count($this->routedirections);	
	
	$this->routedirections[$count]['lat']=$lat;
	
	$this->routedirections[$count]['lng']=$lng;
}

public function addpolyline($lat,$lng)
{
	$count=count($this->polyline);	
	
	$this->polyline[$count]['lat']=$lat;
	
	$this->polyline[$count]['lng']=$lng;
}

public function addPolygon($lat,$lng)
{
	$count=count($this->polygon);
	
	$this->polygon[$count]['lat']=$lat;
	
	$this->polygon[$count]['lng']=$lng;
}

// Call this function to create a google map.

public function showmap()
{
	$this->start();

	$this->instance++;

	return $this->code;
}

public function getData($address)
{
  $url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';

  if( ini_get('allow_url_fopen') )
  {
		$geocode2 = wp_remote_get($url);

		$geocode=$geocode2['body'];
  }
  elseif( !ini_get('allow_url_fopen') )
  {
		$geocode2 = wp_remote_get($url);

		$geocode=$geocode2['body'];
  }
  else
  {
	echo "Configure your php.ini settings. allow_url_fopen may be disabled";

	exit;
  }	

  return json_decode($geocode);
}
}