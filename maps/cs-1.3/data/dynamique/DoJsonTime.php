<?php
// création des JSON par heure pour les cartes (avec tri préalable sur le temps de trajet moyen pour la journée)

include("../../connexion.php");

$mysqli->set_charset("utf8");

$horaire = array();
$arr = file("time.csv");
$delta = 360/176;
$angle_constant = 0;

foreach($arr as $line) {
	$json="";
	$tab="";
	$json_tab = array();
	$horaire = trim($line);
	$h_tab=explode(":", $horaire);
	$hh=trim($h_tab[0]);
	$mm=trim($h_tab[1]);
	
	$fichier = "time/".$hh."_".$mm.".json";	

	$requete="SELECT id_lieu, nom_lisible, tmp_tp, tmp_tt, tmp_min, angle, dist_km, tmp_moyen_tp, tmp_moyen_tt, tmp_moyen_tp_horaire, tmp_moyen_tt_horaire FROM distances WHERE horaire LIKE '$horaire' ORDER BY tmp_moyen_tp";
		
	$result = $mysqli->query($requete);
	if ( ! $result )
		die ("Base des lieux innacessible : ".$mysqli->error);
			
	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
		$nom = $mysqli->real_escape_string($tab['nom_lisible']);
		$json_tab[]= "{\"id_lieu\":".$tab['id_lieu'].",\"nom\":\"".stripslashes($nom)."\", \"tps_tp\":".$tab['tmp_tp'].", \"tps_voit\":".$tab['tmp_tt'].", \"tps_min\":".$tab['tmp_min'].", \"angle\":".$tab['angle'].", \"angle_constant\":".$angle_constant.", \"dist_km\":".$tab['dist_km'].", \"tps_tp_moy\":".$tab['tmp_moyen_tp'].", \"tps_voit_moy\":".$tab['tmp_moyen_tt'].", \"tps_tp_moy_horaire\":".$tab['tmp_moyen_tp_horaire'].", \"tps_voit_moy_horaire\":".$tab['tmp_moyen_tt_horaire']."}";
		$angle_constant += $delta;
	}
	
	$json="[\n".implode(",\n", $json_tab)."\n]";
	
	$handle = fopen($fichier, "w");
	fwrite($handle, $json);
	fclose($handle);
	
	$angle_constant = 0;
}

?>