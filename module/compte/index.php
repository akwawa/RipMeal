<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$requete = new Compte;
		$json = $requete->lister_user();
		$lister_user = json_decode($json, true);
		
		$json = $requete->lister_rank();
		$lister_rank = json_decode($json, true);
		$lister_rank = $lister_rank['result'];
		
		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$page['body']['contenu'] .= '<table id="table_compte"><thead><tr><th>Id</th><th>Login</th><th>Rang</th><th>Action</th></tr></thead><tfoot><tr><th>Id</th><th>Login</th><th>Rang</th><th>Action</th></tr></tfoot><tbody>';
			foreach ($lister_user as $membre) {
				$page['body']['contenu'] .= '<tr id="ligne_'.$membre['u.id'].'"><td>'.$membre['u.id'].'</td><td>'.$membre['u.login'].'</td><td>'.$membre['r.name'].'</td><td><a class="popup" href="#" onclick="action(this);" data-id="'.$membre['u.id'].'">Action</a></td></tr>';
			}
			$page['body']['contenu'] .= '</tbody></table><p><a href="compte-ajouter">Ajouter un nouveau compte</a></p>';
			
			/*** formulaires ***/
			$page['body']['contenu'] .= '<form action="#" method="post" id="compte-modifier" style="display:none;"><p><label for="login">Login</label><input type="text" name="login" id="login" value="" /></p><p><label for="pass">Mot de passe</label><input type="password" name="pass" id="pass" /></p><p><label for="rank">Rang</label><select id="rank" name="rank">';
			foreach($lister_rank as $valeur) {
				$page['body']['contenu'] .= '<option value="'.$valeur['r.id'].'">'.$valeur['r.name'].'</option>';
			}
			$page['body']['contenu'] .= '</select></p><p><input type="hidden" name="niveau" id="niveau" value="1"><input type="submit" value="Modifier le compte"></p></form>';
			/*******************/
			
			$page['header']['js'][] = 'js-compte-compte.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_compte").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_compte").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
