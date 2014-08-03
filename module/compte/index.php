<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

$id = (empty($_REQUEST['id'])?false:$_REQUEST['id']);
$login = (empty($_REQUEST['login'])?false:$_REQUEST['login']);
$pass = (empty($_REQUEST['pass'])?false:$_REQUEST['pass']);
$idRank = (empty($_REQUEST['idRank'])?false:$_REQUEST['idRank']);

if(isset($_POST["form"])) {
	$result = false;
	if(Form::isValid($_POST["form"])) {
		$requete = new Compte;
		$nameRank="";
		if ($_POST["form"] === 'compte-ajouter') {
			$json = $requete->lister_user(false, $login);
			$lister_user = json_decode($json, true);
			if (empty($lister_user['result'])) {
				$requete->add_user($login, $pass, $idRank);
				$json = $requete->lister_user(false, $login, $pass, $idRank);
				$lister_user = json_decode($json, true);
				if (!empty($lister_user['result'])) {
					$id = $lister_user['result'][0]['u.id'];
					$nameRank = $lister_user['result'][0]['r.name'];
				}
				$result = true;
			}
		} elseif ($_POST["form"] === 'compte-modifier') {
			$requete->update_user($id, $login, $idRank, $pass);
			$json = $requete->lister_user($id);
			$lister_user = json_decode($json, true);
			$nameRank = $lister_user['result'][0]['r.name'];
			$result = true;
		}
	} else {
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result, 'id' => $id, 'login' => $login, 'idRank' => $idRank, 'nameRank' => $nameRank));
	exit();
}

		$page['body']['contenu'] .= '<p><a class="button" href="#" onclick="ajouter_compte();">Ajouter un nouveau compte</a></p>';

		$requete = new Compte;
		$json = $requete->lister_user();
		$lister_user = json_decode($json, true);
		
		$json = $requete->lister_rank();
		$lister_rank = json_decode($json, true);
		$tmp = array();
		foreach($lister_rank['result'] as $valeur) {
			$tmp[$valeur['r.id']] = $valeur['r.name'];
		}
		$lister_rank = $tmp;
		
		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$page['body']['contenu'] .= '<table id="table_compte"><thead><tr><th>Id</th><th>Login</th><th>Rang</th><th>Action</th></tr></thead><tfoot><tr><th>Id</th><th>Login</th><th>Rang</th><th>Action</th></tr></tfoot><tbody>';
			foreach ($lister_user as $membre) {
				$page['body']['contenu'] .= '<tr id="tr_'.$membre['u.id'].'" data-id="'.$membre['u.id'].'" data-login="'.$membre['u.login'].'" data-pass="" data-idRank="'.$membre['r.id'].'"><td id="td_'.$membre['u.id'].'_id">'.$membre['u.id'].'</td><td id="td_'.$membre['u.id'].'_login">'.$membre['u.login'].'</td><td id="td_'.$membre['u.id'].'_idRank">'.$membre['r.name'].'</td><td><a class="popup" href="#" onclick="action(this);" data-id="'.$membre['u.id'].'">Action</a></td></tr>';
			}
			$page['body']['contenu'] .= '</tbody></table>';

			/*** formulaires ***/
	/*** Modifier ***/
$form = new Form('compte-modifier');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_modif",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "compte-modifier"));
$form->addElement(new Element\HTML('<h2>Modifier un compte</h2>'));
$form->setValues(array(
	"mod-id" => $id,
	"mod-login" => $login,
	"mod-pass" => "",
	"mod-idRank" => $idRank
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'mod-id', 'readonly' => 'readonly')));
$form->addElement(new Element\Textbox('Login', 'login', array('id' => 'mod-login')));
$form->addElement(new Element\Password('Mot de passe', 'pass', array('id' => 'mod-pass')));
$form->addElement(new Element\Select('Rang', 'idRank', $tmp, array('id' => 'mod-idRank')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Modifier le compte"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);
			/*******************/

	/*** Ajouter ***/
$form = new Form('compte-ajouter');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_ajout",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "compte-ajouter"));
$form->addElement(new Element\HTML('<h2>Ajouter un compte</h2>'));
$form->setValues(array(
	"add-id" => "",
	"add-login" => "",
	"add-pass" => "",
	"add-idRank" => ""
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'add-id', 'readonly' => 'readonly', 'longDesc' => 'Ce champ sera remplis automatiquement lors de la validation.')));
$form->addElement(new Element\Textbox('Login', 'login', array('id' => 'add-login')));
$form->addElement(new Element\Password('Mot de passe', 'pass', array('id' => 'add-pass')));
$form->addElement(new Element\Select('Rang', 'idRank', $tmp, array('id' => 'add-idRank')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Ajouter le compte"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);
			/*******************/

			$page['header']['js'][] = 'js-compte-compte.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_compte").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_compte").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
