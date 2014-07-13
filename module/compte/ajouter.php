<?php

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		include("librairie/PFBC/Form.php");
		
		$login=$pass=$idRank='';

	if(isset($_POST["form"])) {
		if(Form::isValid($_POST["form"])) {		
			$login = $_POST['login'];
			$pass = $_POST['password'];
			$idRank = $_POST['rank'];
			
			$requete = new Compte;
			$json = $requete->lister_user(false, $login);
			$lister_user = json_decode($json, true);

			if (!empty($lister_user['result'])) {
				Form::setError("compte", 'Erreur: Le nom de compte "'.$login.'" existe déjà');
			} else {
				$json = $requete->add_user($login, $pass, $idRank);
				$page['body']['contenu'] .= '<div class="info">Le compte "'.$login.'" a bien été créé.</div><p><a href="compte">Retour à la liste des compte</a></p>';
			}
		} else {
			Form::renderAjaxErrorResponse($_POST["form"]);
		}
	}
		$requete = new Compte;
		$json = $requete->lister_rank();
		$lister_rank = json_decode($json, true);
		$tab_rank=array();
		if (!empty($lister_rank['result'])) {
			foreach ($lister_rank['result'] as $rank) { $tab_rank[$rank["r.id"]]=$rank["r.name"]; }
		}
		
		$form = new Form("compte");
		$form->configure(array("action" => $_SERVER['REQUEST_URI']));
		$form->setValues(array(
			"login" => $login,
			"password" => $pass,
			"rank" => $idRank
		));
		$form->addElement(new Element\HTML('<legend>Nouveau compte</legend>'));
		$form->addElement(new Element\Hidden("form", "compte"));
		$form->addElement(new Element\Textbox("Login", "login", array("required" => 1)));
		$form->addElement(new Element\Password("Mot de passe", "password", array("required" => 1)));
		$form->addElement(new Element\Select("Rang", "rank", $tab_rank));
		$form->addElement(new Element\Button("Ajouter le compte"));
		$page['body']['contenu'] .= $form->render(true);
	}
}