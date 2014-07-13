<?php

include("librairie/PFBC/Form.php");
$form = new Form("login");
$form->addElement(new Element\HTML('<legend>Login</legend>'));
$form->addElement(new Element\Hidden("form", "login"));
$form->addElement(new Element\Email("Email Address:", "Email", array("required" => 1)));
$form->addElement(new Element\Password("Password:", "Password", array("required" => 1)));
$form->addElement(new Element\Checkbox("", "Remember", array("1" => "Remember me")));
$form->addElement(new Element\Button("Login"));
$html = $form->render(true);

/*
if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');
		
		$requete = new Compte;
		$json = $requete->lister_user();
		$lister_user = json_decode($json, true);
		
		if (!empty($lister_user['result'])) {
			$lister_user = $lister_user['result'];
			$page['body']['contenu'] .= '<table><thead><tr><th>Nom</th><th>Rang</th><th colspan="2">Action</th></tr></thead><tbody>';
			foreach ($lister_user as $membre) {
				$page['body']['contenu'] .= '<tr><td>'.$membre['u.login'].'</td><td>'.$membre['u.idRank'].'</td><td><a href="compte-modifier-'.$membre['u.id'].'">Modifier</a></td><td><a href="compte-supprimer-'.$membre['u.id'].'">Supprimer</a></td></tr>';
			}
			$page['body']['contenu'] .= '</tbody></table><p><a href="?menu=compte&amp;sousmenu=ajouterCompte">Ajouter un nouveau compte</a></p>';
			// $("select").uniform();
		}
	}
}

/*
if ($_SESSION) {
	if ($_SESSION['rang'] == 'Administrateur') {
		$login = (empty($_POST['login']))?false:$_POST['login'];
		$mdp = (empty($_POST['password']))?false:$_POST['password'];
		$rangInput = (empty($_POST['rang']))?false:$_POST['rang'];

		if ($login && $mdp && $rangInput) {
			$requete_membre = new requete();
			$requete_membre->insert('membre', array('nom' => $login, 'mdp' => array('VALEUR' => $mdp, 'SALAGE' => true), 'dateCreation' => time(), 'rang' => $rangInput));
			// echo $requete_membre->requete_complete();
			$requete_membre->executer_requete();
			$erreur = array_merge($erreur, $requete_membre->liste_erreurs);
			unset($requete_membre);
			echo '<p>Le compte a bien été créé.</p>';
		} else {
			echo '<form action="?menu=compte&amp;sousmenu=ajouterCompte" method="post"><p><label for="login">Login :</label><input type="text" name="login" id="login" value="'.$login.'"></p><p><label for="password">Mot de passe :</label><input type="password" name="password" id="password" value="'.$mdp.'"></p><p><label>Poste :</label>';
			foreach($rang as $cle => $valeur) {
				echo '<input type="radio" name="rang" value="'.$cle.'" '.(($cle == $rangInput)?'checked':'').'>'.$valeur.'&nbsp;';
			}
			echo '</p><p><input type="submit" value="Ajouter le compte"></p></form>';
		}
	} else {
		echo '<p class="erreur">Vous n\'avez pas les droits nécessaires pour effectuer cette action.</p>';
	}
	include_once('listerComptes.php');
}
*/