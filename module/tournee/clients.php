<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$id = (empty($_REQUEST['id'])?false:$_REQUEST['id']);
		$result = array();

		$requete = new Tournee;
		$json = $requete->lister_clients_tournee($id);
		$lister_clients_tournee = json_decode($json, true);
		if (!empty($lister_clients_tournee['result'])) {
			$result = $lister_clients_tournee;
		}

		echo json_encode($result);
	}
}