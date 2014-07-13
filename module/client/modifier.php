<?php

if (!empty($_SESSION[$application]['idRank'])) {
	$retour=false;
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$idPublic = empty($_REQUEST['idPublic'])?false:$_REQUEST['idPublic'];
		$niveau = empty($_GET['niveau'])?false:$_GET['niveau'];
		
		if ($idPublic !== false) {
			$requete = new Compte;
			$retour = $requete->lister_user($idPublic);
		}
	}
	echo $retour;
}
