<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(22, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$requete = new Sauvegarde;
		$retour = $requete->save_totale();

		$page['body']['contenu'] .= '<h2>Créer une sauvegarde</h2>';
		if ($retour === true)
			$page['body']['contenu'] .= '<div class="info">La sauvegarde a bien été créée.</div>';
		else
			perror(22, 'Une erreur est survenu pendant la sauvegarde');
		
		$page['body']['contenu'] .= '<div><a class="button" href="sauvegarde">Retour à la liste des sauvegardes</a></div>';
	}
}
?>