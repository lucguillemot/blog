<?php 
$host="localhost"; 
$compte="luc"; 
$passe="choros"; 
$base="toposcale";
   
$mysqli = new mysqli("$host","$compte","$passe", "$base");
if (mysqli_connect_errno()) { 
	echo "<font color=\"#CC0000\">Connection i Générale : <b>Mauvaise configuration!!! </b></font><br>  
				Vérifiez que votre login et mot de passe sont bien saisi pour la connexion 
				à la base <b>$base</b><br/>".mysqli_connect_error();
	exit();
}
?>