<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Tournee extends requete {
		var $requete = '';
		var $table = 'tournee';
		var $alias = 't';
		var $allInfos = array();
		var $namesColumns = array();
		
		function listerColonnes($table=false) {
			$this->__reset();
			if ($table===false) $table=$this->table;
			$tab = $this->colonnes($table);
			$this->namesColumns[$table] = array();
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[$table][] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}

		function lister_clients_tournee($id=false, $numeroTournee=false) {
			$this->__reset();

			$this->select(array('client' => array('id', 'name', 'firstname', 'numeroTournee', 'idTournee')), 'c');
			$this->order('c', 'numeroTournee');
			if ($id!==false){$this->where(array('c'=>array('idTournee'=>$id)));}
			if ($numeroTournee!==false){$this->where(array('c'=>array('numeroTournee'=>$numeroTournee)));}

			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function update_client_tournee($idClient, $numeroTournee, $idTournee) {
			$this->__reset();
			
			$this->update('client', array('numeroTournee'=>$numeroTournee, 'idTournee'=>$idTournee));
			$this->where(array('client'=>array('id'=>$idClient)));

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

		function ajouter_tournee($id=false, $name, $fullname){
			$this->__reset();
			$insert = array('name' => $name, 'fullname' => $fullname);
			if ($id!==false){$insert['id']=$id;}
			$this->insert('tournee', $insert);

			// echo $this->buildAll().'<br>';
			$this->execute();

			return true;
		}

		function modif_tournee($id, $name, $fullname) {
			$this->__reset();
			$this->update('tournee', array('name' => $name, 'fullname' => $fullname));
			$this->where(array('tournee'=>array('id'=>$id)));

			// echo $this->buildAll().'<br>';
			$this->execute();

			return true;
		}

		function supprimer_tournee($id) {
			$this->__reset();
			$this->delete('tournee', array('id'=>$id));
			// echo $this->buildAll().'<br>';
			return $this->execute();
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