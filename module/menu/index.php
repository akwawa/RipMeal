<?php

use PFBC\Form, PFBC\Element, PFBC\Validation;

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");

// if(isset($_POST["form"])) {
// 	if(Form::isValid($_POST["form"])) {
// 		if ($_POST["form"] === 'changer_semaine') {
// 			$requete = new Menu;
// 			$json = $requete->lister_regime();
// 			$lister_regime = json_decode($json, true);

// 			$requete->ajouter_tournee($id, $name, $fullname);
// 			$json = $requete->lister_tournee(false, $name, $fullname);
// 			$lister_tournee = json_decode($json, true);
// 			if (!empty($lister_tournee['result'])) {
// 				$id = $lister_tournee['result'][0]['t.id'];
// 			}
// 		}
// 		$result = true;
// 	} else {
// 		$result = false;
// 		Form::renderAjaxErrorResponse($_POST["form"]);
// 	}
// 	echo json_encode(array('result' => $result, 'id' => $id, 'name' => $name, 'fullname' => $fullname));
// 	exit();
// }

		$page['body']['contenu'] .= '<p>
			<a class="button"  href="#menu-importer" onclick="importer_menu();">Importer une semaine de menu</a>
			<a class="button" href="#menu-semaine" onclick="changer_semaine();">Changer de semaine</a>
		</p>';

		$temps=(empty($_REQUEST['mod-dateCalendrier']))?time():strtotime($_REQUEST['mod-dateCalendrier']);
		$numSemaine = date('W', $temps); // demarre de 1 et non 0 /!\
		$lundiSemaine = week_dates($numSemaine-1, date('Y', $temps))-43200;

		$requete = new Menu;
		$json = $requete->lister_regime();
		$lister_regime = json_decode($json, true);
		
		if (!empty($lister_regime['result'])) {
			$lister_regime = $lister_regime['result'];

			$tab_jour = array('LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI', 'DIMANCHE');
			$tab_type = array('MIDI', 'SOIR');
			$line='<tr><th rowspan="2">Régime</th>';

			for ($i=0; $i<sizeof($tab_jour); $i++) {
				$line .= '<th colspan="2">'.$tab_jour[$i].'</th>';
			}
			$line.='</tr><tr>';
			for ($j=0; $j<sizeof($tab_type)*sizeof($tab_jour); $j++) {
				$line .= '<th>'.$tab_type[$j%2].'</th>';
			}
			$line.='</tr>';
			$page['body']['contenu'] .= '<table id="table_menu"><caption>Menus de la SEMAINE n°'.$numSemaine.' du '.date('d/m/Y', $lundiSemaine).' au '.date('d/m/Y', strtotime("+6 day",$lundiSemaine)).'</caption><thead>'.$line.'</thead><tfoot>'.$line.'</tfoot><tbody>';
			foreach ($lister_regime as $regime) {
				$page['body']['contenu'] .= '<tr><td>'.$regime['r.name'].'</td>';

				for ($i=0; $i<sizeof($tab_jour); $i++) {
					$timestampJour = strtotime('+'.$i.' day', $lundiSemaine);
					for ($j=0; $j<sizeof($tab_type); $j++) {
						$json = $requete->recup_calendrier(false, false, $timestampJour, $tab_type[$j]);
						$recup_calendrier = json_decode($json, true);
						$idCalendrier = empty($recup_calendrier['result'])?0:$recup_calendrier['result'][0]['c.id'];
						$json = $requete->lister_menu_regime(false, $regime['r.id'], $idCalendrier);
						$lister_menu_regime = json_decode($json, true);
						$idMenu = empty($lister_menu_regime['result'])?0:$lister_menu_regime['result'][0]['m.idMenu'];
						$json = $requete->lister_menu($idMenu);
						$lister_menu = json_decode($json, true);
						if (!empty($lister_menu['result'])) {
							$lister_menu = $lister_menu['result'][0];
							$result = $lister_menu['me.name'].'<br/>';
							$result .= $lister_menu['mv.name'].'<br/>';
							$result .= $lister_menu['ml.name'].'<br/>';
							$result .= $lister_menu['mf.name'].'<br/>';
							$result .= $lister_menu['md.name'].'<br/>';
						} else {
							$result = '';
						}
						$page['body']['contenu'] .= '<td>'.$result.'</td>';
					}
				}
				$page['body']['contenu'] .= '</tr>';
			}

			$page['body']['contenu'] .= '</tbody></table>';

		$form = new Form('changer_semaine');
		$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
		$form->configure(array(
			"class" => "desktop-hidden"
		));
		$form->setValues(array(
			"mod-dateCalendrier" => date('Y', $temps).'-W'.date('W', $temps)
		));
		$form->addElement(new Element\Hidden("form", "changer_semaine"));
		$form->addElement(new Element\HTML('<h2>Changer de semaine</h2>'));
		$form->addElement(new Element\Week('Semaine du menu', 'mod-dateCalendrier', array('id' => 'mod-dateCalendrier')));
		$form->addElement(new Element\Button("Changer de semaine"));
		$page['body']['contenu'] .= $form->render(true);
		$page['header']['css']['direct'][] = $form->renderCSS(true);
		$page['header']['js']['direct'][] = $form->renderJS(true);


		$form = new Form("uploadSemaine");
		$form->configure(array("prevent" => array("bootstrap", "jQuery"), "action" => $_SERVER['REQUEST_URI']));
		$form->configure(array(
			"ajax" => 1,
			"ajaxCallback" => "loadSemaine",
			"class" => "desktop-hidden"
		));
		$form->setValues(array(
			"imp-dateCalendrier" => date('Y').'-W'.date('W'),
			"imp-delimiteur" => ";",
			"imp-files" => ""
		));
		$form->addElement(new Element\HTML('<h2>Importer des menus</h2>'));
		$form->addElement(new Element\Hidden("form", "uploadSemaine"));
		$form->addElement(new Element\Week('Semaine du menu', 'imp-dateCalendrier', array('id' => 'imp-dateCalendrier')));
		$form->addElement(new Element\File('Choix du fichier', 'imp-files', array('id' => 'imp-files')));
		$form->addElement(new Element\Textbox("Délimiteur", "imp-delimiteur", array("id" => "imp-delimiteur", 'size' => '1', 'maxlength' => '1')));
		$form->addElement(new Element\Button("Importer les menus"));
		$page['body']['contenu'] .= $form->render(true);
		$page['header']['css']['direct'][] = $form->renderCSS(true);
		$page['header']['js']['direct'][] = $form->renderJS(true);


		$page['body']['contenu'] .= '<div id="resultat"></div>';
		$page['header']['js'][] = 'js-menu-menu.js';

			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_menu").DataTable({"sDom":"RTC<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/dataTable.french.txt.js"}});$("#table_menu").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
