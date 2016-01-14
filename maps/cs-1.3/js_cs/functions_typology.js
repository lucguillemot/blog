// FUNCTIONS TYPOLOGY

function change_sliders_tp(a,b,c,d,e,f) {
	$("#slider_tps_moy_tp").slider("value",a);
	$("#slider_ectyp_tp").slider("value",b);
	$("#slider_amplitude_tp").slider("value",c);
	$("#slider_moy_ectypmob_tp_1h").slider("value",d);
	$("#slider_moy_ectypmob_tp_30m").slider("value",e);
	$("#slider_moy_ectypmob_tp_15m").slider("value",f);
		p_tps_moy_tp=a;
		p_ectyp_tp=b;
		p_amplitude_tp=c;
		p_moy_ectypmob_tp_1h=d;
		p_moy_ectypmob_tp_30m=e;
		p_moy_ectypmob_tp_15m=f;
}

function change_sliders_car(a,b,c,d,e,f) {	
	$("#slider_tps_moy_v").slider("value",a);
	$("#slider_ectyp_v").slider("value",b);
	$("#slider_amplitude_v").slider("value",c);
	$("#slider_moy_ectypmob_v_1h").slider("value",d);
	$("#slider_moy_ectypmob_v_30m").slider("value",e);
	$("#slider_moy_ectypmob_v_15m").slider("value",f);
		p_tps_moy_v=a;
		p_ectyp_v=b;
		p_amplitude_v=c;
		p_moy_ectypmob_v_1h=d;
		p_moy_ectypmob_v_30m=e;
		p_moy_ectypmob_v_15m=f;
}

function change_sliders_msda(a,b,c,d,e,f) {	
	if(tc_mode=="both") {
		$("#slider_moy_ectypmob_tp_1h").slider("value",a);
		$("#slider_moy_ectypmob_tp_30m").slider("value",b);
		$("#slider_moy_ectypmob_tp_15m").slider("value",c);
		$("#slider_moy_ectypmob_v_1h").slider("value",d);
		$("#slider_moy_ectypmob_v_30m").slider("value",e);
		$("#slider_moy_ectypmob_v_15m").slider("value",f);
			p_moy_ectypmob_tp_1h=a;
			p_moy_ectypmob_tp_30m=b;
			p_moy_ectypmob_tp_15m=c;
			p_moy_ectypmob_v_1h=d;
			p_moy_ectypmob_v_30m=e;
			p_moy_ectypmob_v_15m=f;
	}
	if(tc_mode=="tp") {
		$("#slider_moy_ectypmob_tp_1h").slider("value",a);
		$("#slider_moy_ectypmob_tp_30m").slider("value",b);
		$("#slider_moy_ectypmob_tp_15m").slider("value",c);
			p_moy_ectypmob_tp_1h=a;
			p_moy_ectypmob_tp_30m=b;
			p_moy_ectypmob_tp_15m=c;
	}
	if(tc_mode=="car") {
		$("#slider_moy_ectypmob_v_1h").slider("value",d);
		$("#slider_moy_ectypmob_v_30m").slider("value",e);
		$("#slider_moy_ectypmob_v_15m").slider("value",f);
			p_moy_ectypmob_v_1h=d;
			p_moy_ectypmob_v_30m=e;
			p_moy_ectypmob_v_15m=f;
	}
	
}

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
	var val_rouge=100;
	var value="hsl("+val_bleu*360/255+","+val_rouge+"%,"+val_vert*100/255+"%)";
	//alert(value);
	return value;	
}	