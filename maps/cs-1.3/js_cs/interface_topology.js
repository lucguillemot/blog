$(function() {

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

});