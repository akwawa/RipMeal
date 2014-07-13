<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$requete = new Client;
		$json = $requete->lister_clients();
		$lister_user = json_decode($json, true);

		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$page['body']['contenu'] .= '<table id="table_client"><thead><tr>
	<th>id</th>
	<th>name</th>
	<th>firstname</th>
	<th>sexe</th>
	<th>address</th>
	<th>fulladdress</th>
	<th>zip</th>
	<th>city</th>
	<th>phone</th>
	<th>secondPhone</th>
	<th>idTournee</th>
	<th>numeroTournee</th>
	<th>pain</th>
	<th>potage</th>
	<th>actif</th>
	<th>AlimentInterdit</th>
	<th>sacPorte</th>
	<th>corbeille</th>
	<th>ressourceName</th>
	<th>ressourceNumber</th>
	<th>ressourceSecondNumber</th>
	<th>ressourceAddress</th>
	<th>Action</th>
</tr></thead><tfoot><tr><th>id</th><th>name</th><th>firstname</th><th>sexe</th><th>address</th><th>fulladdress</th><th>zip</th><th>city</th><th>phone</th><th>secondPhone</th><th>idTournee</th><th>numeroTournee</th><th>pain</th><th>potage</th><th>actif</th><th>AlimentInterdit</th><th>sacPorte</th><th>corbeille</th><th>ressourceName</th><th>ressourceNumber</th><th>ressourceSecondNumber</th><th>ressourceAddress</th><th>Action</th></tr></tfoot><tbody>';
			foreach ($lister_user as $line) {
				$page['body']['contenu'] .= '<tr>
	<td>'.$line['c.id'].'</td>
	<td>'.$line['c.name'].'</td>
	<td>'.$line['c.firstname'].'</td>
	<td>'.$line['c.sexe'].'</td>
	<td>'.$line['c.address'].'</td>
	<td>'.$line['c.fulladdress'].'</td>
	<td>'.$line['c.zip'].'</td>
	<td>'.$line['c.city'].'</td>
	<td>'.$line['c.phone'].'</td>
	<td>'.$line['c.secondPhone'].'</td>
	<td>'.$line['t.name'].'</td>
	<td>'.$line['c.numeroTournee'].'</td>
	<td>'.$line['c.pain'].'</td>
	<td>'.$line['c.potage'].'</td>
	<td>'.(($line['c.actif']==true)?'oui':'non').'</td>
	<td>'.$line['c.AlimentInterdit'].'</td>
	<td>'.(($line['c.sacPorte']==true)?'oui':'non').'</td>
	<td>'.(($line['c.corbeille']==true)?'oui':'non').'</td>
	<td>'.$line['c.ressourceName'].'</td>
	<td>'.$line['c.ressourceNumber'].'</td>
	<td>'.$line['c.ressourceSecondNumber'].'</td>
	<td>'.$line['c.ressourceAddress'].'</td>
	<td><a class="popup" href="#" onclick="action(this);" data-id="'.$line['c.id'].'">Action</a></td>
</tr>';
			}
			$page['body']['contenu'] .= '</tbody></table><p><a href="client-ajouter">Ajouter un nouveau client</a></p>';
			
			/*** formulaires ***/
			$page['body']['contenu'] .= '<form action="#" method="post" id="compte-modifier" style="display:none;"><p><label for="login">Login</label><input type="text" name="login" id="login" value="" /></p><p><label for="pass">Mot de passe</label><input type="password" name="pass" id="pass" /></p><p><label for="rank">Rang</label><select id="rank" name="rank"></select></p><p><input type="hidden" name="niveau" id="niveau" value="1"><input type="submit" value="Modifier le compte"></p></form>';
			/*******************/
			
			$page['header']['js'][] = 'js-client-client.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_client").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_compte").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
