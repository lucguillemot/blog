<?php
// création du JSON pour l'initialisation de la timeline (temps moyens de tous les lieux, par période de 5 minutes

include("../../connexion.php");

$mysqli->set_charset("utf8");

$horaire = array();
$json="";
$tab= "";
$json_tab = array();

$fichier = "lieux/tps_moyen_horaire.json";	

$requete="SELECT tmp_moyen_tp_horaire, tmp_moyen_tt_horaire FROM distances WHERE id_lieu = 1";
	
$result = $mysqli->query($requete);
if ( ! $result )
	die ("Base des lieux innacessible : ".$mysqli->error);
		
while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
	$json_tab[]= "{\"tps_tp\":".$tab['tmp_moyen_tp_horaire'].", \"tps_tt\":".$tab['tmp_moyen_tt_horaire']."}";
}

$json="[\n".implode(",\n", $json_tab)."\n]";
$handle = fopen($fichier, "w");
fwrite($handle, $json);
fclose($handle);

?>