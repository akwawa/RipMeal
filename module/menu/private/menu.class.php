<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Menu extends requete {
		var $requete = '';
		var $table = 'menu';
		var $alias = 'm';
		var $allInfos = array();
		var $namesColumns = array();
		
		function listerColonnes($table=false) {
			if ($table===false) $table=$this->table;
			$tab = $this->colonnes($table);
			for ($i=0;$i<count($tab);$i++) {
				$this->namesColumns[$table][] = $tab[$i]['COLUMN_NAME'];
			}
			$this->allInfos = $tab;
		}
		
		function recup_calendrier($id=false, $date=false, $timestamp=false, $type=false) {
			if (empty($this->namesColumns['calendrier'])) $this->listerColonnes('calendrier');
			$this->__reset();
			$this->select(array('calendrier' => $this->namesColumns['calendrier']), 'c');
			// $where=array('c'=>array('id'=>$id));
			$where=array();
			if ($id!==false) $where['id']=$id;
			if ($date!==false) $where['date']=$date;
			if ($timestamp!==false) $where['timestamp']=$timestamp;
			if ($type!==false) $where['type']=$type;
			if (sizeof($where)>0) $this->where(array('c'=>$where));

			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function lister_menu_semaine($numSemaine=false) {
			if ($numSemaine === false){$numSemaine=strftime('%W');}
			$this->__reset();
		}

		function lister_regime($id=false, $name=false, $fullname=false, $idRemp=false) {
			$where=false;
			if ($id!==false){$where['id']=$id;}
			if ($name!==false){$where['name']=$name;}
			if ($fullname!==false){$where['fullname']=$fullname;}
			if ($idRemp!==false){$where['idRemp']=$idRemp;}
			return $this->fetch('regime', false, $where, false, false, 'r');
		}

		function lister_menu($id=false, $idEntree=false, $idViande=false, $idLegume=false, $idFromage=false, $idDessert=false, $supplement=false) {
			$this->__reset();
			$this->select(array('menu' => array('id')), 'm');
			$this->select(array('menu_entree' => array('id', 'name', 'fullname')), 'me');
			$this->select(array('menu_viande' => array('id', 'name', 'fullname')), 'mv');
			$this->select(array('menu_legume' => array('id', 'name', 'fullname')), 'ml');
			$this->select(array('menu_fromage' => array('id', 'name', 'fullname')), 'mf');
			$this->select(array('menu_dessert' => array('id', 'name', 'fullname')), 'md');
			
			if ($id!==false){$this->where(array('m'=>array('id'=>$id)));}
			if ($idEntree!==false){$this->where(array('m'=>array('idEntree'=>$idEntree)));}
			if ($idViande!==false){$this->where(array('m'=>array('idViande'=>$idViande)));}
			if ($idLegume!==false){$this->where(array('m'=>array('idLegume'=>$idLegume)));}
			if ($idFromage!==false){$this->where(array('m'=>array('idFromage'=>$idFromage)));}
			if ($idDessert!==false){$this->where(array('m'=>array('idDessert'=>$idDessert)));}
			if ($supplement!==false){$this->where(array('m'=>array('supplement'=>$supplement)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function lister_menu_regime($idMenu=false, $idRegime=false, $idCalendrier=false) {
			if (empty($this->namesColumns['menu_regime'])) $this->listerColonnes('menu_regime');
			$this->__reset();
			$this->select(array('menu_regime' => $this->namesColumns['menu_regime']), 'm');
			if ($idMenu!==false){$this->where(array('m'=>array('idMenu'=>$idMenu)));}
			if ($idRegime!==false){$this->where(array('m'=>array('idRegime'=>$idRegime)));}
			if ($idCalendrier!==false){$this->where(array('m'=>array('idCalendrier'=>$idCalendrier)));}

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

		function ajout_composant_menu($type, $id=false, $name, $fullname){
			if (empty($this->namesColumns['menu_'.$type])) $this->listerColonnes('menu_'.$type);
			$this->__reset();

			$tab_champs=array();
			if ($id!==false) $tab_champs['id']=$id;
			$tab_champs['name']=$name;
			$tab_champs['fullname']=$fullname;
			$this->insert('menu_'.$type, $tab_champs);

			$this->execute();
		}

		function recup_composant_menu($type, $id=false, $name, $fullname) {
			$where=false;
			if ($id!==false){$where['id']=$id;}
			if ($name!==false){$where['name']=$name;}
			if ($fullname!==false){$where['fullname']=$fullname;}
			return $this->fetch('menu_'.$type, false, $where, false, false, 'm');
		}

		function ajout_calendrier($id=false, $date=false, $timestamp=false, $type=false) {
			if (empty($this->namesColumns['calendrier'])) $this->listerColonnes('calendrier');
			$this->__reset();

			if ($date===false && $timestamp!==false) {
				$date = date("Y-m-d", $timestamp);
			} elseif ($date!==false && $timestamp===false) {
				$timestamp = strtotime($date);
			} else {
				return false;
			}

			$tab_champs=array();
			if ($id!==false) $tab_champs['id']=$id;
			$tab_champs['date']=$date;
			$tab_champs['timestamp']=$timestamp;
			$tab_champs['type']=$type;
			$this->insert('calendrier', $tab_champs);

			$this->execute();
		}

		function ajout_menu($id=false, $idEntree, $idViande, $idLegume, $idFromage, $idDessert, $supplement) {
			if (empty($this->namesColumns['menu'])) $this->listerColonnes('menu');
			$this->__reset();

			$tab_champs=array();
			if ($id!==false) $tab_champs['id']=$id;
			$tab_champs['idEntree']=$idEntree;
			$tab_champs['idViande']=$idViande;
			$tab_champs['idLegume']=$idLegume;
			$tab_champs['idFromage']=$idFromage;
			$tab_champs['idDessert']=$idDessert;
			$tab_champs['supplement']=$supplement;
			$this->insert('menu', $tab_champs);

			// echo $this->buildAll();
			$this->execute();
		}

		function ajout_menu_regime($idMenu, $idRegime, $idCalendrier) {
			$this->__reset();

			$tab_champs=array();
			$tab_champs['idMenu']=$idMenu;
			$tab_champs['idRegime']=$idRegime;
			$tab_champs['idCalendrier']=$idCalendrier;
			$this->insert('menu_regime', $tab_champs);

			// echo $this->buildAll();
			$this->execute();
		}
	}
	
?>