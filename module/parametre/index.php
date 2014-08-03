<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

if(isset($_POST["form"])) {
	$result = false;
	if(Form::isValid($_POST["form"])) {
		$requete = new Parametre;
		if ($_POST["form"] === 'parametre-modifier') {
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
				if ($result === true) $retour=true;
			}
		}
	} else {
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result));
	exit();
}

	/** Formulaire **/
		$requete = new Parametre;
		$json = $requete->lister_param();
		$lister_param = json_decode($json, true);

		if (!empty($lister_param['result'])) {
			$form = new Form("parametre-modifier");
			$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
			$form->configure(array(
				"ajax" => 1,
				"ajaxCallback" => "modifier_parametre"
			));
			$form->addElement(new Element\Hidden("form", "parametre-modifier"));
			$form->addElement(new Element\HTML('<h2>Modifier vos paramètres</h2>'));
			foreach ($lister_param['result'] as $param) {
				$tmp = explode(';', $param['u.possibility']);
				$tabOption=array();
				foreach ($tmp as $a) {$tabOption[$a] = $a;}
				$form->addElement(new Element\Select($param['u.description'], $param['u.text'], $tabOption, array('id' => $param['u.text'], 'value' => (isset($_COOKIE[$param['u.text']])?$_COOKIE[$param['u.text']]:''))));
			}
			$form->addElement(new Element\Button("Modifier les paramètres"));
			$page['body']['contenu'] .= $form->render(true);
			$page['header']['css']['direct'][] = $form->renderCSS(true);
			$page['header']['js']['direct'][] = $form->renderJS(true);

			$page['header']['js'][] = 'js-parametre-parametre.js';
		}
	}
}