<?php
// FICHIER DE Calcul des valeurs de décile, insertion dans la base de données.

include("../connexion.php");
$mysqli->set_charset("utf8");
$decile = 176/10;
$val_decile=1;
$lap=0;
$n=0;

$variables=file("variables_proximite.csv"); 
foreach ($variables as $var) {
	$val_decile=1;
	$lap=0;
	$n=0;
	//print "variable = ".$var."<br />";
	while ($n<=176) {
	
		$start = $n+$lap;
		$lap = round(176/10);
		
		$compteur = 0;
	
		$requete = "SELECT id_lieu, ".trim($var)." FROM lieux_typo ORDER BY ".trim($var)." LIMIT ".$start.", ".$lap;
				
		$result = $mysqli->query($requete);
		if ( ! $result )
			die ("Base innacessible : ".$mysqli->error);
								
		while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
			$id = $tab['id_lieu'];
			
			$compteur++;
			
			$update = "UPDATE lieux_typo SET d_".trim($var)." = ".$val_decile." WHERE id_lieu = ".$id;
			$result_update = $mysqli->query($update);
			if ( ! $result_update )
				die ("UPDATE impossible : ".$mysqli->error);
			//print "variable = ".$var."<br />numéro = $compteur - ID = $id - valeur décile = $val_decile<br /><br />";
		}
		$val_decile++;
		$n = $start;
		
	}

}

// Export du fichier .JSON pour les axes du graphique de la typologie
// for ($id=1;$id<=176;$id++) {
// 	$requete = "SELECT id_lieu, nom, d_km, d_tps_moy_tp, d_tps_moy_tp_6_00, d_tps_moy_tp_7_20, d_tps_moy_v, d_tps_moy_v_6_00, d_tps_moy_v_7_20, d_ectyp_tp, d_ectyp_tp_6_00, d_ectyp_tp_7_20, d_ectyp_v, d_ectyp_v_6_00, d_ectyp_v_7_20, d_delta_tpv, d_delta_tpv_6_00, d_delta_tpv_7_20, d_amplitude_tp, d_amplitude_tp_6_00, d_amplitude_tp_7_20, 
// 	d_amplitude_v, d_amplitude_v_6_00, d_amplitude_v_7_20, 
// 	d_moy_ectypmob_tp_1h, d_moy_ectypmob_tp_1h_6_00, d_moy_ectypmob_tp_1h_7_20, d_moy_ectypmob_tp_30m, d_moy_ectypmob_tp_30m_6_00, d_moy_ectypmob_tp_30m_7_20, d_moy_ectypmob_tp_15m, d_moy_ectypmob_tp_15m_6_00, d_moy_ectypmob_tp_15m_7_20,
// 	d_moy_ectypmob_v_1h, d_moy_ectypmob_v_1h_6_00, d_moy_ectypmob_v_1h_7_20, d_moy_ectypmob_v_30m, d_moy_ectypmob_v_30m_6_00, d_moy_ectypmob_v_30m_7_20, d_moy_ectypmob_v_15m, d_moy_ectypmob_v_15m_6_00, d_moy_ectypmob_v_15m_7_20
// 	FROM lieux_typo WHERE id_lieu = ".$id." ORDER BY tps_moy_tp"; 
// 	
// 	$result = $mysqli->query($requete);
// 	if ( ! $result )
// 		die ("Base innacessible : ".$mysqli->error);
// 	
// 	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
// 		//$csv_tab[] = $tab['id_lieu'].",".$tab['indice_distance'].",".$tab['indice_regularite'];
// 		$json_tab[]= "{\"id_lieu\":\"".$id."\",\"nom\":\"".$tab['nom']."\",\"km\":".$tab['d_km'].",\"tps_moy_tp\":".$tab['d_tps_moy_tp'].",\"tps_moy_tp_6_00\":".$tab['d_tps_moy_tp_6_00'].",\"tps_moy_tp_7_20\":".$tab['d_tps_moy_tp_7_20'].",\"tps_moy_v\":".$tab['d_tps_moy_v'].",\"tps_moy_v_6_00\":".$tab['d_tps_moy_v_6_00'].",\"tps_moy_v_7_20\":".$tab['d_tps_moy_v_7_20']."
// 		,\"ectyp_tp\":".$tab['d_ectyp_tp'].",\"ectyp_tp_6_00\":".$tab['d_ectyp_tp_6_00'].",\"ectyp_tp_7_20\":".$tab['d_ectyp_tp_7_20'].",\"ectyp_v\":".$tab['d_ectyp_v'].",\"ectyp_v_6_00\":".$tab['d_ectyp_v_6_00'].",\"ectyp_v_7_20\":".$tab['d_ectyp_v_7_20']."
// 		,\"amplitude_tp\":".$tab['d_amplitude_tp'].",\"amplitude_tp_6_00\":".$tab['d_amplitude_tp_6_00'].",\"amplitude_tp_7_20\":".$tab['d_amplitude_tp_7_20'].",\"amplitude_v\":".$tab['d_amplitude_v'].",\"amplitude_v_6_00\":".$tab['d_amplitude_v_6_00'].",\"amplitude_v_7_20\":".$tab['d_amplitude_v_7_20'].",\"moy_ectypmob_tp_1h\":".$tab['d_moy_ectypmob_tp_1h'].",\"moy_ectypmob_tp_1h_6_00\":".$tab['d_moy_ectypmob_tp_1h_6_00'].",\"moy_ectypmob_tp_1h_7_20\":".$tab['d_moy_ectypmob_tp_1h_7_20']."
// 		,\"moy_ectypmob_tp_30m\":".$tab['d_moy_ectypmob_tp_30m'].",\"moy_ectypmob_tp_30m_6_00\":".$tab['d_moy_ectypmob_tp_30m_6_00'].",\"moy_ectypmob_tp_30m_7_20\":".$tab['d_moy_ectypmob_tp_30m_7_20'].",\"moy_ectypmob_tp_15m\":".$tab['d_moy_ectypmob_tp_15m'].",\"moy_ectypmob_tp_15m_6_00\":".$tab['d_moy_ectypmob_tp_15m_6_00'].",\"moy_ectypmob_tp_15m_7_20\":".$tab['d_moy_ectypmob_tp_15m_7_20'].",\"moy_ectypmob_v_1h\":".$tab['d_moy_ectypmob_v_1h'].",\"moy_ectypmob_v_1h_6_00\":".$tab['d_moy_ectypmob_v_1h_6_00'].",\"moy_ectypmob_v_1h_7_20\":".$tab['d_moy_ectypmob_v_1h_7_20']."
// 		,\"moy_ectypmob_v_30m\":".$tab['d_moy_ectypmob_v_30m'].",\"moy_ectypmob_v_30m_6_00\":".$tab['d_moy_ectypmob_v_30m_6_00'].",\"moy_ectypmob_v_30m_7_20\":".$tab['d_moy_ectypmob_v_30m_7_20'].",\"moy_ectypmob_v_15m\":".$tab['d_moy_ectypmob_v_15m'].",\"moy_ectypmob_v_15m_6_00\":".$tab['d_moy_ectypmob_v_15m_6_00'].",\"moy_ectypmob_v_15m_7_20\":".$tab['d_moy_ectypmob_v_15m_7_20']."
// 		,\"delta_tpv\":".$tab['d_delta_tpv'].",\"delta_tpv_6_00\":".$tab['d_delta_tpv_6_00'].",\"delta_tpv_7_20\":".$tab['d_delta_tpv_7_20']."}";
// 	}
// 	$json="[\n".implode(",\n", $json_tab)."\n]";
// }

$requete = "SELECT id_lieu, nom, d_km, d_tps_moy_tp, d_tps_moy_tp_6_00, d_tps_moy_tp_7_20, d_tps_moy_v, d_tps_moy_v_6_00, d_tps_moy_v_7_20, d_ectyp_tp, d_ectyp_tp_6_00, d_ectyp_tp_7_20, d_ectyp_v, d_ectyp_v_6_00, d_ectyp_v_7_20, d_delta_tpv, d_delta_tpv_6_00, d_delta_tpv_7_20, d_amplitude_tp, d_amplitude_tp_6_00, d_amplitude_tp_7_20, 
d_amplitude_v, d_amplitude_v_6_00, d_amplitude_v_7_20, 
d_moy_ectypmob_tp_1h, d_moy_ectypmob_tp_1h_6_00, d_moy_ectypmob_tp_1h_7_20, d_moy_ectypmob_tp_30m, d_moy_ectypmob_tp_30m_6_00, d_moy_ectypmob_tp_30m_7_20, d_moy_ectypmob_tp_15m, d_moy_ectypmob_tp_15m_6_00, d_moy_ectypmob_tp_15m_7_20,
d_moy_ectypmob_v_1h, d_moy_ectypmob_v_1h_6_00, d_moy_ectypmob_v_1h_7_20, d_moy_ectypmob_v_30m, d_moy_ectypmob_v_30m_6_00, d_moy_ectypmob_v_30m_7_20, d_moy_ectypmob_v_15m, d_moy_ectypmob_v_15m_6_00, d_moy_ectypmob_v_15m_7_20
FROM lieux_typo ORDER BY tps_moy_tp"; 

$result = $mysqli->query($requete);
if ( ! $result )
	die ("Base innacessible : ".$mysqli->error);

while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
	//$csv_tab[] = $tab['id_lieu'].",".$tab['indice_distance'].",".$tab['indice_regularite'];
	$json_tab[]= "{\"id_lieu\":\"".$tab['id_lieu']."\",\"nom\":\"".$tab['nom']."\",\"km\":".$tab['d_km'].",\"tps_moy_tp\":".$tab['d_tps_moy_tp'].",\"tps_moy_tp_6_00\":".$tab['d_tps_moy_tp_6_00'].",\"tps_moy_tp_7_20\":".$tab['d_tps_moy_tp_7_20'].",\"tps_moy_v\":".$tab['d_tps_moy_v'].",\"tps_moy_v_6_00\":".$tab['d_tps_moy_v_6_00'].",\"tps_moy_v_7_20\":".$tab['d_tps_moy_v_7_20']."
	,\"ectyp_tp\":".$tab['d_ectyp_tp'].",\"ectyp_tp_6_00\":".$tab['d_ectyp_tp_6_00'].",\"ectyp_tp_7_20\":".$tab['d_ectyp_tp_7_20'].",\"ectyp_v\":".$tab['d_ectyp_v'].",\"ectyp_v_6_00\":".$tab['d_ectyp_v_6_00'].",\"ectyp_v_7_20\":".$tab['d_ectyp_v_7_20']."
	,\"amplitude_tp\":".$tab['d_amplitude_tp'].",\"amplitude_tp_6_00\":".$tab['d_amplitude_tp_6_00'].",\"amplitude_tp_7_20\":".$tab['d_amplitude_tp_7_20'].",\"amplitude_v\":".$tab['d_amplitude_v'].",\"amplitude_v_6_00\":".$tab['d_amplitude_v_6_00'].",\"amplitude_v_7_20\":".$tab['d_amplitude_v_7_20'].",\"moy_ectypmob_tp_1h\":".$tab['d_moy_ectypmob_tp_1h'].",\"moy_ectypmob_tp_1h_6_00\":".$tab['d_moy_ectypmob_tp_1h_6_00'].",\"moy_ectypmob_tp_1h_7_20\":".$tab['d_moy_ectypmob_tp_1h_7_20']."
	,\"moy_ectypmob_tp_30m\":".$tab['d_moy_ectypmob_tp_30m'].",\"moy_ectypmob_tp_30m_6_00\":".$tab['d_moy_ectypmob_tp_30m_6_00'].",\"moy_ectypmob_tp_30m_7_20\":".$tab['d_moy_ectypmob_tp_30m_7_20'].",\"moy_ectypmob_tp_15m\":".$tab['d_moy_ectypmob_tp_15m'].",\"moy_ectypmob_tp_15m_6_00\":".$tab['d_moy_ectypmob_tp_15m_6_00'].",\"moy_ectypmob_tp_15m_7_20\":".$tab['d_moy_ectypmob_tp_15m_7_20'].",\"moy_ectypmob_v_1h\":".$tab['d_moy_ectypmob_v_1h'].",\"moy_ectypmob_v_1h_6_00\":".$tab['d_moy_ectypmob_v_1h_6_00'].",\"moy_ectypmob_v_1h_7_20\":".$tab['d_moy_ectypmob_v_1h_7_20']."
	,\"moy_ectypmob_v_30m\":".$tab['d_moy_ectypmob_v_30m'].",\"moy_ectypmob_v_30m_6_00\":".$tab['d_moy_ectypmob_v_30m_6_00'].",\"moy_ectypmob_v_30m_7_20\":".$tab['d_moy_ectypmob_v_30m_7_20'].",\"moy_ectypmob_v_15m\":".$tab['d_moy_ectypmob_v_15m'].",\"moy_ectypmob_v_15m_6_00\":".$tab['d_moy_ectypmob_v_15m_6_00'].",\"moy_ectypmob_v_15m_7_20\":".$tab['d_moy_ectypmob_v_15m_7_20']."
	,\"delta_tpv\":".$tab['d_delta_tpv'].",\"delta_tpv_6_00\":".$tab['d_delta_tpv_6_00'].",\"delta_tpv_7_20\":".$tab['d_delta_tpv_7_20']."}";
}
$json="[\n".implode(",\n", $json_tab)."\n]";


$fichier = "typologie.json";
$handle = fopen($fichier, "w");
fwrite($handle, $json);
fclose($handle);

?>