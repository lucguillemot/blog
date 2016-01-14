// FONCTIONS GENERALES
				
function maj_horaire (up){
	
	if(up) {
		nb_minutes=parseInt(nb_minutes+step);
		if (nb_minutes>1379)
			nb_minutes=0;
	}

	var h_affichage = Math.floor(nb_minutes/60);
	var m_affichage = Math.floor(nb_minutes%60);
	
	if (h_affichage<10)
		h_affichage="0"+h_affichage;
						
	if (m_affichage==0)
		m_affichage="00";
	else {				
		if (m_affichage<10)
			m_affichage="0"+m_affichage;
	}
	
	time = h_affichage+":"+m_affichage;
	time_file=time.replace(":", "_");

	$("#h_txt").replaceWith("<span id='h_txt' class='infos_texte'>"+time+"</span>");

	return true;
}

function maj_duree() {
	$("#duree_courante").replaceWith("<span id='duree_courante' class='infos_texte'>"+get_duree()+"</span>");	
}


function on_select_lieu() {
	redraw_histogram();
	$("#to_selected_place").addClass("active");
	$("#to_all_places").removeClass("active");
	$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+nom_courant+"</span>");
	$("#euclidien_"+last_id).css("stroke", "#fff").css("stroke-width", 1).css("stroke-opacity", ""+last_opacity); // stroke-opacity doit être en texte !!!
	$("#topologie_"+last_id).css("stroke", "#fff").css("stroke-width", 1).css("stroke-opacity", ""+last_opacity);
	$("#typologie_"+last_id).css("stroke", "#000").css("stroke-width", 1).css("stroke-opacity", ""+last_opacity);
	$("#euclidien_"+id_courant).css("stroke", "#ff0").css("stroke-width", 2).css("stroke-opacity", "1");
	$("#topologie_"+id_courant).css("stroke", "#ff0").css("stroke-width", 2).css("stroke-opacity", "1");
	$("#typologie_"+id_courant).css("stroke", "#ff0").css("stroke-width", 2).css("stroke-opacity", "1");
}


function play() {
	var si1=setInterval(function() {
		maj_horaire(true);
		maj_duree();
		redraw();
	}, tps_transition);
	
	return si1;
}