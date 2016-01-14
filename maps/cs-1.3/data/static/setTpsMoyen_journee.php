<?php
// Calcul du temps moyen horaire et création du fichier json pour la timeline graphique.

include("../connexion.php");

$mysqli->set_charset("utf8");

// $horaire = array();
// $arr = file("time.csv");
// 
// foreach($arr as $line) {
// 	$tab="";
// 	$horaire = trim($line);
// 	$moyenne_h_tp = array();
// 	$moyenne_h_tt = array();
// 	$moyenne_horaire_tp=0;
// 	$moyenne_horaire_tt=0;
// 	
// 	$requete="SELECT nom_lisible, tmp_tp, tmp_tt FROM distances WHERE horaire LIKE '$horaire'";
// 	
// 	$result = $mysqli->query($requete);
// 	if ( ! $result )
// 		die ("Base innacessible : ".$mysqli->error);
// 	
// 	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
// 		//print $horaire;
// 		$moyenne_h_tp[] = $tab['tmp_tp'];
// 		$moyenne_h_tt[] = $tab['tmp_tt'];
// 	}
// 	
// 	
// 	$moyenne_horaire_tp = array_sum($moyenne_h_tp)/176;
// 	$moyenne_horaire_tt = array_sum($moyenne_h_tt)/176;
// 
// 	//print "HEURE= $horaire - moyenne horaire transports publics = $moyenne_horaire_tp - moyenne horaire voiture = $moyenne_horaire_tt <br />";
// 
// 	
// 	
// 	$query = "UPDATE distances SET tmp_moyen_tp_horaire = '$moyenne_horaire_tp', tmp_moyen_tt_horaire = '$moyenne_horaire_tt' WHERE horaire = '$horaire'";
// 	if ($mysqli->query($query)===false){
// 		die ("Requête UPDATE invalide : ".$mysqli->error);
// 	}
// }


//Tri des id_lieu en fonction du temps moyen de trajet en TP

// 	$requete="SELECT id_lieu FROM distances WHERE horaire LIKE '00:00' ORDER BY tmp_moyen_tp";
// 	$result = $mysqli->query($requete);
// 	if ( ! $result )
//  		die ("Base innacessible : ".$mysqli->error);
// 
// 	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
// 		$data = $tab['id_lieu']."\n";
// 		$handle = fopen("id_lieu_sorted.csv", "a");
// 		fwrite($handle, $data);
// 		fclose($handle);
// 	}

$id_lieu = array();
$arr = file("id_lieu_sorted.csv");
$delta = 360/176;
$angle_constant = 0;

$fichier = "staticTopologie_6_22.json";

foreach($arr as $line) {
	$tab="";
	$id_lieu = trim($line);	
	$tps_mini_tp = 2000;
	$tps_mini_voit = 2000;
	$tps_maxi_tp = 0;
	$tps_maxi_voit = 0;
	
	$tab_moy_tp = array();
	$tab_moy_tt = array();

	//premier id au delà de 05:55 = 12849
	//dernier id avant 22:00 = 46640

	$requete="SELECT nom_lisible, tmp_tp, tmp_tt, tmp_min, tmp_moyen_tp, tmp_moyen_tt, tmp_moyen_tp_horaire, tmp_moyen_tt_horaire, dist_km, angle FROM distances WHERE id_lieu = '$id_lieu' AND id > 12849 AND id < 46640";
	
	$result = $mysqli->query($requete);
	if ( ! $result )
		die ("Base innacessible : ".$mysqli->error);
		
	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
		$nom = $mysqli->real_escape_string($tab['nom_lisible']);
		$tps_tp = $tab['tmp_tp'];
		$tps_voit = $tab['tmp_tt'];
	
		$km = $tab['dist_km'];
		$angle = $tab['angle'];
		
		$tab_moy_tp[] = $tab['tmp_tp'];
		$tab_moy_tt[] = $tab['tmp_tt'];
		
		if ($tps_tp < $tps_mini_tp)
			$tps_mini_tp = $tps_tp;
		if ($tps_tp > $tps_maxi_tp)
			$tps_maxi_tp = $tps_tp;
		if ($tps_voit < $tps_mini_voit)
			$tps_mini_voit = $tps_voit;
		if ($tps_voit > $tps_maxi_voit)
			$tps_maxi_voit = $tps_voit;
	}
	
	// 12*6+12*2 = 96
	//289 - 96 = 
	$moyenne_horaire_tp = array_sum($tab_moy_tp)/193;
	$moyenne_horaire_tt = array_sum($tab_moy_tt)/193;
	
	$json_tab[]= "{\"nom\":\"".stripslashes($nom)."\",\"tps_moyen_tp\":\"".$moyenne_horaire_tp."\",\"tps_moyen_voit\":\"".$moyenne_horaire_tt."\",\"tps_mini_tp\":\"".$tps_mini_tp."\",\"tps_mini_voit\":\"".$tps_mini_voit."\",\"tps_maxi_tp\":\"".$tps_maxi_tp."\",\"tps_maxi_voit\":\"".$tps_maxi_voit."\",\"dist_km\":\"".$km."\",\"angle\":\"".$angle."\",\"angle_constant\":\"".$angle_constant."\"}";
	$angle_constant+=$delta;

}

$json="[\n".implode(",\n", $json_tab)."\n]";
	
print $json;
//print "<br /><br /><br />++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br /><br />";
$handle = fopen($fichier, "w");
fwrite($handle, $json);
fclose($handle);

?>