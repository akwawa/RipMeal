<?php

if (!empty($_SESSION[$application]['idRank'])) {
	$retour=false;
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$idPublic = empty($_REQUEST['idPublic'])?false:$_REQUEST['idPublic'];
		$niveau = empty($_REQUEST['niveau'])?false:$_REQUEST['niveau'];
		
		if ($idPublic !== false) {
			if ($niveau!==false) {
				$login = empty($_REQUEST['login'])?false:$_REQUEST['login'];
				$idRank = empty($_REQUEST['idRank'])?false:$_REQUEST['idRank'];
				$pass = empty($_REQUEST['pass'])?false:$_REQUEST['pass'];
				$requete = new Compte;
				$requete->update_user($idPublic, $login, $idRank, $pass);
				$retour['result'][0]=true;
				$retour = json_encode($retour);
			} else {
				$requete = new Compte;
				$retour = $requete->lister_user($idPublic);
			}
		}
	}
	echo $retour;
}
