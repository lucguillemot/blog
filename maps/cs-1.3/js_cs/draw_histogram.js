// Création de l'histogramme de la timeline

function draw_histogram_lines(g, temps) {
	g.append("svg:line")
		.attr("class", "histogram_line")
		.attr("x1", 0)
		.attr("x2", width)
		.attr("y1", histogram_hauteur-scaleChartY(temps))
		.attr("y2", histogram_hauteur-scaleChartY(temps));
}

function draw_histogram(){
	durees=[];
	
	d3.json("data/dynamique/lieux/tps_moyen_horaire.json", function(json) {
		data=json;
		var chart = d3.select("#svg_histogram").append("svg:g");
		chart.selectAll("rect")
			.data(data).enter().append("svg:rect")
			.attr("class","chart")
			.attr("width", w/288)
			.attr("x", function(d,i) { return scaleChartX(i);})
			.attr("fill", function(d) {return set_couleur(d);})
			.attr("y", function(d) {return histogram_hauteur-set_height_chart(d)})
			.attr("height", function(d) {return set_height_chart(d)})
			.attr("duree", function(d) {duree=set_duree(d);durees.push(duree);return duree});
		var t=setTimeout(maj_duree, 1000);
	});
	
	var g_histogram_lines = d3.select("#svg_histogram").append("svg:g");
	draw_histogram_lines(g_histogram_lines, 60);
	d3.select("#svg_histogram").append("svg:text").attr("fill", "white").attr("x",0).attr("y", 47).attr("id", "histogram_hour").text("1 hour");	
}

function redraw_histogram() {
	durees=[];
	var file_name="";
	if (id_courant==0)
		file_name="tps_moyen_horaire";
	else
		file_name=id_courant;
	d3.json("data/dynamique/lieux/"+file_name+".json", function(json) {
		data=json;
		d3.selectAll(".chart").data(data)
			.transition()
			.duration(tps_transition)
			.attr("fill", function(d) {return set_couleur(d);})
			.attr("height", function(d) {return set_height_chart(d)})
			.attr("y", function(d) {return histogram_hauteur-set_height_chart(d)})
			.attr("duree", function(d) {duree=set_duree(d);durees.push(duree);return duree});
		var t=setTimeout(maj_duree, 1000);
	});
	d3.select("#histogram_hour").remove(); // to maintain at the front
	d3.select("#svg_histogram").append("svg:text").attr("fill", "white").attr("x",0).attr("y", 47).attr("id", "histogram_hour").text("1 hour");	
}


//// HISTOGRAMME	
function set_couleur(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_tt+t_park;
	if (option == "voiture") {return couleurVOIT;}
	else if (option == "tp") {return couleurTP;} 
	else {
		if (voiture-tp>0) {
			return couleurTP
		}
		else
			return couleurVOIT;
	}
}

function set_duree(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_tt+t_park;
	if (option == "voiture") {return voiture;}
	else if (option == "tp") {return tp;} 
	else {
		if (voiture-tp>0) {
			return tp
		}
		else
			return voiture;
	}
}

function get_duree() {
	return Math.round(durees[nb_minutes/5]);
}


function set_height_chart(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_tt+t_park;
	if (option == "tp"){return scaleChartY(tp);}
	else if (option == "voiture"){return scaleChartY(voiture)}
	else {
		if (voiture-tp>0)
			return scaleChartY(tp)
		else
			return scaleChartY(voiture);
	}
}