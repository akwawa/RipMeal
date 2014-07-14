<?php

// function dump($array=false, $affichage=true) {
	// if ($array===false) { return false; }
	// $retour = '<pre>'.print_r($array, true).'</pre>';
	// if ($affichage===true) { echo $retour; }
	// return $retour;
// }

if (function_exists('week_dates')!==true) {
	function week_dates($week, $year) {
		$week_dates = array();
		$first_day = mktime(12, 0, 0, 1, 1, $year);
		$first_week = date("W", $first_day);
		if (intval($first_week) > 1) {
			$first_day = strtotime("+1 week", $first_day);
		}
		$timestamp = strtotime("+$week week", $first_day);

		$what_day = date("w", $timestamp);
		if ($what_day==0) {
			$timestamp = strtotime("-6 days", $timestamp);
		} elseif ($what_day > 1) {
			$what_day--;
			$timestamp = strtotime("-$what_day days", $timestamp);
		}
		// $week_dates[2] = date("Y-m-d",strtotime("+1 day",$timestamp));
		return($timestamp);
	}
}

if (function_exists('perror')!==true) {
	function perror($code, $message=false) {
		return array('alert' => array($code => $message));
	}
}

if (function_exists('human_filesize')!==true) {
	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
}

if (function_exists('isJson')!==true) {
	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}

if (function_exists('implode_r')!==true) {
	function implode_r($glue, $array=array()) {
		if (is_array($glue) && empty($array)) $array=$glue; $glue='';

		$return = '';
		foreach ($array as $piece) {
			if (is_array($piece))
				$return .= $glue.implode_r($glue, $piece);
			else
				$return .= $glue.$piece;
		}
		
		return $return;
	}
}

if (function_exists('construire_page')!==true) {
	function construire_page($page, $application, $dev=false) {
		$retour = '';
		// var_dump($page);
		
		if (!empty($page['doctype'])) $retour .= $page['doctype'];
		
		if (!empty($page['header'])) {
			$retour .= '<head>';
			if (!empty($page['header']['meta']))
				foreach ($page['header']['meta'] as $meta)
					$retour .= '<meta '.$meta.' />';
			if (!empty($page['header']['css']))
				foreach ($page['header']['css'] as $media => $css) {
					if ($media === 'direct') {
						if (is_array($css)) $css=implode($css);
						$retour .= '<style>'.$css.'</style>';
					} else {
						if (is_numeric($media)) $media="all";
						if ($dev===false && file_exists($css.'.min.css')) $css .= '.min.css';
						$retour .= '<link rel="stylesheet" type="text/css" href="'.$css.'" media="'.$media.'" />';
					}
				}
			$script='';
			if (!empty($page['header']['js']))
				foreach ($page['header']['js'] as $js) {
					if (gettype($js)==='array') {
						$script .= '<script type="text/javascript">'.implode($js).'</script>';
					} else {
						if ($dev===false && file_exists($js.'.min.js')) $js .= '.min.js';
						$script .= '<script type="text/javascript" src="'.$js.'"></script>';
					}
				}

			if (!empty($page['header']['title'])) $retour .= '<title>'.$page['header']['title'].'</title>';
			if (!empty($page['header']['favicon'])) $retour .= '<link rel="icon" href="'.$page['header']['favicon'].'" />';
			$retour .= '</head>';
		}
		if (!empty($page['body'])) {
			$retour .= '<body><div id="main">';
			if (!empty($page['body']['menu_config'])) $retour .= '<div id="menu_config">'.$page['body']['menu_config'].'</div>';
			if (!empty($page['body']['header'])) $retour .= '<div id="header">'.$page['body']['header'].'</div>';
			if (!empty($_SESSION[$application]['idRank']) && !empty($page['body']['menu'])) {
				$retour .= '<div id="menu"><nav><ul>';
				foreach ($page['body']['menu'] as $url => $menu)
					$retour .= '<li><a href="'.$url.'">'.$menu.'</a></li>';
				$retour .= '</ul></nav></div>';
			}
			if (!empty($page['body']['message'])) {
				$retour .= '<div id="contenuError">';
				foreach ($page['body']['message'] as $type => $message) {
					foreach ($message as $code => $msg) {
						$retour .= '<div class="'.$type.'">Erreur N° '.$code.' - '.$msg.'</div>';
					}
				}
				$retour .= '</div>';
			}
			if (!empty($page['body']['contenu'])) $retour .= '<div id="contenu">'.$page['body']['contenu'].'</div>';
			if (!empty($page['body']['footer'])) $retour .= '<div id="footer">'.$page['body']['footer'].'</div>';
			$retour .= '</div>'.$script.'</body>';
		}
		$retour .= '</html>';
		return $retour;
	}
}

?>