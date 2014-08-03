<?php
if (!empty($_SESSION[$application]['idRank'])) {
	$retour['result']=false;
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$fichier = empty($_REQUEST['fichier'])?false:$_REQUEST['fichier'];

		if ($fichier !== false) {
			$requete = new Sauvegarde;
			if ($fichier==='all') {
				$requete->supprimer_all_save();
				$retour['result']=true;
			} else {
				$save = explode('_', $fichier);
				$rep = $save[0];
				$file = urldecode($save[1].'_'.$save[2]);
				$save_existe = $requete->save_existe($rep, $file);
				if ($save_existe === true) {
					if ($requete->supprimer_save($rep, $file)===true) {
						$retour['result']=true;
					}
				}
			}
		}
	}
	echo json_encode($retour);
}

?>