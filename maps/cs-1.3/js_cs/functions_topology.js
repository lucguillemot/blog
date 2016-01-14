// FUNCTIONS TOPOLOGY

function set_fill(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_voit+t_park;
	if (option == "tp")
		return couleurTP;
	else if (option == "voiture")
		return couleurVOIT;
	else if (voiture-tp>0)
		return couleurTP;
	else {
		return couleurVOIT;}
}

function set_x_topo(d) {
	var tp=d.tps_tp;
	var voiture=d.tps_voit+t_park;
	if (option == "tp"){
		return topologie_scaleX(tp);}
	else if (option == "voiture"){
		return topologie_scaleX(voiture)}
	else {
		if (voiture-tp>0)
			return topologie_scaleX(tp);
		else
			return topologie_scaleX(voiture);
	}
}

function topologie_set_angle(d) {
	if (topologie_angle=="geoWheel") {
		var angle=d.angle;}
	else if (topologie_angle=="sortedWheel"){
		var angle=d.angle_constant;}
	else {
		var angle=0;
	}
	
	 return "rotate("+angle+", 0, 0)";
}

function topologie_set_visibilite(d) {
	if (topologie_angle=="drawLine")
		var visibilite = "hidden";
	else 
		var visibilite = "visible";
		
	return visibilite;
}

function maj_topologie_scaleX(varscale){
	valEchelle = valEchelle+=varscale;
	topologie_scaleX = d3.scale.linear().domain([0, valEchelle]).range([0, w]);
	redraw();
}
