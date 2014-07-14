
<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Regime extends requete {
		var $requete = '';
		var $table = 'regime';
		var $alias = 'r';
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

		function lister_regime($id=false, $name=false, $fullname=false, $idRemp=false) {
			$where=false;
			if ($id!==false){$where['id']=$id;}
			if ($name!==false){$where['name']=$name;}
			if ($fullname!==false){$where['fullname']=$fullname;}
			if ($idRemp!==false){$where['idRemp']=$idRemp;}
			return $this->fetch('regime', false, $where, false, false, 'r');
		}

		function lister_nb_personne_regime($idRegime=false) {
			$this->__reset();
			$this->aggreg("count", "nbRepas", "idPersonne", "tper_reg", "tpr");
			$this->group("tpr", "idRegime");
			if ($id!==false){$this->where(array('tpr'=>array('id'=>$id)));}
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