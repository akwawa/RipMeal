<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Parametre extends requete {
		var $requete = '';
		var $table = 'client';
		var $alias = 'c';
		var $allInfos = array();
		var $namesColumns = array();
		
		function listerColonnes($table=false) {
			if ($table===false) $table=$this->table;
			$tab = $this->colonnes($table);
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[$table][] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
			return $this->namesColumns[$table];
		}
			
		function lister_param($idUserparam=false) {
			$this->__reset();
			$this->select(array('userparam' => $this->listerColonnes('userparam')), 'u');
			$this->select(array('user_userparam' => $this->listerColonnes('user_userparam')), 'uu');
			$this->join('u', 'uu', 'right');
			
			if ($idUserparam!==false){$this->where(array('uu'=>array('idUserparam'=>$idUserparam)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
		
		function update_param($idUser, $tab_value) {
			foreach ($tab_value as $idUserparam => $value) {
				$json = $this->lister_param($idUserparam);
				$lister_param = json_decode($json, true);

				$this->__reset();
				if (!empty($lister_param['result'])) {
					$this->update('user_userparam', array('value' => $value));
					$this->where(array('user_userparam'=>array('idUser'=>$idUser, 'idUserparam' => $idUserparam)));
				} else {
					$this->insert('user_userparam', array('idUser'=>$idUser, 'idUserparam' => $idUserparam, 'value' => $value));
				}

				// echo $this->buildAll().'<br>';
				$this->execute();
			}

			return true;
		}
		
		function fetch($table, $colonne=false, $where=false, $limit=false, $order=false, $alias=false) {
			$alias=($alias===false)?((empty($this->alias))?$table:$this->alias):$alias;
			$columns=array();
			
			if (empty($this->namesColumns)) $this->listerColonnes($table);
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
	
?>