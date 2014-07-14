<?php
if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$idPublic = empty($_REQUEST['idPublic'])?false:$_REQUEST['idPublic'];
		$niveau = empty($_GET['niveau'])?false:$_GET['niveau'];

		if ($idPublic !== false) {
			if ($idPublic == $_SESSION['id']) {
				$retour['error']='Vous ne pouvez pas supprimer votre compte.';
			} else {
				$requete = new Compte;
				$requete->delete_user($idPublic);
				$retour['result'][0]=true;
			}
		}
	}
	echo json_encode($retour);
}