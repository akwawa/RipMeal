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
			
		function liste_clients($id=false) {
			if (empty($this->namesColumns['client'])) {$this->listerColonnes('client');}
			$this->__reset();
			$this->select(array('client' => $this->namesColumns['client']), 'c');
			$this->select(array('tournee' => array('id', 'name', 'fullname')), 't');
			
			if ($id!==false){$this->where(array('c'=>array('id'=>$id)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function liste_regimes($id=false, $name=false, $fullname=false, $color=false, $idRemp=false) {
			if (empty($this->namesColumns['regime'])) {$this->listerColonnes('regime');}
			$this->__reset();
			$this->select(array('regime' => $this->namesColumns['regime']), 'r');
			
			if ($id!==false){$this->where(array('r'=>array('id'=>$id)));}
			if ($name!==false){$this->where(array('r'=>array('name'=>$name)));}
			if ($fullname!==false){$this->where(array('r'=>array('fullname'=>$fullname)));}
			if ($color!==false){$this->where(array('r'=>array('color'=>$color)));}
			if ($idRemp!==false){$this->where(array('r'=>array('idRemp'=>$idRemp)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function liste_tournees($id=false, $name=false, $fullname=false) {
			$this->__reset();
			$table='tournee';
			$alias='t';
			if (empty($this->namesColumns[$table])) {$this->listerColonnes($table);}
			$this->select(array($table => $this->namesColumns[$table]), $alias);

			if ($id!==false){$this->where(array($alias=>array('id'=>$id)));}
			if ($name!==false){$this->where(array($alias=>array('name'=>$name)));}
			if ($fullname!==false){$this->where(array($alias=>array('fullname'=>$fullname)));}

			return $this->execute();
		}
		
		function modif_tournee($id, $name, $firstname, $sexe, $address, $fulladdress, $zip, $city, $phone, $secondPhone, $pain, $potage, $actif, $info, $AlimentInterdit, $sacPorte, $corbeille) {
			$this->__reset();
			$table='tournee';
			$this->update($table, array('name' => $name, 'firstname' => $firstname, 'sexe' => $sexe, 'address' => $address, 'fulladdress' => $fulladdress, 'zip' => $zip, 'city' => $city, 'phone' => $phone, 'secondPhone' => $secondPhone, 'pain' => $pain, 'potage' => $potage, 'actif' => $actif, 'info' => $info, 'AlimentInterdit' => $AlimentInterdit, 'sacPorte' => $sacPorte, 'corbeille' => $corbeille));
			$this->where(array($table=>array('id'=>$id)));

			// echo $this->buildAll().'<br>';
			$this->execute();

			return true;
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