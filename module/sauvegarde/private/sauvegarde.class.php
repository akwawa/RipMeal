<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Sauvegarde extends requete {
		var $requete = '';
		var $liste_save = array();
		var $liste_save_bdd = array();
		var $liste_save_logiciel = array();
		var $liste_tables = array();
		var $temps = false;
		var $table = 'param';
		var $alias = 'p';
		
		function listerColonnes($table=false) {
			$table=($table===false)?$this->table:$table;
			$tab = $this->colonnes($table);
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
		
		function version_actuelle() {
			return $this->fetch($this->table, false, array('name' => 'version'));
		}
		
		function fetch($table, $colonne=false, $where=false, $limit=false, $order=false, $alias=false) {
			$alias=($alias===false)?((empty($this->alias))?$table:$this->alias):$alias;
			$columns=array();
			
			if (empty($this->namesColumns)) $this->listerColonnes();
			if ($colonne===false) $colonne=$this->namesColumns;
			foreach ($colonne as $temp) if (in_array($temp, $this->namesColumns)) $columns[] = $temp;
			
			$this->__reset();
			$this->select(array($table => $columns), $alias);
			if ($where!==false) $this->where(array($alias=>$where));
			if ($order!==false) $this->order(array($alias=>$order));
			if ($limit!==false) $this->limit($limit);
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
		
		function liste_tables() {
			$this->direct('show tables');
			$this->execute(false);
			$liste_tables = $this->resultat['result'];
			foreach ($liste_tables as $tables) {
				foreach ($tables as $table) {
					$this->liste_tables[] = $table;
				}
			}			
		}

		function save_existe($chemin, $nom){
			if ($chemin==='base') {
				$this->liste_save_bdd();
				return in_array($nom, $this->liste_save_bdd);
			} elseif ($chemin==='logiciel') {
				$this->liste_save_logiciel();
				return in_array($nom, $this->liste_save_logiciel);
			}
		}
		
		function liste_save() {
			$this->liste_save_bdd();
			$this->liste_save_logiciel();
		}

		function liste_save_bdd() {
			$repertoire = 'sav/base/';
			if (file_exists($repertoire) && $dossier = opendir($repertoire)) {
				while(false !== ($fichier = readdir($dossier))) {
					if($fichier != '.' && $fichier != '..' && $fichier != 'index.php') {
						$this->liste_save[] = $repertoire.$fichier;
						$this->liste_save_bdd[] = $fichier;
					}
				}
				closedir($dossier);
			}
		}
		
		function liste_save_logiciel() {
			$repertoire = 'sav/logiciel/';
			if (file_exists($repertoire) && $dossier = opendir($repertoire)) {
				while(false !== ($fichier = readdir($dossier))) {
					if($fichier != '.' && $fichier != '..' && $fichier != 'index.php') {
						$this->liste_save[] = $repertoire.$fichier;
						$this->liste_save_logiciel[] = $fichier;
					}
				}
				closedir($dossier);
			}
		}
		
		function save_totale() {
			if ($this->save_bdd() == true)
				if ($this->save_logiciel() == true)
					return true;
			return false;
		}
		
		function save_bdd() {
			if (empty($this->liste_tables)) $this->liste_tables();
			if ($this->temps==false) $this->temps=date('Y-m-d H-i-s');
			
			$fic_base='private/base.php';
			if (file_exists($fic_base)) include($fic_base); else echo 'Impossible de charger le fichier "'.$fic_base.'"';

			$repertoire = 'sav/base/'.$this->temps;
			if (!file_exists($repertoire)){mkdir($repertoire, 0777, true);}
			
			foreach ($this->liste_tables as $table) {
				passthru(sprintf('mysqldump --opt -h '.$PARAM_hote.' -u '.$PARAM_utilisateur.' --password='.$PARAM_mot_passe.' '.$PARAM_nom_bd.' '.$table.' > "'.$repertoire.'/'.$this->temps.'_'.$table.'.sql"'));
			}
			
			return $this->zip_repertoire($repertoire);
		}
		
		function save_logiciel() {
			if ($this->temps==false) $this->temps=date('Y-m-d H-i-s');
			$cheminArchive = 'sav/logiciel/';
			$repertoire = $cheminArchive.$this->temps;
			if (!file_exists($cheminArchive)){mkdir($cheminArchive, 0777, true);}
			$zip = new ZipArchive;
			if($zip->open($repertoire.'.zip', ZIPARCHIVE::CREATE)===true ) {
				$this->addFolderToZip('.', $zip, '.', array('./sav'));
				$zip->close();
				return true;
			}
			return false;
		}
		
		function zip_repertoire($repertoire) {
			$zip = new ZipArchive;
			if($zip->open($repertoire.'.zip', ZIPARCHIVE::CREATE) === true) {
				$this->addFolderToZip($repertoire, $zip);
				$zip->close();
				$this->rmdirr($repertoire);
				return true;
			}
			return false;
		}

		function addFolderToZip($dir, $zipArchive, $rep = '.', $exclure=array()){
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					if ($rep != '.') {
						$zipArchive->addEmptyDir($rep);
					}
					while (($file = readdir($dh)) !== false) {
						$temp = ($rep != '.')?$rep.'/'.$file:$file;
						// echo $dir.'/'.$file.' |'.in_array($dir.'/'.$file, $exclure).'|<br>';
						if (!in_array($dir.'/'.$file, $exclure)) {
							if (is_file($dir.'/'.$file)) {
								$zipArchive->addFile($dir.'/'.$file, $temp);
							} else {
								if (($file != ".") && ($file != "..")) {
									$this->addFolderToZip($dir.'/'.$file, $zipArchive, $temp);
								}
							}
						}
					}
				}
			}
		}

		function rmdirr($dir) {
			if($objs = glob($dir."/*")){
				foreach($objs as $obj) {
					is_dir($obj)?$this->rmdirr($obj):unlink($obj);
				}
			}
			rmdir($dir);
		}
		
		function supprimer_all_save() {
			$this->rmdirr('sav/base');
			$this->rmdirr('sav/logiciel');
		}
		
		function supprimer_save($chemin, $nom){
			if ($this->save_existe($chemin, $nom)===true) {
				unlink('sav/'.$chemin.'/'.$nom);
			}else{
				return false;
			}
		}
}