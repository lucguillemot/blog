
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Content" />
	<meta name="keywords" content="keywords" />
	<title>Commuting Scales 1.2</title>
	<style>body { margin:0; padding:0; } #map { position:absolute; top:0; bottom:0; width:100%; }</style>
	<link type="text/css" href="js/jquery-ui/css/smooth/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
	<link href='css/styles.css' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/polymaps/polymaps.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
	<script type="text/javascript" src="js/d3/d3.min.js"></script>
	<script type="text/javascript" src="js/d3/d3.time.js"></script>

<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://d3js.org/topojson.v1.min.js"></script>

	<script type="text/javascript" src="js_cs/variables.js"></script>
	<script type="text/javascript" src="js_cs/draw.js"></script>
	<script type="text/javascript" src="js_cs/draw_map.js"></script>
	<script type="text/javascript" src="js_cs/draw_typology.js"></script>
	<script type="text/javascript" src="js_cs/draw_histogram.js"></script>
	<script type="text/javascript" src="js_cs/interface.js"></script>
	<script type="text/javascript" src="js_cs/interface_typology.js"></script>
	<script type="text/javascript" src="js_cs/interface_topology.js"></script>
	<script type="text/javascript" src="js_cs/interface_map.js"></script>
	<script type="text/javascript" src="js_cs/functions.js"></script>
	<script type="text/javascript" src="js_cs/functions_topology.js"></script>
	<script type="text/javascript" src="js_cs/functions_typology.js"></script>
	
	<script type="text/javascript">

	$(function() {
		
			draw_map();
			setTimeout(draw, 2000);
			draw_histogram();
		
    		$("#browser_div").hide();
    		$("#CS").show();
     }); 

	</script>
</head>
<body>
	<div id="wrapper">
			<div id="vue_euclidien">
				<div id="map"></div>
    	</div>
	</div>	
    
   </body>
</html>