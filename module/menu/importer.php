<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		// loadSemaine(this); 

		$page['body']['contenu'] .= '<form id="uploadSemaine" action="#" method="post" onsubmit="loadSemaine(this); return false;"><p><label for="file">Choix du fichier</label><input type="file" id="files" name="files[]" /></p><p><label for="delimiteur">Délimiteur</label><input type="text" name="delimiteur" id="delimiteur" value=";" size="1" maxlength="1" /></p><p><label for="dateCalendrier">Semaine du menu</label><input type="week" name="dateCalendrier" id="dateCalendrier" value="'.date('Y').'-W'.date('W').'" /></p><p><input type="submit" value="Importer les menus"></p></form><div id="resultat"></div>';

		$temps=(empty($_POST['dateCalendrier']))?time():strtotime($_POST['dateCalendrier']);
		$numSemaine = date('W', $temps);
		$lundiSemaine = week_dates($numSemaine, date('Y', $temps));

		$page['header']['js'][] = 'js-menu-menu.js';
			// $page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_menu").DataTable({"sDom":"CRT<\"clear\">lfrtip", "bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/dataTable.french.txt.js"}});$("#table_menu").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
	}
}
