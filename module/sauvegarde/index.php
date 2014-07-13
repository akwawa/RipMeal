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

		$page['body']['contenu'] .= '<h2>Gérer les sauvegardes</h2><a class="button" href="sauvegarde-creer">Créer une nouvelle sauvegarde</a><table id="table_sauvegarde"><caption>Liste des sauvegardes</caption><thead><tr><th>Nom</th><th>Type</th><th>Taille</th><th>Action</th></tr></thead><tfoot><tr><th>Nom</th><th>Type</th><th>Taille</th><th>Action</th></tr></tfoot><tbody>';
		$taille_totale=0;
		$temp='';
		foreach ($liste_save as $fichier) {
			$debut = stripos($fichier, '/')+1;
			$fin = stripos($fichier, '/', $debut)-$debut;
			$taille_fichier = filesize($fichier);
			$taille_totale+=$taille_fichier;
			$nom_fichier=basename($fichier);
			$type_fichier=substr($fichier, $debut, $fin);
			$taille_fichier=human_filesize($taille_fichier);
			
			$temp .= '<tr><td>'.$nom_fichier.'</td><td>'.$type_fichier.'</td><td>'.$taille_fichier.'</td><td><a href="sauvegarde-supprimer-'.urlencode($type_fichier.'_'.$nom_fichier).'">Supprimer</a></td></tr>';
		}
		$total = '<tr><th>Taille totale</th><td>&nbsp;</td><td>'.human_filesize($taille_totale).'</td><td><a href="sauvegarde-supprimer-all">Tout supprimer</a></td></tr>';
		$page['body']['contenu'] .= $total.$temp.'</tbody></table>';
		
		$page['header']['js']['direct'][] = '$(document).ready(function(){$("#table_sauvegarde").DataTable({"sDom":"CRT<\"clear\">lfrtip","bStateSave":true,"oTableTools":{"sSwfPath":"swf/copy_csv_xls_pdf.swf"},"oLanguage":{"sUrl":"js/dataTable.french.txt.js"}});$("#table_sauvegarde").dataTable().columnFilter();});';
	}
}
?>