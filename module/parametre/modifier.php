<?php

if (!empty($_SESSION[$application]['idRank'])) {
	$retour=false;
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$requete = new Parametre;
		$json = $requete->lister_param();
		$lister_param = json_decode($json, true);
		
		if (!empty($lister_param['result'])) {
			$tab_value=array();
			foreach ($lister_param['result'] as $param) {
				if (!empty($_POST[$param['u.text']])){
					$tab_value[$param['u.id']]=$_POST[$param['u.text']];
				}
			}
			$result = $requete->update_param($_SESSION[$application]['id'], $tab_value);
			if ($result === true) {
				$retour=true;
			}
		}
	}
	echo $retour;
}
