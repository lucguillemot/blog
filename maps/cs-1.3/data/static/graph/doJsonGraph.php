<?php

include("../../connexion.php");

$horaire = array();
$arr = file("id_lieu.csv");
$json = "";
$fichierTP = "tp.json";
$fichierVoiture = "voiture.json";

foreach($arr as $line) {
	
	$json_tab = array();
	$id_lieu = trim($line);
	
	
	$requete="SELECT id_lieu, tmp_tp, tmp_tt, dist_km, couleur FROM distances WHERE id_lieu = $id_lieu";
		
	$result = $mysqli->query($requete);
	if ( ! $result )
		die ("Base inaccessible : ".$mysqli->error);
		
	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
		$tmp_tp = $tab['tmp_tp'];
		$tmp_tt = $tab['tmp_tt'];
		
		$json_tab[]= $tmp_tp;

	}
	$jsonTP="\nTP".$id_lieu." = [".implode(", ", $json_tab)."],";
	
	$handle = fopen($fichierTP, "a");
	fwrite($handle, $jsonTP);
	fclose($handle);
	
	print "TP".$id_lieu.", ";
}

foreach($arr as $line) {
	
	$json_tab = array();
	$id_lieu = trim($line);
	
	
	$requete="SELECT id_lieu, tmp_tp, tmp_tt, dist_km, couleur FROM distances WHERE id_lieu = $id_lieu";
		
	$result = $mysqli->query($requete);
	if ( ! $result )
		die ("Base inaccessible : ".$mysqli->error);
		
	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
		$tmp_tp = $tab['tmp_tp'];
		$tmp_tt = $tab['tmp_tt'];
		
		$json_tab[]= $tmp_tt;

	}
	$jsonV="\nV".$id_lieu." = [".implode(", ", $json_tab)."],";
	
	$handle = fopen($fichierVoiture, "a");
	fwrite($handle, $jsonV);
	fclose($handle);
	
	print "V".$id_lieu.", ";
}

?>