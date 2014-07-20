<?php
if (empty($_SESSION[$application]['idRank'])) {
	
} else {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(21, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

	function lire_fichier($urlFichier) {
		$retour = false;
		$file = @fopen($urlFichier, "r");
		if ($file) {
			$textjson = '';
			while (!feof($file)) { $textjson .= fgets ($file, 1024); }
			fclose($file);
			$retour = json_decode($textjson, true);
		}
		return $retour;
	}

	function verif_maj($urlBase, $fichierVersion, $nomApplication = false, $resultat = false) {
		$retour = false;
		if ($fichier = lire_fichier($urlBase.$fichierVersion)) {
			foreach ($fichier as $fileVersion) {
				if ($fileVersion["nomApplication"] == $nomApplication) {
					if (floatval($resultat['version']) < floatval($fileVersion['version'])) {
						// if (floatval($resultat['version']) < floatval($fileVersion['minVersion'])) {
							// $ancienneVersion = verif_maj($urlBase, $fileVersion['minVersion']);
						// } else {
							$ancienneVersion = true;
						// }
						if ($ancienneVersion) {
							$retour[] =  array('version' => $fileVersion['version'], 'fichier' => 'fichierMaj_v'.$fileVersion['version'].'.zip', 'minVersion' => $fileVersion['minVersion'], 'fichierMaj' => $fileVersion['fichierMaj']);
							if (gettype($ancienneVersion) == 'array') { $retour[] = $ancienneVersion; }
						}
					}
				}
			}
		}
		return $retour;
	}
		
		// $racineSite = $application.'.'.$siteDistant;
		// $lien = $racineSite.'/'.$fichierVersion; // http://repas.perette.info/last_version.json
		$racineSite = 'repas.perette.info';
		$lien = $racineSite.'/last_version.json';
		if ($sock = @fsockopen($racineSite, 80, $num, $error, 5)) {
			$temp=$page['body']['contenu'];
			
			$requete = new Sauvegarde;
			$requete->liste_save();
			$liste_save = $requete->liste_save;
			$inconnu_base = true;
			$inconnu_logiciel = true;

			foreach ($liste_save as $fichier) {
				$nom_fichier=basename($fichier);
				$date_sauvegarde = substr($nom_fichier, 0, 10);
				$debut = stripos($fichier, '/')+1;
				$fin = stripos($fichier, '/', $debut)-$debut;
				$type_fichier=substr($fichier, $debut, $fin);
				if ($date_sauvegarde === date('Y-m-d')) {
					if ($type_fichier === "base") $inconnu_base = false;
					if ($type_fichier === "logiciel") $inconnu_logiciel = false;
				}
			}
			if ($inconnu_base === true || $inconnu_logiciel === true) include('creer.php');

			$page['body']['contenu']=$temp;

			$json = $requete->version_actuelle();
			$version_actuelle = json_decode($json, true);
			
			if ($version_actuelle['result']) {
				$resultat[$version_actuelle['result'][0]['p.name']] = $version_actuelle['result'][0]['p.value'];
				if ($retour = verif_maj('http://'.$lien, $application, $resultat)) {
					$page['body']['contenu'] .= '<p>Version actuelle : V '.$resultat['version'].' </p><div>Version disponible : <ul>';
					foreach ($retour as $nouvelleVersion) {
						if ($nouvelleVersion['minVersion'] <= $resultat['version']) {
							$page['body']['contenu'] .= '<li style="color:green;">V '.$nouvelleVersion['version'].' (version requise : V '.$nouvelleVersion['minVersion'].') <input type="button" value="Installer" data-urlFichier="'.$racineSite.'/'.$nouvelleVersion['fichierMaj'].'" data-version="'.$nouvelleVersion['version'].'" data-fichier="'.$nouvelleVersion['fichier'].'" onclick="miseAJour(this);" /></li>';
						} else {
							$page['body']['contenu'] .= '<li style="color:red;">V '.$nouvelleVersion['version'].' (version requise : V '.$nouvelleVersion['minVersion'].')</li>';
						}
					}
					$page['body']['contenu'] .= '</ul></div>';
				} else {
					$page['body']['contenu'] .= '<div class="info">Vous possédez la dernière version publiée</div>';
				}
			} else {
				$page['body']['contenu'] .= '<div class="alert">Impossible de trouver la version installée</div>';
			}
		} else {
			$page['body']['contenu'] .= '<div class="alert">Impossible de se connecter au site distant</div>';
		}
	}
}