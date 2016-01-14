$(function() {

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
		
			
		$("#t_left").hide();
			
			
		$("#t_right").click(function(){
			$("#t_left").show();
			$("#t_right").hide();
			$("#typologie_indicateurs").css("left", 635);
		});

		$("#t_left").click(function(){
			$("#t_left").hide();
			$("#t_right").show();
			$("#typologie_indicateurs").css("left", 0);
		});
		
		// Paramètre de sélection automatique
		
		$("#tc_car").click(function() {
			tc_mode="car";
			$(this).addClass("active");
			$("#tc_tp").removeClass("active");
			$("#tc_both").removeClass("active");
			$("#tc_d_15").removeClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_all").addClass("active");
			change_sliders_car(10,10,10,10,10,10);
			change_sliders_tp(0,0,0,0,0,0);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_tp").click(function() {
			tc_mode="tp";
			$(this).addClass("active");
			$("#tc_car").removeClass("active");
			$("#tc_both").removeClass("active");
			$("#tc_d_15").removeClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_all").addClass("active");
			change_sliders_tp(10,10,10,10,10,10);
			change_sliders_car(0,0,0,0,0,0);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_both").click(function() {
			tc_mode="both";
			$(this).addClass("active");
			$("#tc_car").removeClass("active");
			$("#tc_tp").removeClass("active");
			$("#tc_d_15").removeClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_all").addClass("active");
			change_sliders_tp(5,5,5,5,5,5);
			change_sliders_car(5,5,5,5,5,5);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_d_15").click(function() {
			$(this).addClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_all").removeClass("active");
			change_sliders_msda(0,0,10,0,0,10);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_d_30").click(function() {
			$(this).addClass("active");
			$("#tc_d_15").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_all").removeClass("active");
			change_sliders_msda(0,10,0,0,10,0);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_d_60").click(function() {
			$(this).addClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_15").removeClass("active");
			$("#tc_all").removeClass("active");
			change_sliders_msda(10,0,0,10,0,0);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
		
		$("#tc_all").click(function() {
			$(this).addClass("active");
			$("#tc_d_30").removeClass("active");
			$("#tc_d_60").removeClass("active");
			$("#tc_d_15").removeClass("active");
			change_sliders_msda(5,5,5,5,5,5);
			typologie_maj_total_dist()
			typologie_maj_total_reg();
			setTimeout(redraw_typologie, 500);
		});
			
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
});