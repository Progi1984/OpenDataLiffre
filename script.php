<?php

$dirDeliberations = 'archives/deliberations/pdf/';
$dirDeliberations = 'archives/deliberations/json/';

/*
 * ###1 : Recuperation des fichiers
 */
$mainUrl = 'http://www.ville-liffre.fr/';
$arrayPage = array(
	'http://www.ville-liffre.fr/le-conseil-municipal.173.html',
	'http://www.ville-liffre.fr/deliberations-2013.html',
	'http://www.ville-liffre.fr/deliberations-2012.html',
	'http://www.ville-liffre.fr/deliberation_2011.html',
	'http://www.ville-liffre.fr/deliberations_2010.html'
);
foreach ($arrayPage as $page) {
	$contentPage = file_get_contents($page);
	$regexpLink = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	if (preg_match_all("/$regexpLink/siU", $contentPage, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			if (! is_array($match) && ! isset($match[2])) {
				continue;
			}
			$file = basename($match[2]);
			if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'pdf' && strpos($file, 'deliberation') !== false) {
				$file = str_replace('%20', '_', $file);
				$file = str_replace('-', '_', $file);
				$file = str_replace('__', '_', $file);
				if (! file_exists($dirDeliberations . $file)) {
					file_put_contents($dirDeliberations . $file, file_get_contents($mainUrl . $match[2]));
				}
			}
		}
	}
}

/*
 * ###2 : Vérification de la présence du JSON pour le PDF
 */