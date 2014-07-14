<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

		$requete = new Regime;
		$json = $requete->lister_regime();
		$lister_regime = json_decode($json, true);
		
		if (!empty($lister_regime['result'])) {
			$lister_regime = $lister_regime['result'];
			$tab_regime = array("-1" => "Aucun");

			$page['body']['contenu'] .= '<table id="table_regime"><caption>Liste des régimes</caption><thead><tr><th>Id</th><th>Nom</th><th>Nom complet</th><th>Remplacement</th><th>Action</th></tr></thead><tfoot><tr><th>Id</th><th>Nom</th><th>Nom complet</th><th>Remplacement</th><th>Action</th></tr></tfoot><tbody>';
			foreach ($lister_regime as $regime) {
				$tab_regime[$regime['r.id']] = $regime['r.name'];
				$page['body']['contenu'] .= '<tr><td>'.$regime['r.id'].'</td><td>'.$regime['r.name'].'</td><td>'.$regime['r.fullname'].'</td><td>'.$regime['r.idRemp'].'</td><td><a class="popup" href="#" onclick="action(this);" data-id="'.$regime['r.id'].'">Action</a></td></tr>';
			}

			$page['body']['contenu'] .= '</tbody></table>';

			$form = new Form();
			$form->configure(array("prevent" => array("bootstrap", "jQuery"),"action" => $menu));
			$form->addElement(new Element\HTML('<legend>Ajouter un régime</legend>'));
			$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'name')));
			$form->addElement(new Element\Textbox('Nom complet', 'fullname', array('id' => 'fullname')));
			$form->addElement(new Element\Select('Remplacement', 'idRemp', $tab_regime, array('id' => 'idRemp')));
			$form->addElement(new Element\Button("Ajouter un régime"));
			$page['body']['contenu'] .= $form->render(true);

			$page['header']['css']['direct'][] = $form->renderCSS(true);
			$page['header']['js']['direct'][] = $form->renderJS(true).'$(document).ready(function(){$("#table_regime").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_regime").dataTable().columnFilter();$(".popup").click(function(){return false;});});';

		}
	}
}
