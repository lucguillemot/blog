<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Content" />
	<meta name="keywords" content="keywords" />
	<title>Commuting Scales</title>
	<link type="text/css" href="js/jquery-ui/css/smooth/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
	<link href='css/styles.css' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/polymaps/polymaps.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="js/d3/d3.min.js"></script>
	<script type="text/javascript" src="js/d3/d3.time.js"></script>
	<script type="text/javascript">

	$(function() {
		/////// VARIABLES ///////// VARIABLES ///////// VARIABLES ///////// VARIABLES ///////// VARIABLES ///////// VARIABLES ///////// VARIABLES ///////// VARIABLES
		// GENERAL
		var width = 1270;
			w = width/2,
			he = 440,
			scale=2,
			heures=0,
			minutes=0,
			heures_txt="",
			minutes_txt="",
			horaire="",
			tps_transition=700,
			si1 = true,
			step=5,
			nb_minutes=0,
			option = "tp",
			couleurTP = "#0022ad",
			couleurVOIT = "#fa327e",
			id_courant = 0,
			last_id = 0,
			nom_courant = "All places",
			tps_courant_tp=0,
			tps_courant_voiture=0,
			tps_courant_mini=0,
			t_park = 5,
			time ="00:00",
			time_file="00_00",
			durees=new Array,
			type_visualisation = "topologie";
		// EUCLIDIEN
		var euclidien_scaleT = d3.scale.linear().domain([0, 90]).range([1, 0]); // échelle pour l'opacité des points de la carte euclidienne, fonction du tps de trajet
		var po = org.polymaps;
		var map = po.map();
		var e_zoom_val=9;
		// TOPOLOGIE
		var topologie_angle = "geoWheel",
			valEchelle = 500,
			topologie_scaleKM = d3.scale.linear().domain([0, 112]).range([1, 0]), // échelle pour calculer l'opacité des points de la roue.
			topologie_scaleX = d3.scale.linear().domain([0, valEchelle]).range([0, w]); // échelle cartographique pour la roue.
		// TYPOLOGIE
		var typologie_width = 500,
			typologie_height = 370,
			typologie_margin = 50,
			periode = "work",
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
		// TIMELINE ET HISTOGRAMME
		var parse = d3.time.format("%H:%M").parse,
			h_start_ = "00:00",
			h_end_ = "23:55",
			h_start = parse(h_start_),
			h_end = parse(h_end_),
			histogram_hauteur=100,
			scaleChartX = d3.scale.linear().domain([1,288]).range([0,width]), // scale pour la largeur du graphique (nombre de laps de temps)
			scaleChartY = d3.scale.linear().domain([0,120]).range([0, histogram_hauteur]), // scale pour la hauteur du graphique (nombre de minutes)
			t = d3.time.scale().domain([h_start, h_end]).range([0, width]); // scale pour calculer les valeurs de la timeline (de minuit à 23h55 sur 1280 pixels)
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
////////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE ///////// INTERFACE
	
		$("#static").hide();
		
		//////////////////////////////////// About
		$("#about").hide();
		
		$("#to_about").click(function(){
			$("#contact").hide();
			$("#static").hide();
			$("#dynamic").hide();
			$("#about").show();
			$("#li_to_about").addClass("active");
			$("#li_to_dynamic").removeClass("active");
			$("#li_to_static").removeClass("active");
			$("#li_to_contact").removeClass("active");
		});
		
		$("#close_about").click(function(){
			$("#about").hide();
		});
		
		//////////////////////////////////// Contact
		$("#contact").hide();
		
		$("#to_contact").click(function(){
			$("#about").hide();
			$("#static").hide();
			$("#dynamic").hide();
			$("#contact").show();
			$("#li_to_contact").addClass("active");
			$("#li_to_dynamic").removeClass("active");
			$("#li_to_static").removeClass("active");
			$("#li_to_about").removeClass("active");
		});
		
		$("#close_contact").click(function(){
			$("#contact").hide();
		});
		
		///////////////////////////////////// Barre de contrôle
		$("#b_stop").hide();
		
		$("#b_play").click(function() {
			si1=play();
			$("#b_play").hide();
			$("#b_stop").show();
		});
		
		$("#b_stop").click(function() {
			clearInterval(si1);
			$("#b_play").show();
			$("#b_stop").hide();
		});
				
		// Initialisation du slider timeline
		$( "#slider_time" ).slider({ 
			animate: 2000,
			range:"min",
			min: 0,
			max: 1435,
			step: 5,
			value:0, // initialisé plus bas avec nb_minutes
			stop: function( event, ui ) {
				nb_minutes=ui.value;
				maj_horaire(false);
				maj_duree();
				redraw();
			}
		});
		
	   	// Slider step. 
      	$( "#slider_step" ).slider({
			animate: 100,
			range:"min",
			min: 5,
			max: 60,
			step: 5,
			value:5,
			slide: function( event, ui ) {
				step=ui.value;
				$("#step_txt").replaceWith("<span id='step_txt' class='controles_texte'>"+ui.value+" minutes</span>");
			}
		});
		
		// Slider temps de parking. 
      	$( "#slider_park" ).slider({ 
			animate: 100,
			range:"min",
			min: 0,
			max: 60,
			step: 1,
			value:5,
			stop: function( event, ui ) {
				t_park=ui.value;
				$("#park_txt").replaceWith("<span id='park_txt' class='modes_texte parking_texte'>"+ui.value+" minutes</span>");
				redraw();
				redraw_histogram();
			}
		});
		
		// Choix du mode de transport
		$("#tp").click(function() {
			//option = $(this).val();
			option = "tp";
			redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#voit").removeClass("active");
			$("#minimum").removeClass("active");
		});
		
		$("#voit").click(function() {
			//option = $(this).val();
			option = "voiture";
			redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#tp").removeClass("active");
			$("#minimum").removeClass("active");
		});
		
		$("#minimum").click(function() {
			//option = $(this).val();
			option = "minimum";
			redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#voit").removeClass("active");
			$("#tp").removeClass("active");
		});
		
		// Bouton SWITCH
		$("#switch_typologie").click(function(){
			type_visualisation = "typologie";
			draw_typologie();
			redraw_typologie();
			$("#vue_topologie").hide();
			$("#vue_typologie").show();
			$(this).addClass("active");
			$("#switch_topologie").removeClass("active");
			clearInterval(si1);
			$("#b_stop").hide();
			$("#b_play").show();
			$("#controles_dynamique").hide();
			$("#slider_time").hide();
		});
		
		$("#switch_topologie").click(function(){
			type_visualisation = "topologie";
			redraw();
			$("#typologie_indicateurs").hide();
			$("#weighting").removeClass("active");
			$("#vue_typologie").hide();
			$("#vue_topologie").show();
			$(this).addClass("active");
			$("#switch_typologie").removeClass("active");
			$("#controles_dynamique").show();
			$("#slider_time").show();
		});
		
		$("#vue_typologie").hide();
		
		$("#to_all_places").click(function(){
			last_id=id_courant;
			id_courant=0;
			redraw();
			redraw_histogram();
			on_select_lieu();
			$(this).addClass("active");
			$("#to_selected_place").removeClass("active");
		});
		
		$("#to_selected_place").click(function(){
			if (id_courant!=0){
				$(this).addClass("active");
				$("#to_all_places").removeClass("active");
			}
		});
				
		$("#legend_histogram").mouseover(function(){
			$(this).css("opacity", 1);
			$(".histogram_line").css("stroke", "#fff");
		});
		$("#legend_histogram").mouseout(function(){
			$(this).css("opacity", 0.5);
			$(".histogram_line").css("stroke", "#666");
		});
		
		/////////////////////////////////////// Interface TOPOLOGIE
		// Choix du type de représentation de la partie topologique
		$("#drawSortedWheel").click(function() {
			//topologie_angle = $(this).val();
			topologie_angle = "sortedWheel";
			redraw();
			$(this).addClass("active");
			$("#drawGeoWheel").removeClass("active");
			$("#drawLine").removeClass("active");
		});
		
		$("#drawGeoWheel").click(function() {
			//topologie_angle = $(this).val();
			topologie_angle = "geoWheel";
			redraw();
			$(this).addClass("active");
			$("#drawSortedWheel").removeClass("active");
			$("#drawLine").removeClass("active");
		});
		
		$("#drawLine").click(function() {
			//topologie_angle = $(this).val();
			topologie_angle = "Line";
			redraw();
			$(this).addClass("active");
			$("#drawSortedWheel").removeClass("active");
			$("#drawGeoWheel").removeClass("active");
		});
		
		$("#zoomPlus").click(function() {
			maj_topologie_scaleX(-100);
		});
		
		$("#zoomMoins").click(function() {
			maj_topologie_scaleX(100);
		});
		
		$("#e_zoomPlus").click(function() {
			e_zoom_val = maj_e_zoom(true);
			map.zoom(e_zoom_val);
			if (type_visualisation=="topologie")
				redraw();
			else 
				redraw_typologie();
		});
		
		$("#e_zoomMoins").click(function() {
			e_zoom_val = maj_e_zoom(false);
			map.zoom(e_zoom_val);
			if (type_visualisation=="topologie")
				redraw();
			else 
				redraw_typologie();
		});
		
		///////////////////////////////////// Interface TYPOLOGIE
		// Choix de la période
		$("#typologie_periode").buttonset();
		
		$("#work").click(function() {
			//periode = $(this).val();
			periode = "work";
			redraw_typologie();
			$(this).addClass("active");
			$("#day").removeClass("active");
			$("#all").removeClass("active");
		});
		
		$("#day").click(function() {
			//periode = $(this).val();
			periode = "day";
			redraw_typologie();
			$(this).addClass("active");
			$("#work").removeClass("active");
			$("#all").removeClass("active");
		});
		
		$("#all").click(function() {
			//periode = $(this).val();
			periode = "all";
			redraw_typologie();
			$(this).addClass("active");
			$("#work").removeClass("active");
			$("#day").removeClass("active");
		});
		
		// Pondération des indicateurs
		$("#weighting").click(function(){
			$(this).addClass("active");
			$("#typologie_indicateurs").show();
		});
		
		$("#typo_i_exit").click(function(){
			$("#typologie_indicateurs").hide();
			$("#weighting").removeClass("active");
		});
		
		$("#typologie_indicateurs").hide();
		
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
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////// CALCULS TYPOLOGIE /////////// CALCULS TYPOLOGIE /////////// CALCULS TYPOLOGIE /////////// CALCULS TYPOLOGIE /////////// CALCULS TYPOLOGIE /////////// CALCULS TYPOLOGIE 

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
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
////////////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ ///////// MAJ 

		function maj_topologie_scaleX(varscale){
			valEchelle = valEchelle+=varscale;
			topologie_scaleX = d3.scale.linear().domain([0, valEchelle]).range([0, w]);
			redraw();
		}
		
		function maj_e_zoom(up) {
			if (up) {e_zoom_val++;}
			else {e_zoom_val--;}
			
			return e_zoom_val;
		}
			
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
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////// EVENT ///////// EVENT 	///////// EVENT 	///////// EVENT 	///////// EVENT 	///////// EVENT 	///////// EVENT 	///////// EVENT 	

		function on_select_lieu() {
			redraw_histogram();
			$("#to_selected_place").addClass("active");
			$("#to_all_places").removeClass("active");
			$("#affichage_lieu").replaceWith("<span id='affichage_lieu' class='infos_texte'>"+nom_courant+"</span>");
			$("#l"+last_id).css("stroke", "#fff").css("stroke-width", 1);
			$("#topologie_"+last_id).css("stroke", "#fff").css("stroke-width", 1);
			$("#typologie_"+last_id).css("stroke", "#000").css("stroke-width", 1);
			$("#l"+id_courant).css("stroke", "#ff0").css("stroke-width", 3);
			$("#topologie_"+id_courant).css("stroke", "#ff0").css("stroke-width", 3);
			$("#typologie_"+id_courant).css("stroke", "#ff0").css("stroke-width", 3);
			
			$("#l"+id_courant).detach().insertAfter(".lieu_eucli:last");
		}
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////// SET  SET  SET  SET  SET  SET  SET  SET  SET  SET  SET  SET  SET  SET  SET ////////////////////////////////////////////////////////////////////////

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
			return durees[nb_minutes/5];
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
		
//// TOPOLOGIE
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////// DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW  DRAW ////////////////////////////////////////////////////////////////////////////////////////////////////////
		
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
			})
			redraw_typologie();	
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
				/* 
1h30
				var t_legend_iso = g_topologie.select("texte_iso").append("svg:text")
					.attr("class", "texte_iso")
					.text("1h30");
				
				t_legend_iso.append("svg:textPath")
					.attr("xlink:href", "iso90");		
 */

					
				var g_topologie_cercles = g_topologie.selectAll(".g_topologie_cercles").data(data).enter()
					.append("svg:g")
					.attr("class", "g_topologie_cercles")
					.attr("transform", function(d){var angle=d.angle;return "rotate("+angle+", 0, 0)"})
					
				var topologie_cercles = g_topologie_cercles.append("svg:circle")
					.attr("class", "topologie_cercles")
					.attr("id", function(d){return "topologie_"+d.id_lieu;})
					.attr("cy", 0)
					.attr("r", 3.8)
					.attr("fill", couleurTP)
					.attr("fill-opacity", function(d){return topologie_scaleKM(d.dist_km);})
					.attr("stroke", "#fff")
					.attr("stroke-opacity", function(d){return topologie_scaleKM(d.dist_km);})
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
					
				g_topologie.append("svg:circle").attr("class", "epfl").attr("cx", 0).attr("cy", 0).attr("r", 3).attr("fill", "black").attr("stroke", "black");
				
				// Lieux sur la carte euclidienne
				var euclidien_lieux = d3.selectAll(".lieu_eucli").data(data)
					.attr("id", function(d){return "euclidien_"+d.id_lieu;})
					.attr("stroke", "#bbb")
					.attr("stroke-opacity", function(d){return set_opacity(d);})
					.attr("stroke-width", 1)
					.attr("fill-opacity", function(d){return set_opacity(d);})
					.attr("fill", function(d){set_fill(d);})
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
			t=setTimeout(draw_map, 1000);
			t=setTimeout(redraw, 2000);				
		}
  		
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
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
/////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW /////// RERAW 
	
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
				
				d3.selectAll(".lieu_eucli").data(data)
					.transition().ease("linear").duration(tps_transition)	
					.attr("r", 5)
					.attr("stroke", "#fff")
					.attr("stroke-opacity", 1)
					.attr("fill-opacity", 1)
					.attr("fill", function(d){return typologie_couleur(d);});				
			})
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
  				
  				var lignes_rayons = d3.select("#topologie").selectAll(".lignes_rayons").data(data)
	  				.transition().ease("cubic-in-out").duration(tps_transition)
  					.attr("x2", function(d){return set_x_topo(d);});  					
				
				var euclidien_lieux = d3.selectAll(".lieu_eucli").data(data)
					.transition().duration(tps_transition)
					.attr("stroke", "#bbb")
					.attr("stroke-opacity", function(d){return set_opacity(d);})
					.attr("stroke-width", 1)
					.attr("fill-opacity", function(d){return set_opacity(d);})
					.attr("fill", function(d){return set_fill(d);});
			
				// MAJ du choix d'ordonnancement de la roue (géo, ordre croissant, ligne)
				d3.selectAll(".g_rayons").data(data)
					.transition().ease("linear").duration(tps_transition)
					.attr("transform", function(d){return topologie_set_angle(d);})
					.attr("visibility", function(d){return topologie_set_visibilite(d);});
				d3.selectAll(".g_topologie_cercles").data(data)
					.transition().ease("linear").duration(tps_transition)
					.attr("transform", function(d){return topologie_set_angle(d);});
				d3.selectAll(".topologie_noms").data(data)
						.transition().ease("linear").duration(tps_transition)
						.attr("visibility", function(d){return topologie_set_visibilite(d);});
			});
			$( "#slider_time" ).slider( "option", "value", nb_minutes);
		}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY /////////// PLAY 

		function play() {
			var si1=setInterval(function() {
				maj_horaire(true);
				maj_duree();
 				redraw();
			}, tps_transition);
			
			return si1;
		}
		
		function draw_map() {
			// Construction de la carte euclidienne	
			map.container(document.getElementById("map"))
				.center({lat: 46.555, lon: 6.78464})
				.zoom(e_zoom_val)
				.zoomRange([9, 12])
				.add(po.image().url(po.url("http://{S}tile.cloudmade.com"
			 + "/8fe17e6f839f46a489cddf269104b684" // clé Cloudmade
			 + "/46045/256/{Z}/{X}/{Y}.png")
			 .hosts(["a.", "b.", "c.", ""])))
				.add(po.geoJson().url("data/dynamique/geojson/geojson3.json").on("load", onload_geojson).id("lieux"))
				//.add(po.interact())
				.add(po.drag())
				.add(po.hash());
			
			function onload_geojson(e){
				for (var i = 0; i < e.features.length; i++) {
					var feature = e.features[i];
					feature.element.setAttribute("id", feature.data.id);
					feature.element.setAttribute("r", "5");
					feature.element.setAttribute("class", "lieu_eucli");
				}
			}
		}		
		
		draw();
		draw_histogram();	
	}); // Fin du script général
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML /////////// HTML 

	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div id="modes_options">
				<!-- Choix du mode de transport -->
				<img src="images/tp.png"/><span class="modes_texte choix_mode active" id="tp" value="tp" >Public Transportation</span>
				<img src="images/voiture.png"/><span class="modes_texte choix_mode" id="voit" value="voiture">Road Transport</span>
				<img src="images/min.png"/><span class="modes_texte choix_mode" id="minimum" value="minimum">Lowest Time</span>
				<span class="modes_texte" id="tps_parking">Parking time &nbsp;&nbsp;&nbsp;</span>
				<span id="slider_park"></span>
				<span class="modes_texte parking_texte" value="5" id="park_txt">&nbsp;&nbsp;&nbsp;5 minutes</span>
			</div>
			<nav>
				<ul>
					<li id="li_to_dynamic" class="active"><a id="to_dynamic" href="#">dynamic</a></li>
					<li id="li_to_static" ><a id="to_static" href="static.php">static</a></li>
					<li id="li_to_about" ><a id="to_about" href="#">About</a></li>
					<li id="li_to_contact" ><a id="to_contact" href="#">contact</a></li>
				</ul>
			</nav>
		</div> 	
		
		<div id="vue_topologie">
			<svg id="topologie" xmlns="http://www.w3.org/2000/svg"></svg>
			<span id="zooms">
				<img src="icones/white/sq_plus_icon&16.png" alt="+" id='zoomPlus' value='zoomPlus'>
				<img src="icones/white/sq_minus_icon&16.png" alt="+" id='zoomMoins' value='zoomMoins'>
			</span>
			<span id="affichages">
				<span id="drawSortedWheel" value="sortedWheel"/>Increasing Order</span>
				<span id="drawGeoWheel" value="geoWheel" class="active">Cardinal Order</span>
				<span id="drawLine" value="drawLine" />Linear</span>
			</span>
		</div>
		
		<div id="vue_typologie">
			<svg id="typologie" xmlns="http://www.w3.org/2000/svg" width="640" height="600"></svg>
			<span id="typologie_choix_periode">
				<span id="work" value="work" class="active"/>Working Time</span>
				<span id="day" value="day">Day Time</span>
				<span id="all" value="all" />All Day</span>
				<span id="weighting" value="weighting" /><b>Weighting</b></span>
			</span>
		</div>
		
		<div id="typologie_indicateurs">
			<img id="typo_i_exit" src="icones/white/round_delete_icon&16.png" alt="exit">
			<div class="typo_i_titre">Distance-related Indicators</div>
			<ul id="typo_i_distance">
				<li class="typo_i_label"><span class="typologie_slider" id="slider_km"></span>&nbsp;&nbsp;&nbsp;kilometers</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_tps_moy_tp"></span>&nbsp;&nbsp;&nbsp;Average Time Using Public Transportation</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_tps_moy_v"></span>&nbsp;&nbsp;&nbsp;Average Time Using Private Car</li>
			</ul>
				
			<div class="typo_i_titre">Regularity-related Indicators</div>  <!-- Fréquence des transports publics + embouteillages -->
			<ul>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_ectyp_tp"></span>&nbsp;&nbsp;&nbsp;Public Transportation's Travel Time Standard Deviation</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_ectyp_v"></span>&nbsp;&nbsp;&nbsp;Car's Travel Time Standard Deviation</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_amplitude_tp"></span>&nbsp;&nbsp;&nbsp;Public Transportation's Travel Time Range</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_amplitude_v"></span>&nbsp;&nbsp;&nbsp;Car's Travel Time Range</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_tp_1h"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (1hour) PT</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_tp_30m"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (30 minutes) PT</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_tp_15m"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (15 minutes) PT</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_v_1h"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (1 hour) - Car</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_v_30m"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (30 minutes) - Car</li>
				<li class="typo_i_label"><span class="typologie_slider" id="slider_moy_ectypmob_v_15m"></span>&nbsp;&nbsp;&nbsp;Moving Standard Deviation Average (15 minutes) - Car</li>
			</ul>
		</div>
		
		<div id="switch">
			<span id="switch_typologie">Typology</span><span id="switch_topologie" class="active">Topology</span>
		</div>
			
		<div id="vue_euclidien">
			<svg id="map" xmlns="http://www.w3.org/2000/svg"></svg>
			<span id="euclidien_zooms">
				<img src="icones/white/sq_plus_icon&16.png" alt="+" id='e_zoomPlus' value='zoomPlus'>
				<img src="icones/white/sq_minus_icon&16.png" alt="+" id='e_zoomMoins' value='zoomMoins'>
			</span>
		</div>

		<div id="barre_select_or_not">
			<div id="select_or_not">
				<span id="to_selected_place">Place Selected</span>
				<span id="to_all_places" class="active">All Places</span>
			</div>
		</div>
		
		<div id="histogram">
			<svg id="svg_histogram" xmlns="http://www.w3.org/2000/svg"></svg>
			<span id="legend_histogram">1 hour</span>
		</div>
	
		<div id="vue_chronologie">
			<div id="slider_time"></div>
			<svg id="slider_axe" xmlns="http://www.w3.org/2000/svg"></svg>
		</div>

		<div id="controles">
			<span id="controles_dynamique">
				<span id="play_stop">
					<img id="b_play" border="0" src="icones/white/playback_play_icon&16.png" alt="Play">
					<img id="b_stop" border="0" src="icones/white/playback_pause_icon&16.png" alt="Stop">
				</span>
				<span id="step" class="controles_texte">Step &nbsp;&nbsp;&nbsp;</span>
				<span id="slider_step"></span>
				<span value="5" id="step_txt" class="controles_texte">&nbsp;&nbsp;&nbsp;5 minutes</span>
			</span>
			<span id="infos">
				<span id="affichage_lieu" class="infos_texte">All places</span>
				<span id="h_txt" class="infos_texte"></span>
				<span id="duree_courante" class="infos_texte">0</span><span class="infos_texte">minutes</span>
			</span>
		</div>
		
		<span id="about" class="infos_box">
			<img id="close_about" src="icones/white/round_delete_icon&16.png" alt="exit">
			<p>Visualization tools can facilitate access to complex spatial phenomenon. This access is what the application to be presented aims at. </p>
<p>As a case study to such a complex phenomenon, the addressed question is that of the commuters' space of the Swiss federal institute of technologies (EPFL). Dealing with traveling times and mobility choices over a week day, the application aims at defining which is the scale of this space: the space that the many users of EPFL cover every day.</p>
<p>To reach this purpose, two main cartographic metrics are used : that of the euclidean space and that of the time space. The multiplication of different ways of visualizing the same set of data intends to show the complexity of this phenomenon that can be summarized in ''commuters' space'' but that is actually very complex, as the maps and dynamic visualizations produced attempt to depict. By changing the content of the cartographic analogy, they aim at giving to the map its inherent heuristic power, which consists in making readable a complex space, and giving a space to look at, in order to understand the role of topologic transportation systems, the role of distance and the motivations of users.</p>
		</span>
		
		<span id="contact" class="infos_box">
			<img id="close_contact" src="icones/white/round_delete_icon&16.png" alt="exit">
			<p><b><a href="http://people.epfl.ch/boris.beaude">Boris Beaude</a></b><br>boris.beaude[at]epfl.ch</p>
			<p><b><a href="http://people.epfl.ch/luc.guillemot">Luc Guillemot</a></b><br>luc.guillemot[at]epfl.ch</p>
			<p><b><a href="http://choros.epfl.ch/">Chôros Lab</a></b>, EPFL, Lausanne</p>
		</span>
		
		
	</div>	
</body>
</html>