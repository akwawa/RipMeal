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
$firstname = (empty($_REQUEST['firstname'])?false:$_REQUEST['firstname']);
$sexe = (empty($_REQUEST['sexe'])?false:$_REQUEST['sexe']);
$address = (empty($_REQUEST['address'])?false:$_REQUEST['address']);
$fulladdress = (empty($_REQUEST['fulladdress'])?false:$_REQUEST['fulladdress']);
$zip = (empty($_REQUEST['zip'])?false:$_REQUEST['zip']);
$city = (empty($_REQUEST['city'])?false:$_REQUEST['city']);
$phone = (empty($_REQUEST['phone'])?false:$_REQUEST['phone']);
$secondPhone = (empty($_REQUEST['secondPhone'])?false:$_REQUEST['secondPhone']);
$idTournee = (empty($_REQUEST['idTournee'])?false:$_REQUEST['idTournee']);
$numeroTournee = (empty($_REQUEST['numeroTournee'])?false:$_REQUEST['numeroTournee']);
$pain = (empty($_REQUEST['pain'])?false:$_REQUEST['pain']);
$potage = (empty($_REQUEST['potage'])?false:$_REQUEST['potage']);
$actif = (empty($_REQUEST['actif'])?false:$_REQUEST['actif']);
$info = (empty($_REQUEST['info'])?false:$_REQUEST['info']);
$AlimentInterdit = (empty($_REQUEST['AlimentInterdit'])?false:$_REQUEST['AlimentInterdit']);
$sacPorte = (empty($_REQUEST['sacPorte'])?false:$_REQUEST['sacPorte']);
$corbeille = (empty($_REQUEST['corbeille'])?false:$_REQUEST['corbeille']);
$ressourceName = (empty($_REQUEST['ressourceName'])?false:$_REQUEST['ressourceName']);
$ressourceNumber = (empty($_REQUEST['ressourceNumber'])?false:$_REQUEST['ressourceNumber']);
$ressourceSecondNumber = (empty($_REQUEST['ressourceSecondNumber'])?false:$_REQUEST['ressourceSecondNumber']);
$ressourceAddress = (empty($_REQUEST['ressourceAddress'])?false:$_REQUEST['ressourceAddress']);

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		$requete = new Client;
		$json = $requete->modif_client($id, $name, $firstname, $sexe, $address, $fulladdress, $zip, $city, $phone, $secondPhone, $idTournee, $numeroTournee, $pain, $potage, $actif, $info, $AlimentInterdit, $sacPorte, $corbeille, $ressourceName, $ressourceNumber, $ressourceSecondNumber, $ressourceAddress);
		$verif_login = json_decode($json, true);
		
		if (!empty($verif_login['result'])) {
			$verif_login = $verif_login['result'][0];

			session_regenerate_id(true);
			$_SESSION[$application]['id'] = $verif_login['u.id'];
			$_SESSION[$application]['login'] = $verif_login['u.login'];
			$_SESSION[$application]['idRank'] = $verif_login['u.idRank'];

			$json = $requete->recup_cookie($_SESSION[$application]['id']);
			$recup_cookie = json_decode($json, true);

			$page['body']['contenu'] .= '<div class="info">Connexion réussie en tant que "'.$login.'"</div><div class="info">Si la redirection automatique ne fonctionne pas, cliquer <a href="login">ici</a></div>';
		} else {
			$page['body']['contenu'] .= '<div class="alert">Le login ou le mot de passe est incorrect.</div>';
		}
	} else {
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	exit();
}
$tab_numeroTournee = array();

		$requete = new Client;
		$json = $requete->lister_clients();
		$lister_user = json_decode($json, true);

		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$th = '<tr><th>id</th><th>name</th><th>firstname</th><th>sexe</th><th>address</th><th>fulladdress</th><th>zip</th><th>city</th><th>phone</th><th>secondPhone</th><th>idTournee</th><th>nomTournee</th><th>numeroTournee</th><th>pain</th><th>potage</th><th>actif</th><th>AlimentInterdit</th><th>sacPorte</th><th>corbeille</th><th>ressourceName</th><th>ressourceNumber</th><th>ressourceSecondNumber</th><th>ressourceAddress</th><th>Action</th></tr>';
			$page['body']['contenu'] .= '<table id="table_client"><thead>'.$th.'</thead><tfoot>'.$th.'</tfoot><tbody>';
			foreach ($lister_user as $line) {
				if (empty($tab_numeroTournee[$line['c.idTournee']])) {
					$json = $requete->lister_numeroTournee($line['c.idTournee']);
					$lister_numeroTournee = json_decode($json, true);
					$tab_numeroTournee[$line['c.idTournee']] = $lister_numeroTournee['result'];
				}

				$page['body']['contenu'] .= '<tr id="tr_'.$line['c.id'].'"
		data-id="'.$line['c.id'].'"
		data-name="'.$line['c.name'].'"
		data-firstname="'.$line['c.firstname'].'"
		data-sexe="'.$line['c.sexe'].'"
		data-address="'.$line['c.address'].'"
		data-fulladdress="'.$line['c.fulladdress'].'"
		data-zip="'.$line['c.zip'].'"
		data-city="'.$line['c.city'].'"
		data-phone="'.$line['c.phone'].'"
		data-secondPhone="'.$line['c.secondPhone'].'"
		data-idTournee="'.$line['c.idTournee'].'"
		data-nameTournee="'.$line['t.name'].'"
		data-numeroTournee="'.$line['c.numeroTournee'].'"
		data-pain="'.$line['c.pain'].'"
		data-potage="'.$line['c.potage'].'"
		data-actif="'.(($line['c.actif']==true)?'1':'-1').'"
		data-AlimentInterdit="'.$line['c.AlimentInterdit'].'"
		data-sacPorte="'.(($line['c.sacPorte']==true)?'1':'-1').'"
		data-corbeille="'.(($line['c.corbeille']==true)?'1':'-1').'"
		data-ressourceName="'.$line['c.ressourceName'].'"
		data-ressourceNumber="'.$line['c.ressourceNumber'].'"
		data-ressourceAddress="'.$line['c.ressourceAddress'].'"
		>
	<td id="td_'.$line['c.id'].'_id">'.$line['c.id'].'</td>
	<td id="td_'.$line['c.id'].'_name">'.$line['c.name'].'</td>
	<td id="td_'.$line['c.id'].'_firstname">'.$line['c.firstname'].'</td>
	<td id="td_'.$line['c.id'].'_sexe">'.$line['c.sexe'].'</td>
	<td id="td_'.$line['c.id'].'_address">'.$line['c.address'].'</td>
	<td id="td_'.$line['c.id'].'_fulladdress">'.$line['c.fulladdress'].'</td>
	<td id="td_'.$line['c.id'].'_zip">'.$line['c.zip'].'</td>
	<td id="td_'.$line['c.id'].'_city">'.$line['c.city'].'</td>
	<td id="td_'.$line['c.id'].'_phone">'.$line['c.phone'].'</td>
	<td id="td_'.$line['c.id'].'_secondPhone">'.$line['c.secondPhone'].'</td>
	<td id="td_'.$line['c.id'].'_idTournee">'.$line['c.idTournee'].'</td>
	<td id="td_'.$line['c.id'].'_nameTournee">'.$line['t.name'].'</td>
	<td id="td_'.$line['c.id'].'_numeroTournee">'.$line['c.numeroTournee'].'</td>
	<td id="td_'.$line['c.id'].'_pain">'.$line['c.pain'].'</td>
	<td id="td_'.$line['c.id'].'_potage">'.$line['c.potage'].'</td>
	<td id="td_'.$line['c.id'].'_actif">'.(($line['c.actif']==true)?'oui':'non').'</td>
	<td id="td_'.$line['c.id'].'_AlimentInterdit">'.$line['c.AlimentInterdit'].'</td>
	<td id="td_'.$line['c.id'].'_sacPorte">'.(($line['c.sacPorte']==true)?'oui':'non').'</td>
	<td id="td_'.$line['c.id'].'_corbeille">'.(($line['c.corbeille']==true)?'oui':'non').'</td>
	<td id="td_'.$line['c.id'].'_ressourceName">'.$line['c.ressourceName'].'</td>
	<td id="td_'.$line['c.id'].'_ressourceNumber">'.$line['c.ressourceNumber'].'</td>
	<td id="td_'.$line['c.id'].'_ressourceSecondNumber">'.$line['c.ressourceSecondNumber'].'</td>
	<td id="td_'.$line['c.id'].'_ressourceAddress">'.$line['c.ressourceAddress'].'</td>
	<td><a class="popup" href="#" onclick="action(this);" data-id="'.$line['c.id'].'">Action</a></td>
</tr>';
			}
			$page['body']['contenu'] .= '</tbody></table><p><a href="client-ajouter">Ajouter un nouveau client</a></p>';

			/*** formulaires ***/
$tab_tournee = array();
$json = $requete->lister_tournee();
$lister_tournee = json_decode($json, true);

if (!empty($lister_tournee['result'])) {
	foreach ($lister_tournee['result'] as $tournee) {
		$tab_tournee[$tournee['t.id']] = $tournee['t.name'];
	}
}

$page['body']['contenu'] .= '<div id="tab_numeroTournee" name="tab_numeroTournee" class="desktop-hidden">'.json_encode($tab_numeroTournee).'</div>';

$form = new Form('client-modifier');
$form->configure(array("prevent" => array("bootstrap", "jQuery"),"action" => $_SERVER['REQUEST_URI']));
$form->addElement(new Element\HTML('<legend>Modifier un client</legend>'));
$form->setValues(array(
	"id" => $id,
	"name" => $name,
	"firstname" => $firstname,
	"sexe" => $sexe,
	"address" => $address,
	"fulladdress" => $fulladdress,
	"zip" => $zip,
	"city" => $city,
	"phone" => $phone,
	"secondPhone" => $secondPhone,
	"idTournee" => $idTournee,
	"numeroTournee" => $numeroTournee,
	"pain" => $pain,
	"potage" => $potage,
	"actif" => $actif,
	"info" => $info,
	"AlimentInterdit" => $AlimentInterdit,
	"sacPorte" => $sacPorte,
	"corbeille" => $corbeille,
	"ressourceName" => $ressourceName,
	"ressourceNumber" => $ressourceNumber,
	"ressourceSecondNumber" => $ressourceSecondNumber,
	"ressourceAddress" => $ressourceAddress
));
$form->addElement(new Element\HTML('<fieldset><legend>Informations générales</legend>'));
$form->addElement(new Element\Number('ID', 'id', array('id' => 'id', 'readonly' => true)));
$form->addElement(new Element\Textbox('Nom', 'name', array('id' => 'name')));
$form->addElement(new Element\Textbox('Prénom', 'firstname', array('id' => 'firstname')));
$form->addElement(new Element\Select('Sexe', 'sexe', array('F' => 'Femme', 'M' => 'Homme'), array('id' => 'sexe')));
$form->addElement(new Element\Textbox('Adresse', 'address', array('id' => 'address')));
$form->addElement(new Element\Textbox('Adresse complète', 'fulladdress', array('id' => 'fulladdress')));
$form->addElement(new Element\Number('Code postal', 'zip', array('id' => 'zip')));
$form->addElement(new Element\Textbox('Ville', 'city', array('id' => 'city')));
$form->addElement(new Element\Phone('Téléphone', 'phone', array('id' => 'phone')));
$form->addElement(new Element\Phone('Téléphone secondaire', 'secondPhone', array('id' => 'secondPhone')));
$form->addElement(new Element\HTML('</fieldset>'));

$form->addElement(new Element\HTML('<fieldset><legend>Tournées</legend>'));
$form->addElement(new Element\Select('Tournée', 'idTournee', $tab_tournee, array('id' => 'idTournee')));
$form->addElement(new Element\Select('Insérer après', 'numeroTournee', array(), array('id' => 'numeroTournee')));
$form->addElement(new Element\Number('Pain', 'pain', array('id' => 'pain')));
$form->addElement(new Element\Number('Potage', 'potage', array('id' => 'potage')));
$form->addElement(new Element\HTML('</fieldset>'));

$form->addElement(new Element\HTML('<fieldset><legend>Autres informations</legend>'));
$form->addElement(new Element\Select('Actif', 'actif', array("-1" => "Non", "1" => "Oui"), array('id' => 'actif')));
$form->addElement(new Element\Textarea("Info", "info"));
$form->addElement(new Element\Textarea("Aliment interdit", "AlimentInterdit"));
$form->addElement(new Element\Select('Sac à la porte', 'sacPorte', array("-1" => "Non", "1" => "Oui"), array('id' => 'sacPorte')));
$form->addElement(new Element\Select('Corbeille', 'corbeille', array("-1" => "Non", "1" => "Oui"), array('id' => 'corbeille')));
$form->addElement(new Element\HTML('</fieldset>'));

$form->addElement(new Element\HTML('<fieldset><legend>Personne ressource</legend>'));
$form->addElement(new Element\Textbox('Nom', 'ressourceName', array('id' => 'ressourceName')));
$form->addElement(new Element\Phone('Téléphone', 'ressourceNumber', array('id' => 'ressourceNumber')));
$form->addElement(new Element\Phone('Téléphone', 'ressourceSecondNumber', array('id' => 'ressourceSecondNumber')));
$form->addElement(new Element\Textbox('Adresse', 'ressourceAddress', array('id' => 'ressourceAddress')));
$form->addElement(new Element\HTML('</fieldset>'));

$form->addElement(new Element\Button("Modifier le client"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);
			/*******************/
			
			$page['header']['js'][] = 'js-client-client.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_client").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_compte").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
