<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");
		
		$login=$pass=$idRank='';

	/** Formulaire **/
		$requete = new Parametre;
		$json = $requete->lister_param();
		$lister_param = json_decode($json, true);

		if (!empty($lister_param['result'])) {
			$form = new Form("parametre");
			$form->configure(array(
				"ajax" => 1,
				"ajaxCallback" => "modifier_parametre",
				"action" => $_SERVER['REQUEST_URI']
			));
			$form->addElement(new Element\HTML('<legend>Paramètres</legend>'));
			$form->addElement(new Element\Hidden("form", "parametre"));
			foreach ($lister_param['result'] as $param) {
				$tmp = explode(';', $param['u.possibility']);
				$tabOption=array();
				foreach ($tmp as $a) {$tabOption[$a] = $a;}
				$form->addElement(new Element\Select($param['u.description'], $param['u.text'], $tabOption, array('id' => $param['u.text'], 'value' => (isset($_COOKIE[$param['u.text']])?$_COOKIE[$param['u.text']]:''))));
			}
			$form->addElement(new Element\Button("Modifier les paramètres"));
			$page['body']['contenu'] .= $form->render(true);
			$page['header']['js'][] = 'js-parametre-parametre.js';
		}
	}
}