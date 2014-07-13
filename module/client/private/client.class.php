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
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
			
		function lister_clients($id=false) {
			// return $this->fetch($this->table);
			$this->__reset();
			$this->select(array('client' => array("id","name","firstname","sexe","address","fulladdress","zip","city","phone","secondPhone","idTournee","numeroTournee","pain","potage","actif","AlimentInterdit","sacPorte","corbeille","ressourceName","ressourceNumber","ressourceSecondNumber","ressourceAddress")), 'c');
			$this->select(array('tournee' => array('id', 'name', 'fullname')), 't');
			
			if ($id!==false){$this->where(array('c'=>array('id'=>$id)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
		
		function lister_rank() {
			return $this->fetch('rank', array('id', 'name', 'description'), false, false, false, 'r');
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