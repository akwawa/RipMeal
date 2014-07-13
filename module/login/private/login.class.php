<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Login extends requete {
		var $requete = '';
		var $table = 'user';
		var $alias = 'u';
		var $allInfos = array();
		var $namesColumns = array();
		
		function listerColonnes($table=false) {
			$table=($table===false)?$this->table:$table;
			$tab = $this->colonnes($table);
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[$table][] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
		
		function verif_login($login, $password) {
			return $this->fetch($this->table, array('id', 'login', 'idRank'), array('login' => $login, 'pass' => array('value' => $password, 'salt' => true, 'hash' => 'sha1')), 1, false, $this->alias);
		}

		function recup_cookie($id){
			if (empty($this->namesColumns['user_userparam'])) $this->listerColonnes('user_userparam');
			if (empty($this->namesColumns['userparam'])) $this->listerColonnes('userparam');

			$this->__reset();
			$this->select(array('user_userparam' => array('value')), 'uu');
			$this->select(array('userparam' => array('text')), 'u');
			$this->where(array('uu'=>array('idUser' => $id)));
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
		
		function fetch($table, $colonne=false, $where=false, $limit=false, $order=false, $alias=false) {
			$alias=($alias===false)?$table:$alias;
			$columns=array();
			
			if (empty($this->namesColumns[$table])) $this->listerColonnes($table);
			if ($colonne===false) $colonne=$this->namesColumns[$table];
			foreach ($colonne as $temp) if (in_array($temp, $this->namesColumns[$table])) $columns[] = $temp;
			
			$this->__reset();
			$this->select(array($table => $columns), $alias);
			if ($where!==false) $this->where(array($alias=>$where));
			if ($order!==false) $this->order(array($alias=>$order));
			if ($limit!==false) $this->limit($limit);
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
	}
	
?>