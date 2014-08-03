<?php
if (empty($_SESSION)) { session_start(); }

use PFBC\Form, PFBC\Element, PFBC\Validation;

$erreur = array();
$page = array();

$fic_base='private/base.php';
if (file_exists($fic_base)) include($fic_base); else $erreur[] = 'Impossible de charger le fichier "'.$fic_base.'"';

$fic_fonctions='private/fonctions.php';
if (file_exists($fic_fonctions)) require($fic_fonctions); else $erreur[] = 'Impossible de charger le fichier "'.$fic_fonctions.'"';

setlocale(LC_TIME, 'fr_FR.utf8','fra');

$page['doctype'] = '<!DOCTYPE html><!--[if lte IE 7]><html class="no-js ie67 ie678" lang="fr"><![endif]--><!--[if IE 8]><html class="no-js ie8 ie678" lang="fr"><![endif]--><!--[if IE 9]><html class="no-js ie9" lang="fr"><![endif]--><!--[if gt IE 9]><!--><html class="no-js" lang="fr"><!--<![endif]-->';
$page['header']['meta'][] = 'charset="UTF-8"';
$page['header']['meta'][] = 'name="viewport" content="initial-scale=1.0"';
$page['header']['meta'][] = 'name="description" content="RipMeal - Portage de livraison à domicile"';
$page['header']['css'][] = 'css/main.css';
$page['header']['css'][] = 'css/perso.css';
$page['header']['css']['print'] = 'css/print.css';
$page['header']['js'][] = 'js/main.js';
$page['header']['title'] = $application.' - '.$message_par_defaut;
$page['header']['favicon'] = 'img/favicon.ico';

/*** JQuery pour form ***/
$page['header']['css'][] = 'css/jquery.noty.css';
$page['header']['css'][] = 'css/jquery.dataTables.css';
$page['header']['css'][] = 'css/jquery.dataTables.tableTools.min.css';
$page['header']['css'][] = 'css/jquery.dataTables.colVis.min.css';
$page['header']['css'][] = 'css/bootstrap-combined.min.css';

$page['header']['js'][] = 'js/jquery.min.js';
$page['header']['js'][] = 'js/jquery.dataTables.js';
// $page['header']['js'][] = 'js/ColReorderWithResize.js'; // H.S.
$page['header']['js'][] = 'js/jquery.dataTables.tableTools.js';
$page['header']['js'][] = 'js/jquery.dataTables.colVis.min.js';
$page['header']['js'][] = 'js/jquery.dataTables.columnFilter.js';
$page['header']['js'][] = 'js/jquery.populate.pack.js';
$page['header']['js'][] = 'js/jquery.noty.packaged.min.js';
$page['header']['js'][] = 'js/jquery.cookie.min.js';
/************************/
$page['body'] = array('header', 'menu', 'contenu', 'footer');
$page['body']['header'] = '<h1>'.$header.'</h1>';

$page['body']['menu'] = array($racine_url => 'accueil', 'client' => 'Client', 'tournee' => 'Tournée', 'menu' => 'Menu', 'regime' => 'Régime', 'parametre' => 'Paramètre', 'compte' => 'Compte', 'sauvegarde' => 'sauvegarde', 'login-logout' => 'Se déconnecter');
$page['body']['contenu']='';
$menu=(empty($_GET['menu']))?$menu_defaut:$_GET['menu'];
$categorie=(empty($_GET['categorie']))?'index':$_GET['categorie'];
if (empty($_SESSION[$application]['idRank'])) {$menu='login';$categorie='index';}
if (!is_dir('module/'.$menu)) {
	header('HTTP/1.0 404 Not Found');
	$erreur[]='Erreur 404 : Page non trouvée. La page qui s\'affiche est la page d\'accueil du site.';
	$menu=$menu_defaut;
	$page['body']['contenu'] .= '<div class="important">La page demandée n\'existe pas</div>';
} else {
	$menu=($menu==$racine_url)?$menu_defaut:$menu;
	if (file_exists('module/'.$menu.'/'.$categorie.'.php')) include('module/'.$menu.'/'.$categorie.'.php');
}

header('Content-language: fr-FR');
header('Content-Type: text/html; charset=utf-8');
echo construire_page($page, $application, $developpement);
?>