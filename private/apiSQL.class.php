<?php
/*** TO DO LIST ***/
/* HAVING */

/* UPDATE */
/* DELETE */
/* INSERT INTO */

/* SELECT INTO */

/* fonction CASE et SWITCH */

/* UNION / UNION ALL */
/* INTERSECT */
/* EXCEPT / MINUS */

/* CREATE DATABASE */
/* DROP DATABASE */
/* CREATE TABLE */
/* ALTER TABLE */
/* DROP TABLE */
/* sous-requêtes */
/******************/


setlocale(LC_TIME, 'fr_FR.utf8','fra');

class requete {
	/* Variables par code PHP */
	var $fichierConnexion = 'base.php';
	var $distinct = false;
	/**************************/

	/* Variables par fichier connexion */
	var $type_base = 'mysql';
	var $jeux_de_caracteres = 'utf8';
	var $sel = '';
	var $prefixe = '';
	var $information_schema = '';
	var $TABLE_SCHEMA = '';
	/***********************************/

	/* Variables générés par apiSQL */
	var $dbh = NULL;
	var $requete = '';
	var $tabRequete = array();
	var $tabAgregat = array('MIN' => true, 'MAX' => true, 'COUNT' => true, 'SUM' => true, 'UPPER' => true, 'LOWER' => true, 'AVG' => true, 'LTRIM' => true, 'RTRIM' => true, 'TRIM' => true, 'SOUNDEX' => true, 'CHAR_LENGTH' => true, 'LENGTH' => true, 'ASCII' => true, 'LCASE' => true, 'UCASE' => true, 'CHAR' => true, 'SPACE' => true, 'DAYOFTHEMONTH' => true, 'DAYOFWEEK' => true, 'DAYOFYEAR' => true, 'HOUR' => true, 'MINUTE' => true, 'MONTH' => true, 'ABS' => true, 'ACOS' => true, 'ASIN' => true, 'ATAN' => true, 'CEILING' => true, 'COS' => true, 'COT' => true, 'DEGREES' => true, 'EXP' => true, 'FLOOR' => true, 'LOG' => true, 'LOG10' => true, 'RADIANS' => true, 'SIGN' => true, 'SQRT' => true, 'SIN' => true, 'TAN' => true, 'NOW' => true, 'CURRENT_DATE' => true, 'CURDATE' => true, 'CURTIME' => true, 'PI' => true, 'CONCAT' => true, 'REPLACE' => true, 'SUBSTRING' => true, 'LEFT' => true, 'RIGHT' => true, 'TIMESTAMPADD' => true, 'ROUND' => true, 'TRUNCATE' => true, 'IFNULL' => true, 'IIF' => true, 'REPEAT' => true, 'STRCMP' => true, 'LOCATE' => true, 'INSERT' => true, 'ATAN2' => true, 'MOD' => true, 'POWER' => true, 'RAND' => true, 'FIRST' => false, 'LAST' => false, 'DIFFERENCE' => false);
	var $tabNameReq = array();
	var $resultat = array();
// CASE SWITCH
	/********************************/

	function __construct($connexion = NULL) {
		$this->fichierConnexion = (empty($connexion))?dirname(__FILE__).'/'.$this->fichierConnexion:$connexion;
		if (file_exists($this->fichierConnexion)) {
			include($this->fichierConnexion);
			$this->sel = (empty($sel))?'':$sel;
			$this->prefixe = (empty($prefixe))?'':$prefixe;
			$this->TABLE_SCHEMA = $PARAM_nom_bd;
			// $this->information_schema = (empty($information_schema))?'INFORMATION_SCHEMA.':$information_schema;
			$this->information_schema = ($base_privee===false)?'v2__':'INFORMATION_SCHEMA.';

			try {
				$pdo = new PDO($this->type_base.':host='.$PARAM_hote.';dbname='.$PARAM_nom_bd,
								$PARAM_utilisateur, $PARAM_mot_passe,
								array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.(($this->jeux_de_caracteres)?$this->jeux_de_caracteres:'utf8')));
				$this->dbh = $pdo;
				unset($pdo);
			} catch(Exception $e) {
				// $retour['error'] = $e->getMessage().'<br />N° : '.$e->getCode();
				echo $e->getMessage().'<br />N° : '.$e->getCode();
				die();
			}
		} else {
			echo 'Impossible de trouver le fichier de connexion';
			// $retour['error'] = 'Impossible de trouver le fichier de connexion';
		}
	}
	
	function __destruct() {
		$this->dbh = null;
	}
	
	function __reset() {
		$this->distinct = false;
		$this->requete = '';
		$this->tabRequete = array();
		$this->tabNameReq = array();
		$this->resultat = array();
	}
	
	/* fonction de requete */
	public function select($table, $aliasTable = NULL, $affichage = true) {
		$tabTemp = array();
		if (gettype($table) == 'string') {
			$aliasTable = empty($aliasTable)?$table:$aliasTable;
			$tabTemp[$aliasTable] = array('nomTable' => $this->prefixe.$table, 'aliasTable' => $aliasTable, 'join' => array('ok' => false));
			if ($affichage===true) $tabTemp[$aliasTable]['champs'] = array('*' => NULL);
		} elseif (gettype($table) == 'array') {
			foreach ($table as $nomTable => $listeChamps) {
				$Temp_nomTable = $nomTable;
				$Temp_listeChamps = array();

				if (gettype($nomTable) == 'integer') {
					$aliasTable = (count($table) == 1)?(empty($aliasTable)?$listeChamps:$aliasTable):$listeChamps;
					$Temp_listeChamps['*'] = NULL;
					$Temp_nomTable = $listeChamps;
				} elseif (gettype($nomTable) == 'string') {
					$aliasTable = (count($table) == 1)?(empty($aliasTable)?$nomTable:$aliasTable):$nomTable;
					// echo $aliasTable;
					if (gettype($listeChamps) == 'string') {
						$Temp_listeChamps[$listeChamps] = $aliasTable.'.'.$listeChamps;
					} elseif (gettype($listeChamps) == 'array') {
						$temp = array();
						foreach ($listeChamps as $champs => $aliasChamps) {
							if (gettype($champs) == 'integer') {
								$temp_champs = $aliasChamps;
								$temp_aliasChamps = $aliasTable.'.'.$temp_champs;
							} else {
								$temp_champs = $champs;
								$temp_aliasChamps = $aliasChamps;
							}
							$temp[$temp_champs] = $temp_aliasChamps;
						}
						$Temp_listeChamps = $temp;
					}
				}
				$tabTemp[$aliasTable] = array('nomTable' => $this->prefixe.$Temp_nomTable, 'aliasTable' => $aliasTable, 'join' => array('ok' => false), 'champs' => $Temp_listeChamps);
			}
		}
		if (empty($this->tabRequete['select'])) {
			$this->tabRequete['select'] = $tabTemp;
		} else {
			$this->tabRequete['select'] = array_merge($this->tabRequete['select'], $tabTemp);
		}
	}
	
	public function insert($nom_table, $tab_champs) {
		$tab_temp = array();
		foreach ($tab_champs as $nomChamps => $value) {
			if (gettype($value) == 'array') {
				if (isset($value['salt'])) {
					$value['value'] = $value['value'].$this->sel;
				}
				if (isset($value['hash'])) {
					if (is_callable($value['hash'])) {
						$value['value'] = $value['hash']($value['value']);
					}
				}
				$value = $value['value'];
			}
			$nomChampsTemp = 'name_'.rand();
			while (isset($this->tabNameReq[$nomChampsTemp])) {
				$nomChampsTemp = 'name_'.rand();
			}
			$this->tabNameReq[$nomChampsTemp] = $value;
			$tab_temp[$nomChamps] = array('name' => $nomChampsTemp, 'value' => $value);
		}
		$this->tabRequete['insert'][$this->prefixe.$nom_table] = $tab_temp;
	}
	
	public function update($nom_table, $tab_champs) {
		$tab_temp = array();
		foreach ($tab_champs as $nomChamps => $value) {
			if (gettype($value) == 'array') {
				if (isset($value['salt'])) {
					$value['value'] = $value['value'].$this->sel;
				}
				if (isset($value['hash'])) {
					if (is_callable($value['hash'])) {
						$value['value'] = $value['hash']($value['value']);
					}
				}
				$value = $value['value'];
			}
			$nomChampsTemp = 'name_'.rand();
			while (isset($this->tabNameReq[$nomChampsTemp])) {
				$nomChampsTemp = 'name_'.rand();
			}
			$this->tabNameReq[$nomChampsTemp] = $value;
			$tab_temp[$nomChamps] = array('name' => $nomChampsTemp, 'value' => $value);
		}
		$this->tabRequete['update'][$nom_table] = $tab_temp;
	}

	public function delete($nom_table, $tab_where) {
		$this->where(array($this->prefixe.$nom_table => $tab_where));
		$this->tabRequete['delete'] = $nom_table;
	}
	
	public function aggreg($function, $aliasCol = NULL, $col = NULL, $table = NULL, $aliasTable = NULL, $separator = NULL) {
		if (!empty($this->tabAgregat[strtoupper($function)])) {
			$aliasCol = empty($aliasCol)?$function:$aliasCol;
			$col = (gettype($col)=='string')?array($col):((empty($col))?array():$col);
			$table = empty($table)?false:$table;
			$aliasTable = empty($aliasTable)?$table:$aliasTable;
			$separator = empty($separator)?', ':$separator;
			if ($table) {
				if (empty($this->tabRequete['select'][$aliasTable])) {
					$this->tabRequete['select'][$aliasTable] = array('nomTable' => $this->prefixe.$table, 'aliasTable' => $aliasTable, 'join' => array('ok' => false));
				}
			} elseif (empty($this->tabRequete['select'])) {
				$this->tabRequete['select'] = true;
			}
			$this->tabRequete['aggreg'][$aliasCol][$function] = array('cols' => $col, 'table' => $table, 'aliasTable' => $aliasTable, 'separator' => $separator);
		}
	}
	
	public function join($a1, $a2, $typeJoin = 'INNER', $condJoin = '=') {
		// $this->tabRequete['join'][] = array('a1' => $a1, 'a2' => $a2, 'typeJoin' => $typeJoin);
		// $tabTemp[$aliasTable] = array('nomTable' => $this->prefixe.$table, 'aliasTable' => $aliasTable, 'join' => array('ok' => false));
		if (empty($this->tabRequete['select'][$a1])) {
			$this->tabRequete['select'][$a1] = array('nomTable' => $this->prefixe.$a1, 'aliasTable' => $a1, 'join' => array('ok' => false, $a2 => array('typeJoin' => $typeJoin, 'condJoin' => $condJoin)));
		} else {
			$this->tabRequete['select'][$a1]['join'][$a2] = array('typeJoin' => $typeJoin, 'condJoin' => $condJoin);
		}
		/* Changer sens de jointure si problème */
		if (empty($this->tabRequete['select'][$a2])) {
			$this->tabRequete['select'][$a2] = array('nomTable' => $this->prefixe.$a2, 'aliasTable' => $a2, 'join' => array('ok' => false, $a1 => array('typeJoin' => $typeJoin, 'condJoin' => $condJoin)));
		} else {
			$this->tabRequete['select'][$a2]['join'][$a1] = array('typeJoin' => $typeJoin, 'condJoin' => $condJoin);
		}
	}
	
	public function where($liste_table, $groupe = NULL) {
		$tabTemp = array();
		// array([Groupe] => array([0] => $temp))
		/* $temp = Array(
					'table' => $nom_table,
					'col' => $nom_colonne,
					'value' => $valeur,
					'name' => $name,
					'type' => $type,
					'operator' => $operateur,
					'conj => $conjonction
					);
		*/
		while (!$groupe) {
			$groupe = rand();
			if (isset($this->tabRequete['where'][$groupe])) {
				$groupe = NULL;
			}
		}

		foreach ($liste_table as $tableName => $table) {
			if (gettype($table) == 'array') {
				foreach ($table as $colName => $value) {
					if (gettype($value) == 'array') {
						$groupe = empty($value['group'])?$groupe:$value['group'];
						$tabTemp[$groupe]['table'] = $tableName;
						$tabTemp[$groupe]['col'] = (empty($value['col']))?$colName:$value['col'];
						if (!empty($value['salt'])) {
							$value['value'] = $value['value'].$this->sel;
						}
						if (!empty($value['hash'])) {
							if (is_callable($value['hash'])) {
								$value['value'] = $value['hash']($value['value']);
							}
						}
						$tabTemp[$groupe]['value'] = empty($value['value'])?'':$value['value'];
						$tabTemp[$groupe]['type'] = gettype($value['value']);
						$tabTemp[$groupe]['operator'] = empty($value['operator'])?'=':$value['operator'];
						$tabTemp[$groupe]['conj'] = empty($value['conj'])?'AND':$value['conj'];
						$tabTemp[$groupe]['name'] = empty($value['name'])?NULL:$value['name'];
					} else {
						$tabTemp[$groupe]['table'] = $tableName;
						$tabTemp[$groupe]['col'] = $colName;
						$tabTemp[$groupe]['value'] = $value;
						$tabTemp[$groupe]['type'] = gettype($value);
						$tabTemp[$groupe]['operator'] = '=';
						$tabTemp[$groupe]['conj'] = 'AND';
						$tabTemp[$groupe]['name'] = NULL;
					}
					while (empty($tabTemp[$groupe]['name'])) {
						$tabTemp[$groupe]['name'] = 'name_'.rand();
						if (isset($this->tabNameReq[$tabTemp[$groupe]['name']])) {
							$tabTemp[$groupe]['name'] = NULL;
						}
					}
					foreach ($tabTemp as $groupe => $table) {
						$this->tabRequete['where'][$groupe][] = $table;
					}
					$this->tabNameReq[$tabTemp[$groupe]['name']] = $tabTemp[$groupe]['value'];
				}
			}
		}
	}
	
	public function group($table, $col) {
		$this->tabRequete['group'][] = $table.'.'.$col;
	}
	
	public function having($function, $col, $table = NULL, $aliasTable = NULL, $valeur = NULL, $operator = "=", $separator = "AND") {
		if (!empty($this->tabAgregat[strtoupper($function)])) {
			if (isset($table) && !isset($valeur)) {
				$valeur = $table;
				$table = false;
			}
			$aliasTable = empty($aliasTable)?$table:$aliasTable;
			if ($table) {
				if (empty($this->tabRequete['select'][$aliasTable])) {
					$this->tabRequete['select'][$aliasTable] = array('nomTable' => $this->prefixe.$table, 'aliasTable' => $aliasTable, 'join' => array('ok' => false));
				}
			}
			$col = empty($aliasTable)?$col:$aliasTable.'.'.$col;
			$this->tabRequete['having'][$function][] = array('col' => $col, 'operator' => $operator, 'valeur' => $valeur, 'separator' => $separator);
		}
	}
	
	public function order($table, $col = NULL, $sens = 'ASC') {
		if (gettype($table) == 'string' && !empty($col)) {
			$this->tabRequete['order'][$table][$col] = $sens;
		} elseif (gettype($table) == 'array') {
			foreach ($table as $table => $infos) {
				foreach ($infos as $col => $sens) {
					$this->tabRequete['order'][$table][$col] = $sens;
				}
			}
		}
	}
	
	public 	function limit($debut = 0, $offset = 0) {
		$this->tabRequete['limit'] = array('debut' => $debut, 'offset' => $offset);
	}
	
	public function build() {
		$requete = '';
		// Construit la requête et la renvoie en echo
		{ /* Partie SELECT */
		if (!empty($this->tabRequete['select'])) {
			$text_SELECT = 'SELECT '.($this->distinct?'DISTINCT ':'');
			$numTable = 0;
			$tabSelect = $this->tabRequete['select'];
			if (gettype($tabSelect) == 'array') {
				foreach ($tabSelect as $aliasTable => $infoTable) {
					if (!empty($infoTable['champs'])) {
						if ($numTable > 0) { $text_SELECT .= ', '; }
						$numChamps = 0;
						foreach ($infoTable['champs'] as $champs => $aliasChamps) {
							if ($numChamps > 0) { $text_SELECT .= ', '; }
							if ($champs == '*') {
								$text_SELECT .= $aliasTable.'.'.$champs;
							} else {
								$text_SELECT .= $aliasTable.'.'.$champs.' AS "'.$aliasChamps.'"';
							}
							$numChamps++;
						}
						$numTable++;
					}
				}
			}
			$tabAggreg = isset($this->tabRequete['aggreg'])?$this->tabRequete['aggreg']:false;
			if (gettype($tabAggreg) == 'array') {
				foreach ($tabAggreg as $alias => $listeFunctions) {
					foreach ($listeFunctions as $aggreg => $listeOptions) {
						if ($numTable > 0) { $text_SELECT .= $listeOptions['separator']; }
						$text_SELECT .= $aggreg.'(';
						$numCol = 0;
						foreach ($listeOptions['cols'] as $col) {
							if ($numCol > 0) { $text_SELECT .= ', '; }
							$text_SELECT .= $listeOptions['aliasTable'].'.'.$col;
							$numCol++;
						}
						$text_SELECT .= ') AS "'.$alias.'"';
						
						
						$numTable++;
					}
				}
			}
			$requete .= $text_SELECT;
		}
		/*****************/ }
		
		{ /* Partie FROM */
		if (!empty($this->tabRequete['select'])) {
			// récupération des tables via la requête SELECT
			$tabSelect = $this->tabRequete['select'];
			if (gettype($tabSelect) == 'array') {
				$text_FROM = ' FROM ';
				$temp_tabJoin = array();
				foreach ($tabSelect as $aliasTable => $infoTable) {
					$temp = $this->clef_etrangere($infoTable['nomTable']);
					foreach ($temp as $num) {
						foreach ($tabSelect as $infoTable2) {
							if ($infoTable2['nomTable'] == $num['referenced_table_name']) {
								$temp_tabJoin[$aliasTable][] = $num;
								break;
							}
						}
					}
				}

				$temp_tabJointure = array();
				foreach ($temp_tabJoin as $alias => $tabl) {
					foreach ($tabl as $table) {
						foreach ($tabSelect as $infoTable) {
							if (!$infoTable['join']['ok'] && $infoTable['nomTable'] == $table['referenced_table_name']) {
								if (isset($infoTable['join'][$alias])) {
									$typeJoin = $infoTable['join'][$alias]['typeJoin'];
									$condJoin = $infoTable['join'][$alias]['condJoin'];
								} else {
									$typeJoin = 'INNER';
									$condJoin = '=';
								}

								$temp_tabJointure[$alias][] = array('a1' => $alias, 't1' => $table['table_name'], 'c1' => $table['column_name'], 'a2' => $infoTable['aliasTable'], 't2' => $table['referenced_table_name'], 'c2' => $table['referenced_column_name'], 'typeJoin' => $typeJoin, 'condJoin' => $condJoin);
								$tabSelect[$infoTable['aliasTable']]['join']['ok'] = true;
								break;
							}
						}
					}
				}

				foreach ($temp_tabJointure as $table => $ligne) {
					for ($j=0;$j<count($ligne);$j++) {
						foreach ($temp_tabJointure as $tabl => $lign) {
							for ($i=0;$i<count($lign);$i++) {
								/* ajout du 17/11/2013 */
								if (isset($ligne[$j]['a1']) && isset($lign[$i]['a2'])) {
									if ($ligne[$j]['a1'] == $lign[$i]['a2']) {
										$temp_tabJointure[$tabl][$i]['a2'] = $ligne;
										$temp_tabJointure[$tabl][$i]['a2']['aliasa2'] = $lign[$i]['a2'];
										unset($temp_tabJointure[$table][$j]);
										break;
									}
								}
							}
						}
					}
					if (count($temp_tabJointure[$table]) == 0) {
						unset($temp_tabJointure[$table]);
					}
				}

				$text_FROM .= $this->jointure($temp_tabJointure);

				$temp_tabJointure = $this->liste_table_jointure($temp_tabJointure);

				$numTable=0;
				foreach ($tabSelect as $aliasTable => $infoTable) {
					if (!in_array($aliasTable, $temp_tabJointure)) {
						if ($numTable > 0) { $text_FROM .= ', '; }
						// echo $infoTable['join']['ok'].' | ';
						if ($infoTable['join']['ok']===true) {
							$text_FROM .= $infoTable['nomTable'].' '.$infoTable['aliasTable'];
							$numTable++;
						} else {
							$text_FROM .= $infoTable['nomTable'].' '.$infoTable['aliasTable'];
						}
					}
				}
				/*
				$numTable = 0;
				foreach ($tabSelect as $aliasTable => $infoTable) {
					if (!in_array($aliasTable, $temp_tabJointure)) {
						if ($numTable > 0) { $text_FROM .= ', '; }
						// echo $infoTable['join']['ok'].' | ';
						if ($infoTable['join']['ok']===true) {
							$affiche = true;
							foreach ($temp_tabJointure as $table => $ligne) {
								for ($j=0;$j<count($ligne);$j++) {
									// ajout du 17/11/2013
									if (isset($ligne[$j]['t1']) && isset($infoTable['nomTable'])) {
										if ($ligne[$j]['t1'] == $infoTable['nomTable']) {
											$affiche = false;
										}
									}
								}
							}
							if ($affiche===true) { $text_FROM .= $infoTable['nomTable'].' '.$infoTable['aliasTable']; }
							$numTable++;
						} else {
							$text_FROM .= $infoTable['nomTable'].' '.$infoTable['aliasTable'];
						}
					}
				}
				*/
				$requete .= $text_FROM;
			}
		}
		/*****************/ }
		
		{ /* Partie INSERT */
		if (!empty($this->tabRequete['insert'])) {
			$text_INSERT = 'INSERT INTO ';
			$tabInsert = $this->tabRequete['insert'];
			foreach ($tabInsert as $table => $champs) {
				$text_INSERT .= $table.' (';
				$nbChamps = 0;
				foreach ($champs as $name => $key) {
					if ($nbChamps > 0) {$text_INSERT .= ', ';}
					$text_INSERT .= '`'.$name.'`';
					$nbChamps++;
				}
				$text_INSERT .= ') VALUES (';
				$nbChamps = 0;
				foreach ($champs as $name => $key) {
					if ($nbChamps > 0) {$text_INSERT .= ', ';}
					$text_INSERT .= ':'.$key['name'].' ';
					$nbChamps++;
				}
				$text_INSERT .= ')';
			}
			$requete .= $text_INSERT;
		}
		/*****************/ }
		
		{ /* Partie UPDATE */
		if (!empty($this->tabRequete['update'])) {
			$text_UPDATE = 'UPDATE ';
			$tabInsert = $this->tabRequete['update'];
			foreach ($tabInsert as $table => $champs) {
				$text_UPDATE .= $this->prefixe.$table.' AS '.$table.' SET ';
				$nbChamps = 0;
				foreach ($champs as $name => $key) {
					if ($nbChamps > 0) {$text_UPDATE .= ', ';}
					$text_UPDATE .= ' '.$name.' = :'.$key['name'].' ';
					$nbChamps++;
				}
			}
			$requete .= $text_UPDATE;
		}
		/*****************/ }
		
		{ /* Partie DELETE */
		if (!empty($this->tabRequete['delete'])) {
			$text_UPDATE = 'DELETE FROM '.$this->prefixe.$this->tabRequete['delete'];
			$requete .= $text_UPDATE;
		}
		/*****************/ }
		
		{ /* Partie WHERE */
		if (!empty($this->tabRequete['where'])) {
			$text_WHERE = ' WHERE ';
			$tabWhere = $this->tabRequete['where'];
			// var_dump($tabWhere);
			$i=0;
			foreach ($tabWhere as $groupe => $table) {
				$debutGroupe = true;
				foreach ($table as $liste) {
					if ($i > 0) { $text_WHERE .= $liste['conj']; }
					if ($debutGroupe) { $text_WHERE .= ' ( '; }
					$text_WHERE .= ' '.$liste['table'].'.'.$liste['col'].' '.$liste['operator'].' :'.$liste['name'].' ';
					$debutGroupe = false;
					$i++;
				}
				$text_WHERE .= ' ) ';
			}
			$requete .= $text_WHERE;
		}
		/****************/ }
		
		{ /* Partie GROUP BY */
		if (!empty($this->tabRequete['group'])) {
			$text_GROUP = ' GROUP BY '.implode(', ', $this->tabRequete['group']);
			$requete .= $text_GROUP;
		}
		/*******************/ }
		
		{ /* Partie ORDER BY */
		// array( aliasTable => array(col => sens), aliasTable => array(col => sens) )
		if (!empty($this->tabRequete['order'])) {
			$text_ORDER = ' ORDER BY ';
			$numTable = 0;
			$tabOrder = $this->tabRequete['order'];
			foreach ($tabOrder as $aliasTable => $infoTable) {
				foreach ($infoTable as $col => $sens) {
					if ($numTable > 0) { $text_ORDER .= ', '; }
					$text_ORDER .= $aliasTable.'.'.$col.' '.$sens;
					$numTable++;
				}
			}
			$requete .= $text_ORDER;
		}
		/*******************/ }
		
		{ /* Partie LIMIT */
		if (!empty($this->tabRequete['limit'])) {
			$text_LIMIT = ' LIMIT '.$this->tabRequete['limit']['debut'];
			if ($this->tabRequete['limit']['offset']) {
				$text_LIMIT .= ' OFFSET '.$this->tabRequete['limit']['offset'];
			}
			$requete .= $text_LIMIT;
		}
		/****************/ }
		
		{ /* Partie HAVING */
		$tabHaving = isset($this->tabRequete['having'])?$this->tabRequete['having']:false;
		if (gettype($tabHaving) == 'array') {
			$text_HAVING = ' HAVING ';
			$numTable = 0;
			foreach ($tabHaving as $alias => $listeFunctions) {
				$nbHaving = count($listeFunctions);
				for ($i=0;$i<$nbHaving;$i++) {
					$listeOptions = $listeFunctions[$i];
					if ($numTable > 0) { $text_HAVING .= ' '.$listeOptions['separator'].' '; }
					$text_HAVING .= $aggreg.'('.$listeOptions['col'].') '.$listeOptions['operator'].' '.$listeOptions['valeur'];
					$numTable++;
				}
			}
			$requete .= $text_HAVING;
		}
		/*****************/ }

		$this->requete = $requete;
	}

	function liste_table_jointure($tab) {
		$temp = array();
		foreach ($tab as $table => $ligne) {
			for ($i=0;$i<count($ligne);$i++) {
				if (isset($ligne[$i]['a2'])) {
					if (gettype($ligne[$i]['a2']) == 'array') {
						return array_merge($temp, $this->liste_table_jointure(array($ligne[$i]['a2']['aliasa2'] => $ligne[$i]['a2'])));
					} elseif (gettype($ligne[$i]['a2']) == 'string') {
						$temp[] = $ligne[$i]['a1'];
						$temp[] = $ligne[$i]['a2'];
					}
				}
			}
		}
		return array_unique($temp);
	}

	public function execute($direct = true) {
		$retour = false;
		if ($direct === true) {$this->build();}
		$sth = $this->dbh->prepare($this->requete);


		if ($sth->execute($this->tabNameReq)) {
			$retour['result'] = $sth->fetchAll(PDO::FETCH_ASSOC);
			$sth->closeCursor();
		} else {
			$retour = false;
			$retour['error'] = 'Un problème concernant l\'execution de cette requete a été détecté.'.$this->requete;
		}
		$this->resultat = $retour;

		return json_encode($retour);
	}
	
	public function buildAll($direct = true) {
		$retour = false;
		if ($direct) { $this->build(); }
		$requete = $this->requete;
		$count = 1;

		foreach($this->tabNameReq as $name => $value) {
			if (gettype($value) == 'boolean' ) {
				$requete = str_replace(':'.$name.' ', (($value)?'true ':'false '), $requete, $count);
			} else {
				$requete = str_replace(':'.$name.' ', '"'.$value.'" ', $requete, $count);
			}
		}
		
		$retour = $requete;
		
		return $retour;
	}
	
	private function jointure($temp_tabJointure) {
		$temp = '';
		foreach ($temp_tabJointure as $table => $ligne) {
			for ($i=0;$i<count($ligne);$i++) {
				if (isset($ligne[$i]['a2'])) {
					if (gettype($ligne[$i]['a2']) == 'array') {
						$temp = $this->jointure(array($ligne[$i]['a2']['aliasa2'] => $ligne[$i]['a2']));
						return $temp.' '.$ligne[$i]['typeJoin'].' JOIN '.$ligne[$i]['t1'].' '.$ligne[$i]['a1'].' ON '.$ligne[$i]['a1'].'.'.$ligne[$i]['c1'].' '.$ligne[$i]['condJoin'].' '.$ligne[$i]['a2']['aliasa2'].'.'.$ligne[$i]['c2'];
					} elseif (gettype($ligne[$i]['a2']) == 'string') {
						if ($i==0) {
							$temp .= $ligne[$i]['t1'].' '.$ligne[$i]['a1'];
						}
						$temp .= ' '.$ligne[$i]['typeJoin'].' JOIN '.$ligne[$i]['t2'].' '.$ligne[$i]['a2'].' ON '.$ligne[$i]['a1'].'.'.$ligne[$i]['c1'].' '.$ligne[$i]['condJoin'].' '.$ligne[$i]['a2'].'.'.$ligne[$i]['c2'];
					}
				}
			}
		}
		return $temp;
	}
	
	private function clef_etrangere($table) {
		$retour = false;
		$sth = $this->dbh->prepare('SELECT table_name, column_name, referenced_table_name, referenced_column_name FROM '.$this->information_schema.'KEY_COLUMN_USAGE WHERE referenced_table_name IS NOT NULL AND table_name = :table');
		$sth->bindParam(':table', $table);
		if ($sth->execute()) {
			$retour = $sth->fetchAll();
			$sth->closeCursor();
		} else {
			$retour['error'] = 'ERREUR : clef_etrangere - requete execute incorrect';
		}
		return $retour;
	}
	
	public function colonnes($table) {
		$retour = false;
		$sth = $this->dbh->prepare('SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, EXTRA, COLUMN_COMMENT FROM '.$this->information_schema.'COLUMNS WHERE TABLE_SCHEMA = :TABLE_SCHEMA AND table_name = :table;');
		$table = $this->prefixe.$table;
		$sth->bindParam(':table', $table);
		$sth->bindParam(':TABLE_SCHEMA', $this->TABLE_SCHEMA);
		if ($sth->execute()) {
			$retour = $sth->fetchAll();
			$sth->closeCursor();
		} else {
			$retour['error'] = 'ERREUR : colonnes - requete execute incorrect';
		}
		return $retour;
	}
	
	public function direct($requete, $param = false) {
		$this->requete = $requete;
		
		if ($param) {
			$nbParam = count($param);
			for ($i=0;$i<$nbParam;$i++) {
				$this->tabNameReq[$param[$i]['name']] = $param[$i]['value'];
			}
		}
	}
}

?>