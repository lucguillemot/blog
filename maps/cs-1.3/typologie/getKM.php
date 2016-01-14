<?php
// Récupération des distances kilométriques dans les fichiers TOMTOM et insertion dans la base de données

include("../connexion.php");
$mysqli->set_charset("utf8");

$path = "../tomtom/archive/";

for ($i=1;$i<=176;$i++) {
	$nom_fichier = $path.$i.".txt";
	$contenu = file_get_contents($nom_fichier);
	print $contenu."<br /><br />";

	$ereg = "#([0-9.]{1,5}).{1,2}km.{1,3}[0-9]{1,2}#si";
	
	preg_match($ereg, $contenu, $match);
	
	if (!$match)  {
		print "L'expression régulière n'a renvoyé aucun résultat pour l'ID ".$i."<br /><br />";
	}
	
	print $match[1]."<br /><br />";
	
	$update = "UPDATE lieux_typo SET km = ".$match[1]." WHERE id_lieu = ".$i;
	$requete_update = $mysqli->query($update);
		if ( ! $requete_update )
			die ("UPDATE impossible : ".$mysqli->error);
	
}


?>