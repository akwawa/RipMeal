<?php

function list_or_add_calendrier($id=false, $date=false, $timestampJour=false, $typeCalendrier=false) {
	$requete = new Menu;
	$json = $requete->recup_calendrier($id, $date, $timestampJour, $typeCalendrier);
	$recup_calendrier = json_decode($json, true);
	if (!empty($recup_calendrier['result'])) {
		return $recup_calendrier['result'][0]['c.id'];
	} else {
		$requete->ajout_calendrier($id, $date, $timestampJour, $typeCalendrier);
		$json = $requete->recup_calendrier($id, $date, $timestampJour, $typeCalendrier);
		$recup_calendrier = json_decode($json, true);
		return $recup_calendrier['result'][0]['c.id'];
	}
}

function list_or_add_composant_menu($type, $id=false, $name, $fullname) {
	$requete = new Menu;
	$json = $requete->recup_composant_menu($type, $id=false, $name, $fullname);
	$recup = json_decode($json, true);
	if (!empty($recup['result'])) {
		return $recup['result'][0]['m.id'];
	} else {
		$requete->ajout_composant_menu($type, $id=false, $name, $fullname);
		$json = $requete->recup_composant_menu($type, $id=false, $name, $fullname);
		$recup = json_decode($json, true);
		return $recup['result'][0]['m.id'];
	}
}

function list_or_add_menu($id=false, $idEntree=false, $idViande=false, $idLegume=false, $idFromage=false, $idDessert=false, $supplement=false) {
	$requete = new Menu;
	$json = $requete->lister_menu($id, $idEntree, $idViande, $idLegume, $idFromage, $idDessert, $supplement);
	$recup = json_decode($json, true);
	if (!empty($recup['result'])) {
		return $recup['result'][0]['m.id'];
	} else {
		$requete->ajout_menu($id, $idEntree, $idViande, $idLegume, $idFromage, $idDessert, $supplement);
		$json = $requete->lister_menu($id, $idEntree, $idViande, $idLegume, $idFromage, $idDessert, $supplement);
		$recup = json_decode($json, true);
		return $recup['result'][0]['m.id'];
	}
}

function list_or_add_menu_regime($idMenu, $idRegime, $idCalendrier) {
	$requete = new Menu;
	$json = $requete->lister_menu_regime($idMenu, $idRegime, $idCalendrier);
	$recup = json_decode($json, true);
	if (empty($recup['result'])) {
		// echo $idMenu.' '.$idRegime.' '.$idCalendrier;
		$requete->ajout_menu_regime($idMenu, $idRegime, $idCalendrier);
	}
	return true;
}

if (!empty($_SESSION[$application]['idRank'])) {
	if (!file_exists(dirname(__FILE__).'/private/'.$menu.'.class.php')) {
		perror(11, 'Impossible de trouver la class "'.dirname(__FILE__).'/private/'.$menu.'.class.php"');
	} else {
		include_once(dirname(__FILE__).'/private/'.$menu.'.class.php');

		$retour['result'] = false;
		$nomRegime = (empty($_POST['nomRegime']))?false:$_POST['nomRegime'];
		$jour = (empty($_POST['jour']))?false:$_POST['jour'];
		$typeCalendrier = (empty($_POST['typeCalendrier']))?false:$_POST['typeCalendrier'];
		$entree = (empty($_POST['entree']))?false:$_POST['entree'];
		$viande = (empty($_POST['viande']))?false:$_POST['viande'];
		$legume = (empty($_POST['legume']))?false:$_POST['legume'];
		$fromage = (empty($_POST['fromage']))?false:$_POST['fromage'];
		$dessert = (empty($_POST['dessert']))?false:$_POST['dessert'];
		$timestampJour = (empty($_POST['timestampJour']))?false:$_POST['timestampJour'];
		
		$requete = new Menu;

		if ($nomRegime && $jour && $typeCalendrier && $entree && $viande && $legume && $fromage && $dessert) {
			$json = $requete->lister_regime(false, $nomRegime);
			$lister_regime = json_decode($json, true);
			if (!empty($lister_regime['result'])) {
				$idRegime = $lister_regime['result'][0]['r.id'];
				$tab_jour = array('LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI', 'DIMANCHE');
				if (in_array($jour, $tab_jour)) {
					$idCalendrier =list_or_add_calendrier(false, false, $timestampJour, $typeCalendrier);

					$idEntree = list_or_add_composant_menu('entree', false, $entree, $entree);
					$idViande = list_or_add_composant_menu('viande', false, $viande, $viande);
					$idLegume = list_or_add_composant_menu('legume', false, $legume, $legume);
					$idFromage = list_or_add_composant_menu('fromage', false, $fromage, $fromage);
					$idDessert = list_or_add_composant_menu('dessert', false, $dessert, $dessert);
					$supplement = '';
					$idMenu = list_or_add_menu(false, $idEntree, $idViande, $idLegume, $idFromage, $idDessert, $supplement);
					if (list_or_add_menu_regime($idMenu, $idRegime, $idCalendrier)===true) {
						// $retour['result'] = $idCalendrier;
						$retour['result'] = 'ok';
					} else {
						$retour['result'] = 'Erreur !';
					}
				} else {
					$retour['result'] = 'Jour introuvable';
				}
			} else {
				$retour['result'] = 'Régime inexistant';
			}
		} else {
			$retour['result'] = 'Régime incomplet';
		}

/*
							{ // menu_regime
								// idMenu, idRegime, idCalendrier
								$requete = new requete();
								$requete->select('menu_regime', 'e');
								$requete->where(array('e' => array('idRegime' => $idRegime, 'idCalendrier' => $idCalendrier)));
								$requete->grand_tableau = false;
								$requete->executer_requete();
								$liste = $requete->resultat;
								unset($requete);
								if ($liste) {
									$requete = new requete();
									$requete->delete('menu_regime', array('menu_regime' => array('idRegime' => $idRegime, 'idCalendrier' => $idCalendrier)));
									$requete->executer_requete();
									unset($requete);
								}
								$requete = new requete();
								$requete->insert('menu_regime', array('idMenu' => $idMenu, 'idRegime' => $idRegime, 'idCalendrier' => $idCalendrier));
								$requete->executer_requete();
								// unset($requete);
								$retour['resultat'] = true;
								// $retour['resultat'] = $requete->requete_complete();
							}
		*/
	
	}
	
	echo json_encode($retour);
}