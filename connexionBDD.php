<?php
session_start();
	$host="localhost";
	$dbname="fredi";
	$user="root";
	$password="";
	
	try
		{
			//	connexion au serveur de données et à la base
				$bdd = new PDO("mysql:host=$IPserveur;dbname=$nomBase;charset=utf8", $nomUtil,$mdpUtil,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
	//	gestion d’erreur
	catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
?>
