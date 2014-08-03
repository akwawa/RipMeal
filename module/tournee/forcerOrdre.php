<?php

if (!empty($_SESSION[$application]['idRank'])) {
	$retour = array();
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
	
		$idTournee = (empty($_REQUEST['idTournee'])?false:$_REQUEST['idTournee']);
		
		$requete = new Tournee;
		$json = $requete->lister_clients_tournee($idTournee);
		$lister_clients_tournee = json_decode($json, true);
		if (!empty($lister_clients_tournee['result'])) {
			$liste = $lister_clients_tournee['result'];

			$nbPersonnes = count($liste);
			$tab_final = array();
			for ($i=0;$i<$nbPersonnes;$i++) {
				$requete->update_client_tournee($liste[$i]['c.id'], $i, $liste[$i]['c.idTournee']);
			}
			$retour['result'] = true;
		}
	}
	echo json_encode($retour);
}