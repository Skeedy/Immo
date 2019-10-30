<?php

require_once 'lib/init.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

$db_page = loadModel('page');
$map = $db_page->getSiteMap();

foreach($map as $v)
	echo '<url><loc>'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$v.'</loc></url>';

$db_annonce = loadModel('annonce');
$map = $db_annonce->getAnnoncesSitemap();

foreach($map as $v) {
	$titre = json_decode($v->titre);
	$url = _PROTOCOL . $_SERVER['HTTP_HOST'] . _ROOT . $v->id . '-' . clean_str(__lang($titre));
	echo '<url><loc>'.$url.'</loc></url>';
}

echo '</urlset>';
