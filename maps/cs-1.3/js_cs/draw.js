function draw() {

	// Affichage de l'heure
	$("#h_txt").replaceWith("<span id='h_txt' class='infos_texte'>00:00</span>");
				
	// Afficher l'axe de la timeline
	var AxisT = d3.svg.axis().scale(t).ticks(24).tickSize(-5).orient("bottom"); // Axe pour afficher valeurs de la timeline

	// Axe timeline pour labels
	var timeline = d3.select("#slider_axe").append("svg:g")
		.attr("width", 1270)
		.call(AxisT);

	// Affichage de la roue
	d3.json("data/dynamique/time/"+time_file+".json", function(json) {
		data=json;
		
		var g_topologie = d3.select("#topologie").append("svg:g").attr("id", "topo_c").attr("transform", function() {var transX=w/2;var transY=he/2; return "translate("+transX+", "+transY+")"});
		
		draw_topologie_concentrique(g_topologie, 90);
		draw_topologie_concentrique(g_topologie, 60);
		draw_topologie_concentrique(g_topologie, 30);	
		
		g_topologie.append("svg:text")
			.attr("id", "legend_1h30")
			.attr("x", 0)
			.attr("y", function(){return topologie_scaleX(88);})
			.style("fill", "#fff")
			.attr("text-anchor","middle")
			.text("1h30")
			.attr("transform", "rotate(325,0,0)");
	
			
		var g_topologie_cercles = g_topologie.selectAll(".g_topologie_cercles").data(data).enter()
			.append("svg:g")
			.attr("class", "g_topologie_cercles")
			.attr("transform", function(d){var angle=d.angle;return "rotate("+angle+", 0, 0)"})
			
		var topologie_cercles = g_topologie_cercles.append("svg:circle")
			.attr("class", "topologie_cercles")
			.attr("cy", 0)
			.attr("r", 3.8)
			.attr("fill", couleurTP)
			.attr("fill-opacity", function(d){return topologie_scaleKM(d.dist_km);})
			.attr("stroke", "#fff")
			.attr("stroke-opacity", function(d){return topologie_scaleKM(d.dist_km);})
			.attr("id", function(d){return "topologie_"+d.id_lieu;})
			.on("click", function(d){
				last_id = id_courant;
				last_opacity=opacity;
				id_courant = d.id_lieu;
				opacity=set_opacity(d);
				nom_courant = d.nom;
				on_select_lieu();
			})
			.on("mouseover", function(d){
				$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+d.nom+"</span>");
			})
			.on("mouseout", function(d){
				$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+nom_courant+"</span>");
			});
			
		g_topologie.append("svg:circle").attr("class", "epfl").attr("cx", 0).attr("cy", 0).attr("r", 3).attr("fill", "black").attr("stroke", "black");
		
	});
	t=setTimeout(redraw, 1000);
	t=setTimeout(draw_lieux_topo, 1000);							
}

function draw_topologie_concentrique(g, temps) {
	g.append("svg:circle")
		.attr("id", "iso"+temps)
		.attr("cx", 0)
		.attr("cy", 0)
		.attr("r", function(){return topologie_scaleX(temps);})
		.attr("fill", "#ddd")
		.attr("fill-opacity", "0.3")
}
	
		
function redraw() {

	var cercles_iso90 = d3.select("#iso90")
		.transition().ease("linear").duration(tps_transition)
		.attr("r", function(){return topologie_scaleX(90);});
		
	var cercles_iso60 = d3.selectAll("#iso60")
		.transition().ease("linear").duration(tps_transition)
		.attr("r", function(){return topologie_scaleX(60);});
		
	var cercles_iso30 = d3.selectAll("#iso30")
		.transition().ease("linear").duration(tps_transition)
		.attr("r", function(){return topologie_scaleX(30);});
	
	var legend_1h30 = d3.selectAll("#legend_1h30")
		.transition().ease("linear").duration(tps_transition)
		.attr("y", function(){return topologie_scaleX(88);});
	
	// MAJ des cercles
	d3.json("data/dynamique/time/"+time_file+".json", function(json) {
		data=json;
							
		var topologie_cercles = d3.select("#topologie").selectAll(".topologie_cercles").data(data)
			.transition().ease("cubic-in-out").duration(tps_transition)
			.attr("cx", function(d){return set_x_topo(d)})
			.attr("fill", function(d){return set_fill(d);});
		
		d3.selectAll(".g_topologie_cercles").data(data)
			.transition().ease("linear").duration(tps_transition)
			.attr("transform", function(d){return topologie_set_angle(d);});
		
	});
	
	$( "#slider_time" ).slider( "option", "value", nb_minutes);
	
	redraw_lieux_topo();
}