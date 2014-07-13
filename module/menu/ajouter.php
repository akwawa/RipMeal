<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$requete = new Compte;
		$json = $requete->lister_user();
		$lister_user = json_decode($json, true);
		
		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$page['body']['contenu'] .= '<table><thead><tr><th>Nom</th><th>Rang</th><th colspan="2">Action</th></tr></thead><tbody>';
			foreach ($lister_user as $membre) {
				$page['body']['contenu'] .= '<tr><td>'.$membre['u.login'].'</td><td>'.$membre['u.idRank'].'</td><td><a href="compte-modifier-'.$membre['u.id'].'">Modifier</a></td><td><a href="compte-supprimer-'.$membre['u.id'].'">Supprimer</a></td></tr>';
			}
			$page['body']['contenu'] .= '</tbody></table><p><a href="?menu=compte&amp;sousmenu=ajouterCompte">Ajouter un nouveau compte</a></p>';
		}
	}
}