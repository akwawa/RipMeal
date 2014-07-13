<?php

	$application='ripmeal';
	$site='RipMeal';
	$siteDistant='perette.info';
	$request_uri=parse_url($_SERVER["REQUEST_URI"]);
	$racine_url=substr($request_uri['path'], 0, strripos($request_uri['path'], '/')).'/';
	$message_par_defaut='Gestion de portage de repas à domicile';
	$header='RipMeal';
	$menu_defaut='sauvegarde';
	$version=3.00;
	$developpement=true;

	$PARAM_hote='127.0.0.1';
	// $PARAM_hote='mysql51zfs-28.pro';
	$PARAM_type_base = 'mysql';
	$PARAM_port='3306';
	$PARAM_nom_bd='ripmeal';
	$PARAM_utilisateur='perettesite';
	$PARAM_mot_passe='bYo1T3n2';
	$PARAM_jeux_de_caracteres = 'utf8';
	$sel = 'repas_MeatandMeal_$^*:!:;';
	$prefixe = 'v3__';
	$base_privee=true;

?>