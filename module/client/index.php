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
$pain = (empty($_REQUEST['pain'])?false:$_REQUEST['pain']);
$potage = (empty($_REQUEST['potage'])?false:$_REQUEST['potage']);
$actif = (empty($_REQUEST['actif'])?false:$_REQUEST['actif']);
$info = (empty($_REQUEST['info'])?false:$_REQUEST['info']);
$AlimentInterdit = (empty($_REQUEST['AlimentInterdit'])?false:$_REQUEST['AlimentInterdit']);
$sacPorte = (empty($_REQUEST['sacPorte'])?false:$_REQUEST['sacPorte']);
$corbeille = (empty($_REQUEST['corbeille'])?false:$_REQUEST['corbeille']);

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		$requete = new Client;
		if ($_POST["form"] === 'client-ajouter') {
			$requete->ajouter_client($id, $name, $firstname, $sexe, $address, $fulladdress, $zip, $city, $phone, $secondPhone, $pain, $potage, $actif, $info, $AlimentInterdit, $sacPorte, $corbeille);
			$json = $requete->liste_clients(false, $name, $firstname, $sexe, $address, $fulladdress, $zip, $city, $phone, $secondPhone, $pain, $potage, $actif, $info, $AlimentInterdit, $sacPorte, $corbeille);
			$liste_clients = json_decode($json, true);
			if (!empty($liste_clients['result'])) {
				$id = $liste_clients['result'][0]['t.id'];
			}
		} elseif ($_POST["form"] === 'client-modifier') {
			$json = $requete->modif_client($id, $name, $firstname, $sexe, $address, $fulladdress, $zip, $city, $phone, $secondPhone, $pain, $potage, $actif, $info, $AlimentInterdit, $sacPorte, $corbeille);
		}
		$result = true;
	} else {
		$result = false;
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	echo json_encode(array('result' => $result, 'id' => $id));
	exit();
}

$page['body']['contenu'] .= '<p><a class="button" href="#client-ajouter" onclick="ajouter_client();">Ajouter un nouveau client</a></p>';

$requete = new Client;
$json = $requete->liste_clients();
$liste_clients = json_decode($json, true);
if (!empty($liste_clients['result'])) {
	$liste_clients = $liste_clients['result'];
	$th = '<tr><th>id</th><th>name</th><th>firstname</th><th>sexe</th><th>address</th><th>fulladdress</th><th>zip</th><th>city</th><th>phone</th><th>secondPhone</th><th>pain</th><th>potage</th><th>actif</th><th>AlimentInterdit</th><th>sacPorte</th><th>corbeille</th><th>Action</th></tr>';
	$page['body']['contenu'] .= '<table id="table_client"><thead>'.$th.'</thead><tfoot>'.$th.'</tfoot><tbody>';
	foreach ($liste_clients as $line) {

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
		data-pain="'.$line['c.pain'].'"
		data-potage="'.$line['c.potage'].'"
		data-actif="'.(($line['c.actif']==true)?'1':'-1').'"
		data-AlimentInterdit="'.$line['c.AlimentInterdit'].'"
		data-sacPorte="'.(($line['c.sacPorte']==true)?'1':'-1').'"
		data-corbeille="'.(($line['c.corbeille']==true)?'1':'-1').'"
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
	<td id="td_'.$line['c.id'].'_pain">'.$line['c.pain'].'</td>
	<td id="td_'.$line['c.id'].'_potage">'.$line['c.potage'].'</td>
	<td id="td_'.$line['c.id'].'_actif">'.(($line['c.actif']==true)?'oui':'non').'</td>
	<td id="td_'.$line['c.id'].'_AlimentInterdit">'.$line['c.AlimentInterdit'].'</td>
	<td id="td_'.$line['c.id'].'_sacPorte">'.(($line['c.sacPorte']==true)?'oui':'non').'</td>
	<td id="td_'.$line['c.id'].'_corbeille">'.(($line['c.corbeille']==true)?'oui':'non').'</td>
	<td><a class="popup" href="#" onclick="action(this);" data-id="'.$line['c.id'].'">Action</a></td>
	</tr>';
}

$page['body']['contenu'] .= '</tbody></table>';


		/*** formulaires ***/
$form = new Form('client-modifier');
$form->configure(array("prevent" => array("bootstrap", "jQuery"),"action" => $_SERVER['REQUEST_URI']));
$form->configure(array(
	"ajax" => 1,
	"ajaxCallback" => "valide_modif_client",
	"class" => "desktop-hidden"
));
$form->addElement(new Element\Hidden("form", "client-modifier"));
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
	"pain" => $pain,
	"potage" => $potage,
	"actif" => $actif,
	"info" => $info,
	"AlimentInterdit" => $AlimentInterdit,
	"sacPorte" => $sacPorte,
	"corbeille" => $corbeille
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

$form->addElement(new Element\HTML('<fieldset><legend>Prévision des repas</legend>'));
$liste_jours=array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
$liste_repas=array('Midi', 'Soir');
$form->addElement(new Element\HTML('<table><thead><tr><th rowspan="2">&nbsp;</th>'));
foreach ($liste_jours as $jour) { $form->addElement(new Element\HTML('<th colspan="2">'.$jour.'</th>')); }
$form->addElement(new Element\HTML('</tr><tr>'));
foreach ($liste_repas as $repas) { $form->addElement(new Element\HTML('<th>'.$repas.'</th>'));}
$form->addElement(new Element\HTML('</tr></thead><tbody>'));
$form->addElement(new Element\HTML('<tr><th>Tournée</th>'));
$requete = new Client;
$json = $requete->liste_tournees();
$liste_tournees = json_decode($json, true);
if (!empty($liste_tournees['result'])) {
	$liste_tournees = $liste_tournees['result'];
	foreach ($liste_jours as $jour) {
		foreach ($liste_repas as $repas) {
			$form->addElement(new Element\HTML('<td><select>'));
			foreach ($liste_tournees as $tournee) {
				$form->addElement(new Element\HTML('<option value="'.$tournee['t.id'].'">'.$tournee['t.name'].'</option>'));
			}
			$form->addElement(new Element\HTML('</td>'));
		}
	}
}
$form->addElement(new Element\HTML('</tr>'));
$requete = new Client;
$json = $requete->liste_regimes();
$liste_regimes = json_decode($json, true);
if (!empty($liste_regimes['result'])) {
	$liste_regimes = $liste_regimes['result'];
	foreach ($liste_regimes as $regime) {
		$form->addElement(new Element\HTML('<tr><th>'.$regime['r.name'].'</th>'));
		foreach ($liste_jours as $jour) {
			foreach ($liste_repas as $repas) {
				$form->addElement(new Element\HTML('<td><input name="'.$jour.'_'.$repas.'_'.$regime['r.id'].'" type="number" min="0" value="0" size="1" maxsize="1" /></td>'));
			}
		}
		$form->addElement(new Element\HTML('<th>'.$regime['r.name'].'</th></tr>'));
	}
}
$form->addElement(new Element\HTML('</tbody></table></fieldset>'));

$form->addElement(new Element\Button("Modifier le client"));
$page['body']['contenu'] .= $form->render(true);
$page['header']['css']['direct'][] = $form->renderCSS(true);
$page['header']['js']['direct'][] = $form->renderJS(true);
			/*******************/
			
			$page['header']['js'][] = 'js-client-client.js';
			$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_client").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/jquery.dataTable.french.txt.js"}});$("#table_client").dataTable().columnFilter();$(".popup").click(function(){return false;});});';
		}
	}
}
