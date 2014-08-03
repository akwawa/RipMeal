<?php
if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$id = empty($_REQUEST['id'])?false:$_REQUEST['id'];

		if ($id !== false) {
			$requete = new Tournee;
			$requete->supprimer_tournee($id);
			$retour['result'][0]=true;
		}
	}
	echo json_encode($retour);
}