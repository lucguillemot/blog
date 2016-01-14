<?php
	
		include("../../../connexion.php");

		$requete="SELECT id_lieu, nom_lisible, lat, lng, tmp_tp, tmp_tt FROM distances WHERE id < 177 ORDER BY tmp_moyen_tp";
		$result = $mysqli->query($requete);
		if ( ! $result )
			die ("Base innacessible : ".$mysqli->error);
		
		while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {	
			$nom=utf8_encode($tab["nom_lisible"]);
			$id=$tab["id_lieu"];
			$lat=$tab["lat"];
			$lng=$tab["lng"];
			$temps=$tab["tmp_tp"];
			$temps_v=$tab["tmp_tt"];
			$opacity=$temps/90;
					
			$geojson="{\"type\": \"Feature\",\"id\":\"euclidien_".$id."\",\n";
			$geojson.="\"geometry\": {\"type\": \"Point\", \"coordinates\": [".$lng.",".$lat."]},\n";
			//$geojson.=\"\\"properties\\": {\\"id\\":\\"\".$id.\"\\", \\"color\\": \\"#bbb\\", \\"fillColor\\":\\"#00b\\", \\"fillOpacity\\": \\"\".$opacity.\"\\", \\"weight\\":\\"1\\"},\n\";//
			$geojson.="\"properties\": {\"popupContent\": \"".$nom."\"}\n}";

			$geojsontab[]=$geojson;	
				
					
		}
		
		$geojsonfile="{\"type\": \"FeatureCollection\",\n \"features\": [\n".implode(",\n", $geojsontab)."]\n}";

	print $geojsonfile;
	
	$fichier = "geojson2.json";	

	$handle = fopen($fichier, "w");
	fwrite($handle, $geojsonfile);
	fclose($handle);
	
	print "terminé";
?>