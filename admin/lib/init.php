<?php

//repertoires
define('_DIR_ADMIN', dirname(dirname(__FILE__)).'/');
define('_ROOT_ADMIN', _ROOT.str_replace(dirname(dirname(dirname(__FILE__))).'/', '', _DIR_ADMIN));

//autres
define('_NB_PAR_PAGE', 25);


if(count($_LANGS) > 1) {
	define('_ROOT_LANG', _ROOT._LANG_DEFAULT);
}
else {
	define('_ROOT_LANG', _ROOT);
}
