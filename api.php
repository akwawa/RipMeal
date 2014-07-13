<?php
if (empty($_SESSION)) { session_start(); }

$fic_base='private/base.php';
if (file_exists($fic_base)) include($fic_base); else $erreur[] = 'Impossible de charger le fichier "'.$fic_base.'"';

setlocale(LC_TIME, 'fr_FR.utf8','fra');

$menu=empty($_REQUEST['menu'])?false:$_REQUEST['menu'];
$categorie=empty($_REQUEST['categorie'])?false:$_REQUEST['categorie'];
$idPublic=empty($_REQUEST['idPublic'])?false:$_REQUEST['idPublic'];

if (!empty($_SESSION[$application]['idRank'])) {
	$retour=false;
	$page='module/'.$menu.'/'.$categorie.'.php';
	if (!file_exists($page)) {
		perror(11, 'Impossible de trouver la page "'.$page.'"');
	} else {
		include_once($page);
	}
}
return $retour;

?>