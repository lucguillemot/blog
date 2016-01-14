<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Content" />
	<meta name="keywords" content="keywords" />
	<title>STATIC</title>
	<link href='css/static.css' rel='stylesheet' type='text/css' />
	<link type="text/css" rel="stylesheet" href="css/graph.css"/>
	<link type="text/css" href="js/jquery-ui/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="js/jquery-ui/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="js/d3/d3.min.js"></script>
	<script type="text/javascript" src="js/d3/d3.time.js"></script>
	<script type="text/javascript" src="data/static/data_graph.js"></script>
	<style>#demo-frame > div.demo { padding: 10px; }</style>
	<script type="text/javascript">

	$(function() {
	
		var data;
		var couleurTP = "#0087cb";
		var couleurVOIT = "#ef7d29";
		var parse = d3.time.format("%H:%M").parse;
		var valEchelle = 300;
		var width = 1280;
		var	w = width/2,
			he = 800,
			h_start_ = "00:01",
			h_end_ = "23:55",
			h_start = parse(h_start_),
			h_end = parse(h_end_),
			// Histogramme / timeline
			scaleChartX = d3.scale.linear().domain([1,288]).range([0,1200]), // scale pour la largeur du graphique (nombre de laps de temps)
			scaleChartY = d3.scale.linear().domain([0,370]).range([0, 300]), // scale pour la hauteur du graphique (nombre de minutes)
			scaleLabelY = d3.scale.linear().domain([0,370]).range([300, 0]), // scale pour les labels sur la hauteur du graphique (nombre de minutes)
			t = d3.time.scale().domain([h_start, h_end]).range([0, 1200]), // scale pour calculer les valeurs de la timeline (de minuit à 23h55 sur 1280 pixels)
			AxisT = d3.svg.axis().scale(t).ticks(24).tickSize(-5).orient("bottom"), // Axe pour afficher valeurs de la timeline
			// Roue			
			scaleKM = d3.scale.linear().domain([1, 85]).range([1, 0]), // échelle pour calculer l'opacité des points de la roue.
			scaleX = d3.scale.linear().domain([0, valEchelle]).range([0, w]); // échelle cartographique pour la roue.
			// Graphique
			width_graph = 500,
			height_graph = 700,
			margin = 60,
			tpsMax = 500,
			y = d3.scale.linear().domain([0, TP1.length]).range([0, height_graph]),
			xTP = d3.scale.linear().domain([0, tpsMax]).range([width_graph/2, 0]).clamp(500), // échelle linéaire TP
			xVoiture = d3.scale.linear().domain([0, tpsMax]).range([width_graph/2, width_graph]), // échelle linéaire VOITURE 
			t_graph = d3.time.scale().domain([h_start, h_end]).range([0, height_graph]), // time scale pour afficher les horaires
			xAxisTP = d3.svg.axis().scale(xTP).ticks(12).tickSize(5).orient("top"), // axe horizontal pour les TP (nombre de minutes)
			xAxisVoiture = d3.svg.axis().scale(xVoiture).ticks(12).tickSize(5).orient("top"),//idem pour la voiture.
			yAxisGauche = d3.svg.axis().scale(t_graph).ticks(15).orient("left").tickSize(0).tickSubdivide(false),
			yAxisDroit = d3.svg.axis().scale(t_graph).ticks(15).orient("right").tickSize(0).tickSubdivide(true);

		
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

		function draw() {
			
			// Affichage de la roue
			d3.json("data/static/staticTopologie_6_22.json", function(json) {
				data=json;
				
				var g = d3.select("#svg_roue").append("svg:g").attr("transform", function() {var transX=w/2;var transY=he/2; return "translate("+transX+", "+transY+")"});
				
				//cercle 1h00
				g.append("svg:circle").attr("id", "iso90").attr("cx", 0).attr("cy", 0).attr("r", function(){return scaleX(60);}).attr("fill", "none").attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");				
				
				//cercle 1h30
				g.append("svg:circle").attr("id", "iso90").attr("cx", 0).attr("cy", 0).attr("r", function(){return scaleX(90);}).attr("fill", "none").attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");				
				
				// Affichage légende
				g.append("svg:text").attr("x", 0).attr("y", function(){return scaleX(90);}).style("fill", "black").text("1h30").attr("transform", "rotate(285,0,0)");
				g.append("svg:text").attr("x", 0).attr("y", function(){return scaleX(60);}).style("fill", "black").text("1h00").attr("transform", "rotate(285,0,0)");

				var groupe_lines = g.selectAll(".groupe_lines").data(data).enter()
					.append("svg:g")
					.attr("class", "groupe_lines")
					.attr("transform", function(d){var angle=d.angle_constant;return "rotate("+angle+", 0, 0)"});
				
 				var lines = groupe_lines.append("svg:line")
 					.attr("class", "line")
 					.attr("x1", function(d){return scaleX(d.tps_mini_tp)})
 					.attr("y1", 0)
 					.attr("x2", function(d){return scaleX(d.tps_maxi_tp)})
 					.attr("y2", 0)
 					.style("stroke-width", 6)
 					.style("stroke", "black")
 					.attr("stroke-opacity", function(d){return scaleKM(d.dist_km);});
								
				var groupe_circles = g.selectAll(".groupe_circles").data(data).enter()
					.append("svg:g")
					.attr("class", "groupe_circles")
					.attr("transform", function(d){var angle=d.angle_constant;return "rotate("+angle+", 0, 0)"});
					
				var circles = groupe_circles.append("svg:circle")
					.attr("class", "circle")
					.attr("cx", function(d){return scaleX(d.tps_moyen_tp)})
					.attr("cy", 0)
					.attr("r", 2)
					.attr("fill", "black")
					.attr("fill-opacity", function(d){return scaleKM(d.dist_km);})
					.attr("stroke-width", "0");
	
		
				var title = groupe_circles.append("svg:text")
					.attr("class", "title")
					.attr("opacity", "1")
					.attr("text-anchor", "start")
					.style("font-size", 8)
					.style("fill", "black")
					.attr("x", scaleX(120))
					.attr("y", 0)
					.attr("z-index", 1)
					.style("baseline-shift", "-2px")
					.text(function(d) { return d.nom; });



				g.append("svg:rect").attr("class", "epfl").attr("x", -2).attr("y", -2).attr("width", 4).attr("height", 4).attr("fill", "black");
			});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

			// Affichage du graphique de la timeline
			d3.json("data/dynamique/lieux/tps_moyen_horaire.json", function(json) {
				data=json;
				
				var chart = d3.select("#svg_histogramme").append("svg:g");
    			
    			var AxisChartY = d3.svg.axis().scale(scaleLabelY).ticks(4).tickSize(2).orient("left"); // Axe pour afficher valeurs de minutes

				// Affichage de l'axe timeline y
				var axeY = d3.select("#svg_histogramme");
					axeY.append("svg:g")
						.attr("transform", "translate(30, 0)")
						.attr("stroke-width", 2)
						.call(AxisChartY);
				//Chart TP	
				chart.selectAll(".chart_tp")
						.data(data).enter().append("svg:rect")
					.attr("class","chart_tp")
					.attr("transform", "translate(40, 0)")
					.attr("fill", couleurTP)
					.attr("x", function(d,i) { return scaleChartX(i);})
					.attr("y", function(d) {return 300-scaleChartY(d.tps_tp);})
					.attr("width", w/288)
					.attr("height", function(d) {return scaleChartY(d.tps_tp)});
				//Chart voiture
				chart.selectAll(".chart_V")
						.data(data).enter().append("svg:rect")
					.attr("class","chart_V")
					.attr("transform", "translate(40, 0)")
					.attr("fill", couleurVOIT)
					.attr("x", function(d,i) { return scaleChartX(i);})
					.attr("y", function(d) {return 300-scaleChartY(d.tps_tt);})
					.attr("width", w/288)
					.attr("height", function(d) {return scaleChartY(d.tps_tt);});
			
			});
			
			
			var AxisT = d3.svg.axis().scale(t).ticks(24).tickSize(2).orient("bottom"); // Axe pour afficher valeurs de la timeline

			// Axe timeline pour labels
			var timeline = d3.select("#svg_timeline");
				timeline.append("svg:g")
					.attr("transform", "translate(40, 0)")
					.call(AxisT);
					
					
	/////////////////////////////////////////////////////////////////				
			// GRAPHIQUE //
			
			var svg = d3.select("#svg_graphique").append("svg:g")
			  .attr("width", width_graph+2*margin)
			  .attr("height", height_graph+2*margin)
			  .append("svg:g")
			  .attr("transform", "translate("+margin+", "+margin+")");

			// Axes horizontaux
 			svg.append("svg:line")
				 .attr("class", "ligne").attr("x1", 0).attr("y1", height_graph/2).attr("x2", width_graph).attr("y2", height_graph/2)
 				 .attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");
 			svg.append("svg:line")
				 .attr("class", "ligne").attr("x1", 0).attr("y1", height_graph/4).attr("x2", width_graph).attr("y2", height_graph/4)
 				 .attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");
 			svg.append("svg:line")
				 .attr("class", "ligne").attr("x1", 0).attr("y1", height_graph/4+height_graph/2).attr("x2", width_graph).attr("y2", height_graph/4+height_graph/2)
 				 .attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");
 			svg.append("svg:line")
				 .attr("class", "ligne").attr("x1", 0).attr("y1", height_graph).attr("x2", width_graph).attr("y2", height_graph)
 				 .attr("stroke-opacity", "0.4").attr("stroke", "black").style("stroke-width","1").style("stroke-dasharray","6,4");
			
			
			// Graphique TP
			  var lines=svg.selectAll(".line")
					  .data([TP1, TP2, TP3, TP4, TP5, TP6, TP7, TP8, TP9, TP10, TP11, TP12, TP13, TP14, TP15, TP16, TP17, TP18, TP19, TP20, TP21, TP22, TP23, TP24, TP25, TP26, TP27, TP28, TP29, TP30, TP31, TP32, TP33, TP34, TP35, TP36, TP37, TP38, TP39, TP40, TP41, TP42, TP43, TP44, TP45, TP46, TP47, TP48, TP49, TP50, TP51, TP52, TP53, TP54, TP55, TP56, TP57, TP58, TP59, TP60, TP61, TP62, TP63, TP64, TP65, TP66, TP67, TP68, TP69, TP70, TP71, TP72, TP73, TP74, TP75, TP76, TP77, TP78, TP79, TP80, TP81, TP82, TP83, TP84, TP85, TP86, TP87, TP88, TP89, TP90, TP91, TP92, TP93, TP94, TP95, TP96, TP97, TP98, TP99, TP100, TP101, TP102, TP103, TP104, TP105, TP106, TP107, TP108, TP109, TP110, TP111, TP112, TP113, TP114, TP115, TP116, TP117, TP118, TP119, TP120, TP121, TP122, TP123, TP124, TP125, TP126, TP127, TP128, TP129, TP130, TP131, TP132, TP133, TP134, TP135, TP136, TP137, TP138, TP139, TP140, TP141, TP142, TP143, TP144, TP145, TP146, TP147, TP148, TP149, TP150, TP151, TP152, TP153, TP154, TP155, TP156, TP157, TP158, TP159, TP160, TP161, TP162, TP163, TP164, TP165, TP166, TP167, TP168, TP169, TP170, TP171, TP172, TP173, TP174, TP175, TP176])
					.enter().append("svg:path")
					.attr("class", "graph_TP")
					.style("stroke","#60aec8")
					.attr("fill","none")
					.attr("d", d3.svg.line()
					 //.interpolate("monotone")
					//.x(function(d,i) {if (type_scale = "lin"){return xTP(d);} else if (type_scale = "log") {return LOGxTP(d);} })
					.x(function(d,i) {return xTP(d);})
					.y(function(d,i) { return y(i); }));
					
			// Graphique voitures
			  var lines=svg.selectAll(".line")
					  .data([V1, V2, V3, V4, V5, V6, V7, V8, V9, V10, V11, V12, V13, V14, V15, V16, V17, V18, V19, V20, V21, V22, V23, V24, V25, V26, V27, V28, V29, V30, V31, V32, V33, V34, V35, V36, V37, V38, V39, V40, V41, V42, V43, V44, V45, V46, V47, V48, V49, V50, V51, V52, V53, V54, V55, V56, V57, V58, V59, V60, V61, V62, V63, V64, V65, V66, V67, V68, V69, V70, V71, V72, V73, V74, V75, V76, V77, V78, V79, V80, V81, V82, V83, V84, V85, V86, V87, V88, V89, V90, V91, V92, V93, V94, V95, V96, V97, V98, V99, V100, V101, V102, V103, V104, V105, V106, V107, V108, V109, V110, V111, V112, V113, V114, V115, V116, V117, V118, V119, V120, V121, V122, V123, V124, V125, V126, V127, V128, V129, V130, V131, V132, V133, V134, V135, V136, V137, V138, V139, V140, V141, V142, V143, V144, V145, V146, V147, V148, V149, V150, V151, V152, V153, V154, V155, V156, V157, V158, V159, V160, V161, V162, V163, V164, V165, V166, V167, V168, V169, V170, V171, V172, V173, V174, V175, V176])
					.enter().append("svg:path")
					.attr("class", "graph_V")
					.style("stroke","#ef7d29")
					.attr("fill","none")
					.attr("d", d3.svg.line()
					// .interpolate("monotone")
					.x(function(d,i) {return xVoiture(d);})
					.y(function(d,i) { return y(i); }));
			
			// Axe des abscisses TP
			  svg.append("svg:g")
				  .attr("class", "x_axisTP")
				  //.attr("transform", "translate(0," + height_graph + ")")
				  .call(xAxisTP);
			
			// Axe des abscisses voiture
			  svg.append("svg:g")
				  .attr("class", "x_axisV")
				  //.attr("transform", "translate(0," + height_graph + ")")
				  .call(xAxisVoiture);
			
			

			// Axe des ordonnées gauche
			  svg.append("svg:g")
				  .attr("class", "y axis")
				  .attr("transform", "translate("+0+", 0)")
				  .call(yAxisGauche);
			// Axe des ordonnées droit
			  svg.append("svg:g")
				  .attr("class", "y axis")
				  .attr("transform", "translate("+width_graph+", 0)")
				  .call(yAxisDroit);
				  
			// Transparence en fonction de la distance
			d3.json("data/dynamique/time/00_00.json", function(json) {
			
				data=json;
				
				d3.selectAll(".graph_TP").data(data)
					.attr("opacity", 0.2);
				d3.selectAll(".graph_V").data(data)
					.attr("opacity", 0.2);
			});
			
  		}

	draw();

	});
	
	
	</script>
</head>
<body>
	<div id="wrapper">
		<svg id="svg_roue" xmlns="http://www.w3.org/2000/svg" width="1280" height="800"></svg>
		<svg id="svg_histogramme" xmlns="http://www.w3.org/2000/svg" width="1280" height="300"></svg>
		<svg id="svg_timeline" xmlns="http://www.w3.org/2000/svg" width="1280" height="50"></svg>
		<svg id="svg_graphique" xmlns="http://www.w3.org/2000/svg" width="1280" height="1000"></svg>
	</div>
</body>
</html>