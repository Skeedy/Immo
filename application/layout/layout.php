<?php

//rendu ajax
if( isAjax() ) {
	include _DIR_VIEWS . $_view . '_view.php';
	exit;
}


include _DIR_LAYOUT.'head.php';

include _DIR_LAYOUT.'header.php';

include _DIR_VIEWS.$_view.'_view.php';

include _DIR_LAYOUT.'footer.php';
?>
