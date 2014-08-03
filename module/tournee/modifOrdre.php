<?php

if (!empty($_SESSION[$application]['idRank'])) {
	$retour = array();
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$idClient = (empty($_REQUEST['idClient'])?false:$_REQUEST['idClient']);
		$numeroTourneeOld = (empty($_REQUEST['numeroTourneeOld'])?false:$_REQUEST['numeroTourneeOld']);
		$numeroTourneeNew = (empty($_REQUEST['numeroTourneeNew'])?false:$_REQUEST['numeroTourneeNew']);
		$idTournee = (empty($_REQUEST['idTournee'])?false:$_REQUEST['idTournee']);

		$requete = new Tournee;
		$json = $requete->lister_clients_tournee(false, $numeroTourneeNew);
		$lister_clients_tournee = json_decode($json, true);
		if (!empty($lister_clients_tournee['result'])) {
			$idClient2 = $lister_clients_tournee['result'][0]['c.id'];
			$requete->update_client_tournee($idClient2, $numeroTourneeOld, $idTournee);
			$requete->update_client_tournee($idClient, $numeroTourneeNew, $idTournee);
			$retour['result'] = true;
		}
	}
	echo json_encode($retour);
}