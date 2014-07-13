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
			$page['body']['contenu'] .= '<form action="#" method="POST" onsubmit="return modifier_parametre();">';
			foreach ($lister_param['result'] as $param) {
				$page['body']['contenu'] .= '<p><label for="'.$param['u.text'].'">'.$param['u.description'].'</label><select id="'.$param['u.text'].'" name="'.$param['u.text'].'">';
				$possibility=explode(';', $param['u.possibility']);
				foreach ($possibility as $pos) {
					$page['body']['contenu'] .= '<option value="'.$pos.'"'.((isset($_COOKIE[$param['u.text']]) && $_COOKIE[$param['u.text']]==$pos)?' selected':'').'>'.$pos.'</option>';
				}
				$page['body']['contenu'] .= '</select></p>';
			}
			$page['body']['contenu'] .= '<p><input type="submit" value="Enregistrer les modifications" /></p></form>';
			$page['header']['js'][] = 'js-parametre-parametre.js';
		}
	}
}
?>