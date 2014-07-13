<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Maj extends requete {
		var $requete = '';
		var $table = 'param';
		var $alias = 'p';
		var $temps = false;
		
		function listerColonnes($table=false) {
			$table=($table===false)?$this->table:$table;
			$tab = $this->colonnes($table);
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
		
		function verif_login($login, $password) {
			return $this->fetch($this->table, array('id', 'login', 'idRank'), array('login' => $login, 'pass' => array('value' => $password, 'salt' => true, 'hash' => 'sha1')), 1, false, $this->alias);
		}

		function version_actuelle() {
			return $this->fetch($this->table, false, array('name' => 'version'));
		}
		
		function date_sauvegarde() {
			return $this->fetch($this->table, false, array('name' => 'date_sauvegarde'));
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
}