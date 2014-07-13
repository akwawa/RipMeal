<?php

use PFBC\Form;
use PFBC\Element;

if (!empty($_SESSION[$application]['idRank'])) {
	echo 'logué';
} else {
	include("librairie/PFBC/Form.php");

if(isset($_POST["form"])) {
	if(Form::isValid($_POST["form"])) {
		if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
			perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
		} else {
			include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
			
			$login = $_POST['login'];
			$password = $_POST['password'];
			
			$requete = new Login;
			$json = $requete->verif_login($login, $password);
			$verif_login = json_decode($json, true);
			
			if (!empty($verif_login['result'])) {
				$verif_login = $verif_login['result'][0];

				session_regenerate_id(true);
				$_SESSION[$application]['id'] = $verif_login['u.id'];
				$_SESSION[$application]['login'] = $verif_login['u.login'];
				$_SESSION[$application]['idRank'] = $verif_login['u.idRank'];

				$json = $requete->recup_cookie($_SESSION[$application]['id']);
				$recup_cookie = json_decode($json, true);
			
				if (!empty($recup_cookie['result'])) {
					foreach ($recup_cookie['result'] as $cookie) {
						setcookie($cookie['u.text'], $cookie['uu.value']);
					}
				}

				$page['body']['contenu'] .= '<div class="info">Connexion réussie en tant que "'.$login.'"</div><div class="info">Si la redirection automatique ne fonctionne pas, cliquer <a href="login">ici</a></div>';
				header('Location: sauvegarde-maj');
				$login='';
				$password='';
			} else {
				$page['body']['contenu'] .= '<div class="alert">Le login ou le mot de passe est incorrect.</div>';
			}
		}
	} else {
		Form::renderAjaxErrorResponse($_POST["form"]);
	}
	exit();
}

	$form = new Form("login");
	// $form->configure(array(
	// 	"ajax" => 1,
	// 	"ajaxCallback" => "valide_login"
	// ));
	$form->addElement(new Element\HTML('<legend>Login</legend>'));
	$form->addElement(new Element\Hidden("form", "login"));
	$form->addElement(new Element\Textbox("Login", "login", array("required" => 1)));
	$form->addElement(new Element\Password("Mot de passe", "password", array("required" => 1)));
	$form->addElement(new Element\Checkbox("", "Remember", array("1" => "Se souvenir de moi")));
	$form->addElement(new Element\Button("Login"));
	$page['body']['contenu'] .= $form->render(true);

	$page['header']['js'][] = 'js-login-login.js';
}

?>