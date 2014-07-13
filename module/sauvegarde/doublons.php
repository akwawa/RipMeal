<?php
if (empty($_SESSION[$application]['idRank'])) {
	
} else {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(21, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$requete = new Sauvegarde;
		$requete->liste_save();
		$liste_save = $requete->liste_save;

		$page['header']['css'][] = 'css-sauvegarde-sauvegarde.css';
		$page['body']['contenu'] .= '<h2>Gérer les sauvegardes</h2><a class="button" href="sauvegarde-creer">Créer une nouvelle sauvegarde</a><table><caption>Liste des sauvegardes</caption><thead><tr><th>Nom</th><th>Type</th><th>Taille</th><th>CRC 32</th></tr></thead><tbody>';
		$taille_totale=0;
		foreach ($liste_save as $fichier) {
			$debut = stripos($fichier, '/')+1;
			$fin = stripos($fichier, '/', $debut)-$debut;
			$taille_fichier = filesize($fichier);
			$taille_totale+=$taille_fichier;
			$nom_fichier=basename($fichier);
			$type_fichier=substr($fichier, $debut, $fin);
			$taille_fichier=human_filesize($taille_fichier);
			$hash_fichier=hash_file('md5', $fichier);

			$zip = new ZipArchive;
			if ($zip->open($fichier) === TRUE) {
				$zip->extractTo($nom_fichier'/');
				$zip->close();
			} else {
				// failed to extract.
			}
			unset($zip);
			
			$page['body']['contenu'] .= '<tr><td>'.$nom_fichier.'</td><td>'.$type_fichier.'</td><td>'.$taille_fichier.'</td><td>'.$hash_fichier.'</td></tr>';
		}
		$page['body']['contenu'] .= '</tbody><tfoot><th colspan="2">Taille totale</th><td>'.human_filesize($taille_totale).'</td><td></td></tfoot></table>';
	}
}
?>