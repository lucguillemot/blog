$(function() {
	
		
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
				if (type_visualisation=="topologie")
					redraw();
				redraw_histogram();
			}
		});
		
		// Choix du mode de transport
		$("#tp").click(function() {
			//option = $(this).val();
			option = "tp";
			if (type_visualisation=="topologie")
				redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#voit").removeClass("active");
			$("#minimum").removeClass("active");
			$("#gradient_voiture").hide();
			$("#gradient_mini").hide();
			$("#gradient_tp").show();
		});
		
		$("#voit").click(function() {
			//option = $(this).val();
			option = "voiture";
			if (type_visualisation=="topologie")
				redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#tp").removeClass("active");
			$("#minimum").removeClass("active");
			$("#gradient_tp").hide();
			$("#gradient_mini").hide();
			$("#gradient_voiture").show();
		});
		
		$("#minimum").click(function() {
			//option = $(this).val();
			option = "minimum";
			if (type_visualisation=="topologie")
				redraw();
			redraw_histogram();
			$(this).addClass("active");
			$("#voit").removeClass("active");
			$("#tp").removeClass("active");
			$("#gradient_tp").hide();
			$("#gradient_voiture").hide();
			$("#gradient_mini").show();
		});

		
		// Bouton SWITCH
		$("#switch_typologie").click(function(){
			type_visualisation = "typologie";
			draw_lieux_typo();
			draw_typologie();
			$("#vue_topologie").hide();
			$("#vue_typologie").show();
			$("#legend_map").hide();
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
			draw_lieux_topo();
			redraw();
			$("#typologie_indicateurs").hide();
			$("#weighting").removeClass("active");
			$("#vue_typologie").hide();
			$("#vue_topologie").show();
			$("#legend_map").show();
			$(this).addClass("active");
			$("#switch_typologie").removeClass("active");
			$("#controles_dynamique").show();
			$("#slider_time").show();
		});
		
		$("#vue_typologie").hide();
		
		
		$("#legend_histogram").mouseover(function(){
			$(this).css("opacity", 1);
			$(".histogram_line").css("stroke", "#fff");
		});
		$("#legend_histogram").mouseout(function(){
			$(this).css("opacity", 0.5);
			$(".histogram_line").css("stroke", "#666");
		});

});