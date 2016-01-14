<?php
// création des fichiers json par lieux pour la timeline graphique.

include("connexion.php");

$mysqli->set_charset("utf8");

$geocode = array();
$arr = file("geocode.csv");

foreach($arr as $line) {
	$tab="";
	$json_tab=array();
	$geocode = trim($line);	
	
	$fichier = "cities/".$geocode.".json";
	
	$requete="SELECT country, city, lat, lon, y_1900, src_1900, y_1910, src_1910, y_1920, src_1920, y_1930, src_1930, y_1940, src_1940, y_1950, src_1950, y_1960, src_1960, y_1970, src_1970, y_1980, src_1980, y_1990, src_1990, y_2000, src_2000, y_2005, src_2005, y_2009, src_2009 FROM cities3 WHERE geocode = '$geocode'";

	
	$result = $mysqli->query($requete);
	if ( ! $result )
		die ("Database unreachable: ".$mysqli->error);
		
	while ( $tab = $result->fetch_array(MYSQLI_ASSOC) ) {
		$name = $mysqli->real_escape_string($tab['city']);$country = $tab['country'];		
		$y_1900 = $tab['y_1900'];$src_1900 = $tab['src_1900'];
		$y_1910 = $tab['y_1910'];$src_1910 = $tab['src_1910'];
		$y_1920 = $tab['y_1920'];$src_1920 = $tab['src_1920'];
		$y_1930 = $tab['y_1930'];$src_1930 = $tab['src_1930'];
		$y_1940 = $tab['y_1940'];$src_1940 = $tab['src_1940'];
		$y_1950 = $tab['y_1950'];$src_1950 = $tab['src_1950'];
		$y_1960 = $tab['y_1960'];$src_1960 = $tab['src_1960'];
		$y_1970 = $tab['y_1970'];$src_1970 = $tab['src_1970'];
		$y_1980 = $tab['y_1980'];$src_1980 = $tab['src_1980'];
		$y_1990 = $tab['y_1990'];$src_1990 = $tab['src_1990'];
		$y_2000 = $tab['y_2000'];$src_2000 = $tab['src_2000'];
		$y_2005 = $tab['y_2005'];$src_2005 = $tab['src_2005'];		
		$y_2009 = $tab['y_2009'];$src_2009 = $tab['src_2009'];

		
		$json_tab[]= "{\"geocode\":\"".$geocode."\",\"year\":2009, \"value\":".$y_2009.", \"src\":\"".$src_2009."\"}";
	}
	
	$json="[\n".implode(",\n", $json_tab)."\n]";
	
	//print $json;
	//print "<br /><br /><br />++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br /><br />";
	
	$handle = fopen($fichier, "w");
	fwrite($handle, $json);
	fclose($handle);
}

/*
$json_tab[]= "\"".$geocode."\":\n[
	\t{\"year\":1900, \"value\":".$y_1900.", \"src\":\"".$src_1900."\"},
	\t{\"year\":1920, \"value\":".$y_1910.", \"src\":\"".$src_1910."\"},
	\t{\"year\":1930, \"value\":".$y_1920.", \"src\":\"".$src_1920."\"},
	\t{\"year\":1930, \"value\":".$y_1930.", \"src\":\"".$src_1930."\"},
	\t{\"year\":1940, \"value\":".$y_1940.", \"src\":\"".$src_1940."\"},
	\t{\"year\":1950, \"value\":".$y_1950.", \"src\":\"".$src_1950."\"},
	\t{\"year\":1960, \"value\":".$y_1960.", \"src\":\"".$src_1960."\"},
	\t{\"year\":1970, \"value\":".$y_1970.", \"src\":\"".$src_1970."\"},
	\t{\"year\":1980, \"value\":".$y_1980.", \"src\":\"".$src_1980."\"},
	\t{\"year\":1990, \"value\":".$y_1990.", \"src\":\"".$src_1990."\"},
	\t{\"year\":2000, \"value\":".$y_2000.", \"src\":\"".$src_2000."\"},
	\t{\"year\":2005, \"value\":".$y_2005.", \"src\":\"".$src_2005."\"},
	\t{\"year\":2009, \"value\":".$y_2009.", \"src\":\"".$src_2009."\"}
	\n\t],";
*/
?>