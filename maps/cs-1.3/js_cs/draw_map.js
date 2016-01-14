function draw_map() {
	// Construction de la carte euclidienne	
	L.mapbox.accessToken = 'pk.eyJ1IjoibHVjZ3VpbGxlbW90IiwiYSI6Ijg5bEF4amcifQ.E0WluY_iZmMpZOyP7a0r6w';
	var map = L.mapbox.map('map', 'lucguillemot.7412dd15', { zoomControl:false })
    	.setView([46.555, 6.78464],9);
      	
	var svg = d3.select(map.getPanes().overlayPane).append("svg"),
	    g = svg.append("g").attr("class", "leaflet-zoom-hide");
	
	d3.json("data/dynamique/geojson/geojson.json", function(collection) {
	  	var transform = d3.geo.transform({point: projectPoint}),
	    	path = d3.geo.path().projection(transform);
		 
		var feature = g.selectAll("path")
		      .data(collection.features)
		    .enter().append("path");
		
		map.on("viewreset", reset);
		  reset();
		
		function reset() {
		    var bounds = path.bounds(collection),
		        topLeft = bounds[0],
		        bottomRight = bounds[1];
		
		   
		svg.attr("width", bottomRight[0] - topLeft[0])
		       .attr("height", bottomRight[1] - topLeft[1])
		       .style("left", topLeft[0] + "px")
		       .style("top", topLeft[1] + "px");
		
		g.attr("transform", "translate(" + -topLeft[0] + "," + -topLeft[1] + ")");
  
 		feature.attr("d", path).attr("class", "lieu_eucli");

	  }
	  
	  function projectPoint(x, y) {
	    var point = map.latLngToLayerPoint(new L.LatLng(y, x));
	    this.stream.point(point.x, point.y);
	  }
	  
	});
	
    /*
map.container(document.getElementById("map").appendChild(po.svg("svg")))
		.center({lat: 46.555, lon: 6.78464})
		.zoom(e_zoom_val)
		.zoomRange([zoom_min, zoom_max])
		.add(po.drag())
		.add(po.hash());
		
		.hosts(["a.", "b.", "c.", ""])));
*/
}

function draw_lieux_topo() {
	// Affichage de la roue
	d3.json("data/dynamique/time/"+time_file+".json", function(json) {
		data=json;
		
		// Lieux sur la carte euclidienne
		var euclidien_lieux = d3.selectAll(".lieu_eucli").data(data)
			.attr("stroke", "#bbb")
			.attr("stroke-opacity", function(d){return set_opacity(d);})
			.attr("stroke-width", 1)
			.attr("fill-opacity", function(d){return set_opacity(d);})
			.attr("fill", function(d){set_fill(d);})
			.attr("id", function(d){return "euclidien_"+d.id_lieu;})
			.on("click", function(d){ // Ajouter à cause du zoom de polymaps
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
	});
	t=setTimeout(redraw_lieux_topo, 1000);				
}


function redraw_lieux_topo() {

	// MAJ des cercles
	d3.json("data/dynamique/time/"+time_file+".json", function(json) {
		data=json;
							
		var euclidien_lieux = d3.selectAll(".lieu_eucli").data(data)
			.transition().duration(tps_transition)
			.attr("stroke", "#bbb")
			.attr("stroke-opacity", function(d){return set_opacity(d);})
			.attr("stroke-width", 1)
			.attr("fill-opacity", function(d){return set_opacity(d);})
			.attr("fill", function(d){return set_fill(d);});
	});	
	
	on_select_lieu();
}


function draw_lieux_typo() {


	// Affichage de la roue
	d3.json("data/dynamique/typologie.json", function(json) {
		data=json;
		
		// Lieux sur la carte euclidienne
		var euclidien_lieux = d3.selectAll(".lieu_eucli").data(data)
			.attr("stroke", "#bbb")
			.attr("stroke-opacity", function(d){return set_opacity(d);})
			.attr("stroke-width", 1)
			.attr("fill-opacity", function(d){return set_opacity(d);})
			.attr("fill", function(d){set_fill(d);})
			.on("click", function(d){ // Ajouter à cause du zoom de polymaps
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
	});
}

function redraw_lieux_typo() {
	// Affichage de la roue
	d3.json("data/dynamique/typologie.json", function(json) {
		data=json;
			
		d3.selectAll(".lieu_eucli").data(data)
			.transition().ease("linear").duration(tps_transition)	
			.attr("r", 5)
			.attr("stroke", "#fff")
			.attr("stroke-opacity", 1)
			.attr("fill-opacity", 1)
			.attr("fill", function(d){return typologie_couleur(d);});		
	});
	on_select_lieu();
}


//// EUCLIDIEN
function set_opacity(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_voit+t_park;
	if (option == "tp"){
		return euclidien_scaleT(tp);}
	else if (option == "voiture"){
		return euclidien_scaleT(voiture)}
	else {
		if (voiture-tp>0)
			return euclidien_scaleT(tp);
		else
			return euclidien_scaleT(voiture);
	}
}

/*
function e_zoom() {
	map.zoom(e_zoom_val);
	if (type_visualisation=="topologie") {
		setTimeout(draw_lieux_topo,500);
	}
	else {
		setTimeout(draw_lieux_typo,500);
		setTimeout(redraw_typologie, 500);
	}
}
*/
