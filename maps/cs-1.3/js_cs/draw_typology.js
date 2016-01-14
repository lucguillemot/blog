function draw_typologie() {
			
	var svg = d3.select("#typologie").append("svg:g")
		  .attr("width", typologie_width+2*typologie_margin)
		  .attr("height", typologie_height+2*typologie_margin)
		  .attr("transform", "translate("+typologie_margin+", "+typologie_margin+")");
	
	// Axes
	svg.append("svg:line")
		 .attr("class", "typologie_axe").attr("x1", 0).attr("y1", typologie_height).attr("x2", typologie_width).attr("y2", typologie_height)
		 .attr("stroke-opacity", "0.8").attr("stroke", "#fff").style("stroke-width","0.5");
	svg.append("svg:text")
		.attr("x", 5).attr("y", 10)	
		.text("Regularity");
	svg.append("svg:line")
		 .attr("class", "typologie_axe").attr("x1", 0).attr("y1", typologie_height).attr("x2", 0).attr("y2", 0)
		 .attr("stroke-opacity", "0.8").attr("stroke", "#fff").style("stroke-width","0.5");
	svg.append("svg:text")
		.attr("x", typologie_width).attr("y", typologie_height-5)
		.attr("text-anchor", "end")
		.text("Distance");
	
	// Points	 
	d3.json("data/dynamique/typologie.json", function(json) {
		data=json;
		
		var g = svg.selectAll(".typologie_lieux").data(data).enter()
			.append("svg:g")
			.attr("class", "typologie_lieux");
				
		var points = g.append("svg:circle")
			.attr("class", "typologie_cercles")
			.attr("id", function(d){return "typologie_"+d.id_lieu;})
			.attr("z-index", 10)
			.on("click", function(d){
				last_id = id_courant;
				id_courant = d.id_lieu;
				nom_courant = d.nom;
				on_select_lieu();
			})
			.on("mouseover", function(d){
				$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+d.nom+"</span>");
			})
			.on("mouseout", function(d){
				$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+nom_courant+"</span>");
			});	
	});
	redraw_typologie();	
}

function redraw_typologie()  {
	d3.json("data/dynamique/typologie.json", function(json){
		data=json;
		
		d3.selectAll(".typologie_cercles").data(data)
			.transition().ease("linear").duration(tps_transition)	
			.attr("cx", function(d){return typologie_x(typologie_valeur_x(d));})	
			.attr("cy", function(d){return typologie_y(typologie_valeur_y(d));})
			.attr("r", 5)
			.attr("stroke-width",3)
			.attr("fill", function(d){return typologie_couleur(d);});
	});
	redraw_lieux_typo();
}
