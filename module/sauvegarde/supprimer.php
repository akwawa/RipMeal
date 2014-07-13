<?php
if (empty($_SESSION[$application]['idRank'])) {
	
} else {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(21, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$idPublic = empty($_GET['idPublic'])?false:$_GET['idPublic'];
		
		if ($idPublic!==false) {
			$requete = new Sauvegarde;
			if ($idPublic==='all') {
				$requete->supprimer_all_save();
			} else {
				$save = explode('_', $idPublic);
				$save_existe = $requete->save_existe($save[0], urldecode($save[1]));
				if ($save_existe === true) {
					if ($requete->supprimer_save($save[0], urldecode($save[1]))===true) {
						$page['body']['contenu'] .= '<div class="info">Sauvegarde supprimée avec succès</div>';
					} else {
						perror(22, 'Impossible de supprimer le fichier de sauvegarde');
					}
				} else {
					perror(22, 'La sauvegarde n\'as pas été trouvée');
				}
			}
		}
		include(dirname(__FILE__).'/index.php');
	}
}
?>