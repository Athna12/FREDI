<?php
session_start();
	$server="localhost";
	$db="fredi";
	$user="root";
	$password="";
	
	try
		{
			//	connexion au serveur de données et à la base
				$bdd = new PDO("mysql:host=$server; dbname=$db;charset=utf8", $user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
	//	gestion d’erreur
	catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
?>
