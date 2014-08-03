<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

$id = (empty($_REQUEST['id'])?false:$_REQUEST['id']);
$name = (empty($_REQUEST['name'])?false:$_REQUEST['name']);
$fullname = (empty($_REQUEST['fullname'])?false:$_REQUEST['fullname']);

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		$requete = new Tournee;
		if ($_POST["form"] === 'tournee-ajouter') {
			$requete->ajouter_tournee($id, $name, $fullname);
			$json = $requete->lister_tournee(false, $name, $fullname);
			$lister_tournee = json_decode($json, true);
			if (!empty($lister_tournee['result'])) {
				$id = $lister_tournee['result'][0]['t.id'];
			}
		} elseif ($_POST["form"] === 'tournee-modifier') {
			$json = $requete->modif_tournee($id, $name, $fullname);
		}
		$result = true;
	} else {
		$result = false;
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result, 'id' => $id, 'name' => $name, 'fullname' => $fullname));
	exit();
}

$page['body']['contenu'] .= '<p><a class="button" href="#tournee-ajouter" onclick="ajouter_tournee();">Ajouter une nouvelle tournée</a></p>';

		$requete = new Tournee;
		$json = $requete->lister_tournee();
		$lister_tournee = json_decode($json, true);

		if (!empty($lister_tournee['result'])) {
			$lister_tournee = $lister_tournee['result'];
			$th = '<tr><th>id</th><th>name</th><th>fullname</th><th>Clients</th><th>Action</th></tr>';
			$page['body']['contenu'] .= '<table id="table_tournee"><thead>'.$th.'</thead><tfoot>'.$th.'</tfoot><tbody>';
			foreach ($lister_tournee as $line) {
				$page['body']['contenu'] .= '<tr id="tr_'.$line['t.id'].'"
		data-id="'.$line['t.id'].'"
		data-name="'.$line['t.name'].'"
		data-fullname="'.$line['t.fullname'].'">
	<td id="td_'.$line['t.id'].'_id">'.$line['t.id'].'</td>
	<td id="td_'.$line['t.id'].'_name">'.$line['t.name'].'</td>
	<td id="td_'.$line['t.id'].'_fullname">'.$line['t.fullname'].'</td>
	<td><a class="popup" href="#" onclick="voir_client('.$line['t.id'].');">Voir les clients</a></td>
	<td><a class="popup" href="#" onclick="action(this);" data-id="'.$line['t.id'].'">Action</a></td>
</tr>';
			}
			$page['body']['contenu'] .= '</tbody></table>';

			/*** formulaires ***/
	/*** Modifier ***/
$form = new Form('tournee-modifier');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_modif",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "tournee-modifier"));
$form->addElement(new Element\HTML('<h2>Modifier une tournée</h2>'));
$form->setValues(array(
	"mod-id" => $id,
	"mod-name" => $name,
	"mod-fullname" => $fullname
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'mod-id', 'readonly' => 'readonly')));
$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'mod-name')));
$form->addElement(new Element\Textbox('Nom complet', 'fullname', array('id' => 'mod-fullname')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Modifier la tournée"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);


	/*** Ajouter ***/
$form = new Form('tournee-ajouter');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_ajout",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "tournee-ajouter"));
$form->addElement(new Element\HTML('<h2>Ajouter une tournée</h2>'));
$form->setValues(array(
	"add-id" => $id,
	"add-name" => $name,
	"add-fullname" => $fullname
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'add-id', 'readonly' => 'readonly', 'longDesc' => 'Ce champ sera remplis automatiquement lors de la validation.')));
$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'add-name', 'required' => 'required')));
$form->addElement(new Element\Textbox('Nom complet', 'fullname', array('id' => 'add-fullname', 'required' => 'required')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Ajouter la tournée"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);
			/*******************/
			
			$page['header']['css'][] = 'css-tournee-tournee.css';
			$page['header']['js'][] = 'js-tournee-tournee.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_tournee").DataTable({"sDom":"CRT<\"clear\">lfrtip", "bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_tournee").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
