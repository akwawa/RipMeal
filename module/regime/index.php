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
$idRemp = (empty($_REQUEST['idRemp'])?false:$_REQUEST['idRemp']);
if ($idRemp==="-1"){$idRemp=false;}

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		$requete = new Regime;
		if ($_POST["form"] === 'regime-ajouter') {
			$requete->ajouter_regime(false, $name, $fullname, $idRemp);
			$json = $requete->lister_regime(false, $name, $fullname, $idRemp);
			$lister_regime = json_decode($json, true);
			if (!empty($lister_regime['result'])) {
				$id = $lister_regime['result'][0]['r.id'];
			}
		} elseif ($_POST["form"] === 'regime-modifier') {
			$json = $requete->modif_regime($id, $name, $fullname, $idRemp);
		}
		$result = true;
	} else {
		$result = false;
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result, 'id' => $id, 'name' => $name, 'fullname' => $fullname, 'idRemp' => $idRemp));
	exit();
}

$page['body']['contenu'] .= '<p><a class="button" href="#regime-ajouter" onclick="ajouter_regime();">Ajouter un nouveau régime</a></p>';

		$requete = new Regime;
		$json = $requete->lister_regime();
		$lister_regime = json_decode($json, true);
		
		if (!empty($lister_regime['result'])) {
			$lister_regime = $lister_regime['result'];
			$tab_regime = array("-1" => "Aucun");

			$page['body']['contenu'] .= '<table id="table_regime"><caption>Liste des régimes</caption><thead><tr><th>Id</th><th>Nom</th><th>Nom complet</th><th>Remplacement</th><th>Action</th></tr></thead><tfoot><tr><th>Id</th><th>Nom</th><th>Nom complet</th><th>Remplacement</th><th>Action</th></tr></tfoot><tbody>';
			foreach ($lister_regime as $regime) {
				$tab_regime[$regime['r.id']] = $regime['r.name'];
				$page['body']['contenu'] .= '<tr id="tr_'.$regime['r.id'].'" data-id="'.$regime['r.id'].'" data-name="'.$regime['r.name'].'" data-fullname="'.$regime['r.fullname'].'" data-idRemp="'.$regime['r.idRemp'].'"><td id="td_'.$regime['r.id'].'_id">'.$regime['r.id'].'</td><td id="td_'.$regime['r.id'].'_name">'.$regime['r.name'].'</td><td id="td_'.$regime['r.id'].'_fullname">'.$regime['r.fullname'].'</td><td id="td_'.$regime['r.id'].'_idRemp">'.$regime['r.idRemp'].'</td><td><a class="popup" href="#" onclick="action(this);" data-id="'.$regime['r.id'].'">Action</a></td></tr>';
			}

			$page['body']['contenu'] .= '</tbody></table>';


			/*** formulaires ***/
	/*** Modifier ***/
$form = new Form('regime-modifier');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_modif",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "regime-modifier"));
$form->addElement(new Element\HTML('<h2>Modifier un régime</h2>'));
$form->setValues(array(
	"mod-id" => $id,
	"mod-name" => $name,
	"mod-fullname" => $fullname,
	"mod-idRemp" => $fullname
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'mod-id', 'readonly' => 'readonly')));
$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'mod-name')));
$form->addElement(new Element\Textbox('Nom complet', 'fullname', array('id' => 'mod-fullname')));
$form->addElement(new Element\Select('Remplacement', 'idRemp', $tab_regime, array('id' => 'add-idRemp')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Modifier la tournée"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);


	/*** Ajouter ***/
			$form = new Form('regime-ajouter');
			$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
			$form->configure(array(
				"ajax" => 1,
				"ajaxCallback" => "valide_ajout",
				"class" => "desktop-hidden"
			));
			$form->setValues(array(
				"add-id" => "",
				"add-name" => "",
				"add-fullname" => "",
				"add-idRemp" => ""
			));
			$form->addElement(new Element\Hidden("form", "regime-ajouter"));
			$form->addElement(new Element\HTML('<h2>Ajouter un régime</h2>'));
			$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
			$form->addElement(new Element\Number('ID', 'id', array('id' => 'add-id', 'readonly' => 'readonly', 'longDesc' => 'Ce champ sera remplis automatiquement lors de la validation.')));
			$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'add-name')));
			$form->addElement(new Element\Textbox('Nom complet', 'fullname', array('id' => 'add-fullname')));
			$form->addElement(new Element\Select('Remplacement', 'idRemp', $tab_regime, array('id' => 'add-idRemp')));
			$form->addElement(new Element\HTML('</fieldset>'));
			$form->addElement(new Element\Button("Ajouter un régime"));
			$page['body']['contenu'] .= $form->render(true);
			$page['header']['css']['direct'][] = $form->renderCSS(true);
			$page['header']['js']['direct'][] = $form->renderJS(true);
			
			$page['header']['js'][] = 'js-regime-regime.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_regime").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_regime").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
