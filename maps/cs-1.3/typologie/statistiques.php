<?php
// FICHIER DE Création des variables pour la typologie et calculs statistiques

include("../connexion.php");
$mysqli->set_charset("utf8");

$arr = file("../data/dynamique/id_lieu.csv");

//premier id au delà de 05:55 = 12849
//dernier id avant 20:00 = 42416

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ecart_type($tableau) {
	$moyenne = array_sum($tableau) / count($tableau);
	$p_variance = 0;
	foreach ($tableau as $i) {
        $p_variance += pow($i - $moyenne, 2);
    }
    $variance = $p_variance / count($tableau);
    
    return sqrt($variance);
}

function moyenne_ecart_types_mobile($tableau, $t_inf, $t_sup){
	$nb_val = count($tableau);
	$tab_ectypmob = array();
	for ($i=0;$i<=$nb_val;$i++) {
		$sample = array();
		if ($i>=$t_inf AND $i<=$nb_val-$t_sup) {
			for ($j=$i-$t_inf;$j<$i+$t_sup;$j++) {
				$sample[] = $tableau[$j];		
			}
			$tab_ectypmob[] = ecart_type($sample);
		}
	}
	return array_sum($tab_ectypmob) / count($tab_ectypmob);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function calcul($array, $journee)  {
	global $mysqli;
	
	foreach($array as $line) { //pour chaque id_lieu
		
		$id_lieu = trim($line);	
	
		$tab_tp = array();
		$tab_v = array();
		$tab_tpv = array();
			
		if ($journee == "tout")
			$requete = "SELECT tmp_tp, tmp_tt FROM distances WHERE id_lieu = '$id_lieu'";
		elseif ($journee == "activite")
			$requete = "SELECT tmp_tp, tmp_tt FROM distances WHERE id_lieu = '$id_lieu' AND id > 12848";
		elseif ($journee == "travail")
			$requete = "SELECT tmp_tp, tmp_tt FROM distances WHERE id_lieu = '$id_lieu' AND id > 12848 AND id < 42417";
	
		$result = $mysqli->query($requete);
		if ( ! $result )
			die ("Base innacessible : ".$mysqli->error);
			
		while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) { //chaque heure pour le lieu
			// Tableaux
			$tab_tp[] = $tab['tmp_tp'];
			$tab_v[] = $tab['tmp_tt'];
		}
		
		// Nombre d'individus
		$nb_val = count($tab_tp);
		
		// Minimum et maximum
		$tps_mini_tp = min($tab_tp);
		$tps_maxi_tp = max($tab_tp);
		$tps_mini_v = min($tab_v);
		$tps_maxi_v = max($tab_v);
		
		// Calcul des amplitudes
		$amplitude_tp = $tps_maxi_tp - $tps_mini_tp;
		$amplitude_v = $tps_maxi_v - $tps_mini_v;
		
		// Calcul des moyennes
		$tps_moy_tp = array_sum($tab_tp)/$nb_val;
		$tps_moy_v = array_sum($tab_v)/$nb_val;
		
		// Calcul des écarts_types
		//// TP
		$ec_typ_tp = ecart_type($tab_tp);
		//// Voiture
		$ec_typ_v = ecart_type($tab_v);
		
		// Calcul de la variation	
		$delta_tpv_moy = $tps_moy_tp - $tps_moy_v;
		
		// Calcul de la moyenne des écart-types mobiles	
		///Transports publics
		//// une heure
			$moy_ectypmob_tp_1h = moyenne_ecart_types_mobile($tab_tp,6,6);
		//// 30 minutes
			$moy_ectypmob_tp_30m = moyenne_ecart_types_mobile($tab_tp,3,3);
		//// 15 minutes
			$moy_ectypmob_tp_15m = moyenne_ecart_types_mobile($tab_tp,2,2);	
		
		///Voiture
		//// une heure
			$moy_ectypmob_v_1h = moyenne_ecart_types_mobile($tab_v,6,6);
		//// 30 minutes
			$moy_ectypmob_v_30m = moyenne_ecart_types_mobile($tab_v,3,3);
		//// 15 minutes
			$moy_ectypmob_v_15m = moyenne_ecart_types_mobile($tab_v,2,2);	
		
		
		// Insertion dans la base de données
		if ($journee == "tout")
			$update = "UPDATE lieux_typo SET tps_moy_tp='$tps_moy_tp', tps_moy_v='$tps_moy_v', ectyp_tp='$ec_typ_tp', ectyp_v='$ec_typ_v', delta_tpv='$delta_tpv_moy', amplitude_tp='$amplitude_tp', amplitude_v='$amplitude_v', moy_ectypmob_tp_1h='$moy_ectypmob_tp_1h', moy_ectypmob_tp_30m='$moy_ectypmob_tp_30m', moy_ectypmob_tp_15m='$moy_ectypmob_tp_15m', moy_ectypmob_v_1h='$moy_ectypmob_v_1h', moy_ectypmob_v_30m='$moy_ectypmob_v_30m', moy_ectypmob_v_15m='$moy_ectypmob_v_15m' WHERE id_lieu='$id_lieu'";
		elseif ($journee == "activite")
			$update = "UPDATE lieux_typo SET tps_moy_tp_6_00='$tps_moy_tp', tps_moy_v_6_00='$tps_moy_v', ectyp_tp_6_00='$ec_typ_tp', ectyp_v_6_00='$ec_typ_v', delta_tpv_6_00='$delta_tpv_moy', amplitude_tp_6_00='$amplitude_tp', amplitude_v_6_00='$amplitude_v', moy_ectypmob_tp_1h_6_00='$moy_ectypmob_tp_1h', moy_ectypmob_tp_30m_6_00='$moy_ectypmob_tp_30m', moy_ectypmob_tp_15m_6_00='$moy_ectypmob_tp_15m', moy_ectypmob_v_1h_6_00='$moy_ectypmob_v_1h', moy_ectypmob_v_30m_6_00='$moy_ectypmob_v_30m', moy_ectypmob_v_15m_6_00='$moy_ectypmob_v_15m' WHERE id_lieu='$id_lieu'";
		elseif ($journee == "travail")
			$update = "UPDATE lieux_typo SET tps_moy_tp_7_20='$tps_moy_tp', tps_moy_v_7_20='$tps_moy_v', ectyp_tp_7_20='$ec_typ_tp', ectyp_v_7_20='$ec_typ_v', delta_tpv_7_20='$delta_tpv_moy', amplitude_tp_7_20='$amplitude_tp', amplitude_v_7_20='$amplitude_v', moy_ectypmob_tp_1h_7_20='$moy_ectypmob_tp_1h', moy_ectypmob_tp_30m_7_20='$moy_ectypmob_tp_30m', moy_ectypmob_tp_15m_7_20='$moy_ectypmob_tp_15m', moy_ectypmob_v_1h_7_20='$moy_ectypmob_v_1h', moy_ectypmob_v_30m_7_20='$moy_ectypmob_v_30m', moy_ectypmob_v_15m_7_20='$moy_ectypmob_v_15m' WHERE id_lieu='$id_lieu'";
		
		$requete_update = $mysqli->query($update);
		if ( ! $requete_update )
			die ("UPDATE impossible : ".$mysqli->error);
		
		// print $id_lieu." --> tps moyen TP = ".$tps_moy_tp."; tps moyen voiture = ".$tps_moy_v."; tps moyen tpv = ".$tps_moy_tpv."; tps mini TP = ".$tps_mini_tp."; tps maxi TP = ".$tps_maxi_tp."
// 		; tps mini voiture = ".$tps_mini_v."; tps maxi voiture = ".$tps_maxi_v."; amplitude TP = ".$amplitude_tp." ; amplitude voiture = ".$amplitude_v." ; écart-type = ".$ec_typ." ; variation = ".$delta_tpv_moy." ; moyenne des écart-types mobiles = ".$moy_ectypmob_1h."<br/><br/>";
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Calculs et Updates
	//// Pour l'ensemble de la journée
	calcul($arr, "tout");
	//// De 6:00 à Minuit
	calcul($arr, "activite");
	//// De 7:00 à 20:00
	calcul($arr, "travail");


?>