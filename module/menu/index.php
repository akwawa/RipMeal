<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$temps=(empty($_POST['dateCalendrier']))?time():strtotime($_POST['dateCalendrier']);
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

			$page['body']['contenu'] .= '<form action="'.$menu.'" method="POST"><p><label for="dateCalendrier">Semaine</label><input type="week" name="dateCalendrier" id="dateCalendrier" value="" /><input type="submit" value="Changer de semaine" /></p></form>';

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
			/*
			$line = '<tr><th colspan="2">&nbsp;</th>';
			foreach ($lister_regime as $regime) { $line .= '<th>'.$regime['r.name'].'</th>'; }
			$line .= '</tr>';
			$page['body']['contenu'] .= '<table id="table_menu"><caption>Menus de la SEMAINE du '.date('d/m/Y', $lundiSemaine).' au '.date('d/m/Y', strtotime("+6 day",$lundiSemaine)).'</caption><thead>'.$line.'</thead><tfoot>'.$line.'</tfoot><tbody>';

			for ($i=0; $i<sizeof($tab_jour); $i++) {
				$timestampJour = strtotime('+'.$i.' day', $lundiSemaine);
				$page['body']['contenu'] .= '<tr><td rowspan="2">'.$tab_jour[$i].'</td>';
				for ($j=0; $j<sizeof($tab_type); $j++) {
					if (($j%2)===1) {$page['body']['contenu'] .= '<tr>';}
					$page['body']['contenu'] .= '<td>'.$tab_type[$j].'</td>';

					$json = $requete->recup_calendrier(false, false, $timestampJour, $tab_type[$j]);
					$recup_calendrier = json_decode($json, true);
					if (!empty($recup_calendrier['result'])) {
						$idCalendrier = $recup_calendrier['result'][0]['c.id'];
					} else {
						$idCalendrier = 0;
					}
					foreach ($lister_regime as $regime) {
						$json = $requete->lister_menu_regime(false, $regime['r.id'], $idCalendrier);
						$lister_menu_regime = json_decode($json, true);
						if (!empty($lister_menu_regime['result'])) {
							$idMenu = $lister_menu_regime['result'][0]['m.idMenu'];
						} else {
							$idMenu = 0;
						}
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
					$page['body']['contenu'] .= '</tr>';
				}
			}
			*/

			$page['body']['contenu'] .= '</tbody></table><p><a href="menu-importer">Importer une semaine de menu</a></p>';

			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_menu").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/dataTable.french.txt.js"}});$("#table_menu").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
