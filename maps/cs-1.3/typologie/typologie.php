<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Content" />
	<meta name="keywords" content="keywords" />
	<title>TYPOLOGIE</title>
	<link href='../css/static.css' rel='stylesheet' type='text/css' />
	<link type="text/css" rel="stylesheet" href="../css/styles.css"/>
	<link type="text/css" href="../js/jquery-ui/css/smooth/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../js/jquery-ui/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="../js/d3/d3.min.js"></script>
	<script type="text/javascript" src="../js/d3/d3.time.js"></script>
	<script type="text/javascript">

	$(function() {
// VARIABLES /////////// VARIABLES /////////// VARIABLES /////////// VARIABLES /////////// VARIABLES /////////// VARIABLES /////////// VARIABLES ///////////
		var typologie_width = 600,
			typologie_height = 600,
			typologie_margin = 60,
						
			periode = "day",
			
			total_p_dist=3,
			total_p_reg=10,
			
			p_km=1,
			p_tps_moy_tp=1,
			p_tps_moy_v=1,
			
			p_ectyp_tp=1,
			p_ectyp_v=1,
			p_amplitude_tp=1,
			p_amplitude_v=1,
			p_moy_ectypmob_tp_1h=1,
			p_moy_ectypmob_tp_30m=1,
			p_moy_ectypmob_tp_15m=1,
			p_moy_ectypmob_v_1h=1,
			p_moy_ectypmob_v_30m=1,
			p_moy_ectypmob_v_15m=1,
			
			typologie_x = d3.scale.linear().domain([0, 10]).range([0, typologie_width]), 
			typologie_y = d3.scale.linear().domain([10, 0]).range([0, typologie_height]),
			typologie_xAxis = d3.svg.axis().scale(typologie_x).ticks(12).tickSize(5).orient("top"),
			typologie_yAxis = d3.svg.axis().scale(typologie_y).ticks(15).orient("right").tickSize(0).tickSubdivide(true);
				
// INTERFACE /////////////// INTERFACE /////////////// INTERFACE /////////////// INTERFACE /////////////// INTERFACE /////////////// INTERFACE /////////////
	
		// Choix de la période
		$("#typologie_choix_periode").buttonset();
		
		$("#work").click(function() {
			periode = $(this).val();
			redraw_typologie();
		});
		
		$("#day").click(function() {
			periode = $(this).val();
			redraw_typologie();
		});
		
		$("#all").click(function() {
			periode = $(this).val();
			redraw_typologie();
		});
		
		// Indicateurs de la distance
		$( "#slider_km" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_km=ui.value;
				typologie_maj_total_dist();
				redraw_typologie();
			}
		});
		$( "#slider_tps_moy_tp" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_tps_moy_tp=ui.value;
				typologie_maj_total_dist();
				redraw_typologie();
			}
		});
		$( "#slider_tps_moy_v" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_tps_moy_v=ui.value;
				typologie_maj_total_dist();
				redraw_typologie();
			}
		});
		
		// Indicateurs de régularité
		$( "#slider_ectyp_tp" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_ectyp_tp=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_ectyp_v" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_ectyp_v=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_amplitude_tp" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_amplitude_tp=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_amplitude_v" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_amplitude_v=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_tp_1h" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_tp_1h=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_tp_30m" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_tp_30m=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_tp_15m" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_tp_15m=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_v_1h" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_v_1h=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_v_30m" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_v_30m=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
		$( "#slider_moy_ectypmob_v_15m" ).slider({
			animate: 100, min: 0, max: 10, step: 1,	value:5,
			stop: function( event, ui ) {
				p_moy_ectypmob_v_15m=ui.value;
				typologie_maj_total_reg();
				redraw_typologie();
			}
		});
	
		
// CALCULS ///////////// CALCULS ////////// CALCULS ////////// CALCULS ////////// CALCULS ////////// CALCULS ////////// CALCULS ////////// CALCULS ////////

		function typologie_maj_total_dist()  {
			total_p_dist = p_km+p_tps_moy_tp+p_tps_moy_v;
		}
		
		function typologie_maj_total_reg()  {
			total_p_reg = p_ectyp_tp+p_ectyp_v+p_amplitude_tp+p_amplitude_v+p_moy_ectypmob_tp_1h+p_moy_ectypmob_tp_30m+p_moy_ectypmob_tp_15m+p_moy_ectypmob_v_1h+p_moy_ectypmob_v_30m+p_moy_ectypmob_v_15m;
		}
		
		function typologie_valeur_x(d)  {
			if (periode == "work") 
				var val_x=(p_km*d.km+p_tps_moy_tp*d.tps_moy_tp_7_20+p_tps_moy_v*d.tps_moy_v_7_20)/total_p_dist;
			else if (periode == "day") // Valeur par défaut
				var val_x=(p_km*d.km+p_tps_moy_tp*d.tps_moy_tp_6_00+p_tps_moy_v*d.tps_moy_v_6_00)/total_p_dist;
			else if (periode == "all") 
				var val_x=(p_km*d.km+p_tps_moy_tp*d.tps_moy_tp+p_tps_moy_v*d.tps_moy_v)/total_p_dist;
								
			return val_x;
		}
		
		function typologie_valeur_y(d)  {
			if (periode == "work") 
				var val_y=(p_ectyp_tp*d.ectyp_tp_7_20+p_ectyp_v*d.ectyp_v_7_20+p_amplitude_tp*d.amplitude_tp_7_20+p_amplitude_v*d.amplitude_v_7_20+p_moy_ectypmob_tp_1h*d.moy_ectypmob_tp_1h_7_20+p_moy_ectypmob_tp_30m*d.moy_ectypmob_tp_30m_7_20+p_moy_ectypmob_tp_15m*d.moy_ectypmob_tp_15m_7_20+p_moy_ectypmob_v_1h*d.moy_ectypmob_v_1h_7_20+p_moy_ectypmob_v_30m*d.moy_ectypmob_v_30m_7_20+p_moy_ectypmob_v_15m*d.moy_ectypmob_v_15m_7_20)/total_p_reg;
			else if (periode == "day") // Valeur par défaut
				var val_y=(p_ectyp_tp*d.ectyp_tp_6_00+p_ectyp_v*d.ectyp_v_6_00+p_amplitude_tp*d.amplitude_tp_6_00+p_amplitude_v*d.amplitude_v_6_00+p_moy_ectypmob_tp_1h*d.moy_ectypmob_tp_1h_6_00+p_moy_ectypmob_tp_30m*d.moy_ectypmob_tp_30m_6_00+p_moy_ectypmob_tp_15m*d.moy_ectypmob_tp_15m_6_00+p_moy_ectypmob_v_1h*d.moy_ectypmob_v_1h_6_00+p_moy_ectypmob_v_30m*d.moy_ectypmob_v_30m_6_00+p_moy_ectypmob_v_15m*d.moy_ectypmob_v_15m_6_00)/total_p_reg;
			else if (periode == "all")
				var val_y=(p_ectyp_tp*d.ectyp_tp+p_ectyp_v*d.ectyp_v+p_amplitude_tp*d.amplitude_tp+p_amplitude_v*d.amplitude_v+p_moy_ectypmob_tp_1h*d.moy_ectypmob_tp_1h+p_moy_ectypmob_tp_30m*d.moy_ectypmob_tp_30m+p_moy_ectypmob_tp_15m*d.moy_ectypmob_tp_15m+p_moy_ectypmob_v_1h*d.moy_ectypmob_v_1h+p_moy_ectypmob_v_30m*d.moy_ectypmob_v_30m+p_moy_ectypmob_v_15m*d.moy_ectypmob_v_15m)/total_p_reg;
			
			return val_y;
		}
		
		function typologie_couleur(d)  {
			var val_vert = 255-((typologie_valeur_x(d)*255)/10);
			var val_bleu = 255-((typologie_valeur_y(d)*255)/10);
				
			return "rgb(0,"+val_vert+","+val_bleu+")";	
		}




	
///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW ///////// DRAW /////////
		
		function draw_typologie() {
					
			var svg = d3.select("#typologie").append("svg:g")
				  .attr("width", typologie_width+2*typologie_margin)
				  .attr("height", typologie_height+2*typologie_margin)
				  .attr("transform", "translate("+typologie_margin+", "+typologie_margin+")");
			
			// Axes
			svg.append("svg:line")
				 .attr("class", "typologie_axe").attr("x1", 0).attr("y1", typologie_height).attr("x2", typologie_width).attr("y2", typologie_height)
 				 .attr("stroke-opacity", "0.8").attr("stroke", "black").style("stroke-width","0.5");
 			svg.append("svg:line")
				 .attr("class", "typologie_axe").attr("x1", 0).attr("y1", typologie_height).attr("x2", 0).attr("y2", 0)
 				 .attr("stroke-opacity", "0.8").attr("stroke", "black").style("stroke-width","0.5");
 			
 			// Points	 
 			d3.json("typologie.json", function(json) {
				data=json;
				
				var g = svg.selectAll(".lieux").data(data).enter()
					.append("svg:g")
					.attr("class", "lieux");
						
				var points = g.append("svg:circle")
					.attr("class", "ronds")
					.attr("z-index", 10);
							
				var noms = g.append("svg:text")
					.attr("class", "noms")
					.attr("text-anchor", "start")
					.attr("fill", "#111")
					.attr("opacity", 0.1)
					.style("font-size", 9)
					.attr("z-index", 5)
					.text(function(d) { return d.nom; });
				
				
				$(".lieux").mouseover(function() { $(".noms", this).attr("opacity", 1).css("font-size", 12); }) ;
				$(".lieux").mouseout(function() { $(".noms", this).attr("opacity", 0).css("font-size", 9); }) ;			


			})
			redraw_typologie();	
		}

// REDRAW ///////// REDRAW ///////// REDRAW ///////// REDRAW ///////// REDRAW ///////// REDRAW ///////// REDRAW ///////// REDRAW /////////

		function redraw_typologie()  {
		
			// alert("ponderation de la distance ="+ponderation_dist);
			d3.json("typologie.json", function(json){
				data=json;
				
				var points = d3.selectAll(".ronds").data(data)
					.transition()
					.duration(150)	
					.attr("cx", function(d){return typologie_x(typologie_valeur_x(d));})	
					.attr("cy", function(d){return typologie_y(typologie_valeur_y(d));})
					.attr("r", 5)
					.attr("stroke-width",3)
					.attr("fill", function(d){return typologie_couleur(d);});		
					//.attr("fill", "#000")
					//.attr("stroke", "#000")
					//.attr("fill-opacity", function(d){return 1-(valeur_x(d)/10);})
					//.attr("stroke-opacity", function(d){return 1-(valeur_y(d)/10);});

				d3.selectAll(".noms").data(data)
					.transition()
					.duration(150)	
					.attr("x", function(d){return typologie_x(typologie_valeur_x(d));})	
					.attr("y", function(d){return typologie_y(typologie_valeur_y(d));});					
			})
		}		
		draw_typologie();
	});
		
	
// HTML /////////////////// HTML ////////////// HTML ////////////// HTML ////////////// HTML ////////////// HTML ////////////// HTML ////////////
	</script>
</head>
<body>
	<div id="wrapper">
		<table>
			<tr>
				<td>
					<svg id="typologie" xmlns="http://www.w3.org/2000/svg" width="1000" height="700"></svg>
				</td>
				
				<td>
					<div id="typologie_choix_periode">
						<h1>Choix de la période représentée</h1>
						<form id="periode">
							<input type="radio" id="work" name="choix_periode" value="work"/><label for="work">Working time</label>
							<input type="radio" id="day" name="choix_periode" value="day" checked="checked" /><label for="day">day</label>
							<input type="radio" id="all" name="choix_periode" value="all" /><label for="all">All</label>
						</form>
					</div>

					<div id="typologie_indicateurs">
						<h1>Pondération des indicateurs</h1>
						<h2>Indicateurs relatifs à la distance</h2>
						Distance kilomètrique<div id="slider_km" style="width:100px;"></div>
						Temps moyen en TP<div id="slider_tps_moy_tp" style="width:100px;"></div>
						Temps moyen en voiture<div id="slider_tps_moy_v" style="width:100px;"></div>
						
						<h2>Indicateurs relatifs à la régularité</h2>  <!-- Fréquence des transports publics + embouteillages -->
						Ecart-type TP<div id="slider_ectyp_tp" style="width:100px;"></div>
						Ecart-type voiture<div id="slider_ectyp_v" style="width:100px;"></div>
						Amplitude en TP<div id="slider_amplitude_tp" style="width:100px;"></div>
						Amplitude pour la voiture<div id="slider_amplitude_v" style="width:100px;"></div>
						Moyenne des écart-types mobile TP - 1 heure<div id="slider_moy_ectypmob_tp_1h" style="width:100px;"></div>
						Moyenne des écart-types mobile TP - 30 minutes<div id="slider_moy_ectypmob_tp_30m" style="width:100px;"></div>
						Moyenne des écart-types mobile TP - 15 minutes<div id="slider_moy_ectypmob_tp_15m" style="width:100px;"></div>
						Moyenne des écart-types mobile Voiture - 1 heure<div id="slider_moy_ectypmob_v_1h" style="width:100px;"></div>
						Moyenne des écart-types mobile Voiture - 30 minutes<div id="slider_moy_ectypmob_v_30m" style="width:100px;"></div>
						Moyenne des écart-types mobile Voiture - 15 minutes<div id="slider_moy_ectypmob_v_15m" style="width:100px;"></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>