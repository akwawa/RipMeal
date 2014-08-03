<?php

	if (!class_exists('requete')) {
		if (file_exists('private/apiSQL.class.php')) {
			include_once('private/apiSQL.class.php');
		}
	}

	class Compte extends requete {
		var $requete = '';
		var $table = 'user';
		var $alias = 'u';
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

		function recup_salage() {
			$day = date("Y_m_d");
			$repertoire = 'module/compte/salage';
			$fichier = 'module/compte/salage/'.$day;
			if (!file_exists($fichier)) {
				if (!file_exists($repertoire)) mkdir($repertoire);
				$salage = getToken().rand(1, 9);
				$file = fopen($fichier,"w");
				fwrite($file, $salage);
				fclose($file);
			} else {
				$salage = file_get_contents($fichier);
			}
			return json_encode(array('result' => $salage));
		}
		
		function verif_login($login, $password) {
			return $this->fetch($this->table, array('id', 'login', 'idRank'), array('login' => $login, 'pass' => array('value' => $password, 'salt' => true, 'hash' => 'sha1')), 1, false, $this->alias);
		}
		
		function lister_user($id=false, $login=false, $pass=false, $idRank=false) {
			$this->__reset();
			$this->select(array('user' => array('id', 'login')), 'u');
			$this->select(array('rank' => array('id', 'name', 'description')), 'r');
			
			if ($id!==false){$this->where(array('u'=>array('id'=>$id)));}
			if ($login!==false){$this->where(array('u'=>array('login'=>$login)));}
			if ($pass!==false){$this->where(array('u'=>array("pass" => array('value' => $pass, 'salt' => true, 'hash' => 'sha1'))));}
			if ($idRank!==false){$this->where(array('u'=>array('idRank'=>$idRank)));}
			
			// echo $this->buildAll().'<br>';
			return $this->execute();
		}

		function add_user($login, $pass, $idRank) {
			$this->__reset();

			$tab_champs=array();
			$tab_champs['login']=$login;
			$tab_champs['pass']=array('value' => $pass, 'salt' => true, 'hash' => 'sha1');
			$tab_champs['idRank']=$idRank;
			$this->insert('user', $tab_champs);

			// echo $this->buildAll().'<br>';
			$this->execute();
		}
		
		function update_user($id, $login, $idRank, $pass=false) {
			$this->__reset();
			if ($pass!==false) {
				$this->update('user', array('login'=>$login, 'idRank'=>$idRank, 'pass' => array('value' => $password, 'salt' => true, 'hash' => 'sha1')));
			} else {
				$this->update('user', array('login'=>$login, 'idRank'=>$idRank));
			}
			
			$this->where(array('user'=>array('id'=>$id)));

			// echo $this->buildAll().'<br>';
			return $this->execute();
		}
		
		function delete_user($id) {
			// return $this->fetch($this->table);
			$this->__reset();
			$this->delete('user', array('id'=>$id));
			
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