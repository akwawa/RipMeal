<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(21, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

$base = (empty($_REQUEST['base'])?false:$_REQUEST['base']);
$logiciel = (empty($_REQUEST['logiciel'])?false:$_REQUEST['logiciel']);

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		$requete = new Sauvegarde;
		if ($_POST["form"] === 'sauvegarde-creer') {
			if ($base) $requete->save_bdd();
			if ($logiciel) $requete->save_logiciel();
		}
		$result = true;
	} else {
		$result = false;
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result, 'base' => $base, 'logiciel' => $logiciel));
	exit();
}

		$requete = new Sauvegarde;
		$requete->liste_save();
		$liste_save = $requete->liste_save;

		$page['body']['contenu'] .= '<h2>Gérer les sauvegardes</h2><a class="button" href="#" onclick="ajouter_sauvegarde();">Créer une nouvelle sauvegarde</a><table id="table_sauvegarde"><caption>Liste des sauvegardes</caption><thead><tr><th>Nom</th><th>Type</th><th>Taille</th><th>Récupérer</th><th>Action</th></tr></thead><tfoot><tr><th>Nom</th><th>Type</th><th>Taille</th><th>Récupérer</th><th>Action</th></tr></tfoot><tbody>';
		$taille_totale=0;
		$temp='';
		foreach ($liste_save as $fichier) {
			$debut = stripos($fichier, '/')+1;
			$fin = stripos($fichier, '/', $debut)-$debut;
			$taille_fichier = filesize($fichier);
			$taille_totale+=$taille_fichier;
			$nom_fichier=basename($fichier);
			$type_fichier=substr($fichier, $debut, $fin);
			$taille_fichier=human_filesize($taille_fichier);
			
			$temp .= '<tr><td>'.$nom_fichier.'</td><td>'.$type_fichier.'</td><td>'.$taille_fichier.'</td><td><a href="'.$fichier.'">Récupérer</a></td><td><a href="#" onclick="supprimer_sauvegarde(this);" data-fichier="'.urlencode($type_fichier.'_'.$nom_fichier).'">Supprimer</a></td></tr>';
		}
		$total = '<tr><th>Taille totale</th><td>&nbsp;</td><td>'.human_filesize($taille_totale).'</td><td>&nbsp;</td><td><a href="#" onclick="supprimer_sauvegarde(this);" data-fichier="all">Tout supprimer</a></td></tr>';
		$page['body']['contenu'] .= $total.$temp.'</tbody></table>';
		
/*** formulaires ***/
	/*** Nouvelle sauvegarde ***/
$form = new Form('sauvegarde-creer');
$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_save",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "sauvegarde-creer"));
$form->addElement(new Element\HTML('<h2>Créer une sauvegarde</h2>'));
$form->setValues(array(
	"add-base" => 1,
	"add-logiciel" => 1
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Select('Sauvegarder la base de donnée', 'base', array(1 => 'Oui', 0 => 'Non'), array('id' => 'add-base')));
$form->addElement(new Element\Select('Sauvegarder les fichiers du logiciel', 'logiciel', array(1 => 'Oui', 0 => 'Non'), array('id' => 'add-logiciel')));
$form->addElement(new Element\HTML('</fieldset>'));
$form->addElement(new Element\Button("Créer la sauvegarde"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);

		$page['header']['js'][] = 'js-sauvegarde-sauvegarde.js';
		$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_sauvegarde").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/dataTable.french.txt.js"}});$("#table_sauvegarde").dataTable().columnFilter();});';
	}
}
?>