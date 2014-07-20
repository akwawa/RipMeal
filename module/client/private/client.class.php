<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Client extends requete {
		var $requete = '';
		var $table = 'client';
		var $alias = 'c';
		var $allInfos = array();
		var $namesColumns = array();
		
		function listerColonnes($table=false) {
			if ($table===false) $table=$this->table;
			$tab = $this->colonnes($table);
			$this->namesColumns[$table] = array();
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[$table][] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
			
		function lister_clients($id=false) {
			if (empty($this->namesColumns['client'])) {$this->listerColonnes('client');}
			$this->__reset();
			$this->select(array('client' => $this->namesColumns['client']), 'c');
			$this->select(array('tournee' => array('id', 'name', 'fullname')), 't');
			
			if ($id!==false){$this->where(array('c'=>array('id'=>$id)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function lister_tournee($id=false, $name=false, $fullname=false) {
			$where=false;
			if ($id!==false){$where['id']=$id;}
			if ($name!==false){$where['name']=$name;}
			if ($fullname!==false){$where['fullname']=$fullname;}
			return $this->fetch('tournee', false, $where, false, false, 't');
		}
		
		function lister_rank() {
			return $this->fetch('rank', array('id', 'name', 'description'), false, false, false, 'r');
		}
		
		function fetch($table, $colonne=false, $where=false, $limit=false, $order=false, $alias=false) {
			$alias=($alias===false)?((empty($this->alias))?$table:$this->alias):$alias;
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