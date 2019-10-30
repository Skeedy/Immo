<?php
require_once 'lib/phpthumb/ThumbLib.inc.php';

$thumb = PhpThumbFactory::create($_GET['filename']);

list($w, $h) = getimagesize($_GET['filename']);
$ratio = $w/$h;


//suppression du canal alpha
if(isset($_GET['alpha']) && $_GET['alpha'] == 0)
	$thumb->setOptions(array('preserveAlpha' => false));

//si with xor height
if((isset($_GET['width']) && is_numeric($_GET['width'])) xor (isset($_GET['height']) && is_numeric($_GET['height']))) {
	if(isset($_GET['width']))
		$thumb->resize($_GET['width'], round($_GET['width']/$ratio));
	else
		$thumb->resize(round($_GET['height']*$ratio), $_GET['height']);
}

//si width and height
if(isset($_GET['width']) && isset($_GET['height']) && is_numeric($_GET['width']) && is_numeric($_GET['height'])) {	
	if(isset($_GET['crop']) && $_GET['crop'] == 1)
		$thumb->adaptiveResize($_GET['width'], $_GET['height']);
	else
		$thumb->resize($_GET['width'], $_GET['height']);
}

$thumb->show();
exit;
?>
