$(function() {

	
	// Légende euclidien GRADIENT
	
	$("#gradient_voiture").hide();
	$("#gradient_mini").hide();
	

	$("#e_zoomPlus").click(function() {
		e_zoom_val++;
		if(e_zoom_val==zoom_max)
			$("#e_zoomPlus").hide();
		$("#e_zoomMoins").show();
		e_zoom();
	});
	
	$("#e_zoomMoins").click(function() {
		e_zoom_val--;
		if(e_zoom_val==zoom_min)
			$("#e_zoomMoins").hide();
		$("#e_zoomPlus").show();
		e_zoom();
	});
	
	
	$("#to_all_places").click(function(){
		last_id=id_courant;
		id_courant=0;
		nom_courant="All Places";
		// redraw();
		redraw_histogram();
		on_select_lieu();
		$(this).addClass("active");
		$("#to_selected_place").removeClass("active");
		$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+nom_courant+"</span>");
	});
		
	$("#to_selected_place").click(function(){
		if (id_courant!=0){
			$(this).addClass("active");
			$("#to_all_places").removeClass("active");
		}
	});				
});