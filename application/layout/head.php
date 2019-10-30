<!DOCTYPE html>
<html lang="<?php echo $_lang; ?>"<?php if(!empty($_html_class)) echo ' class="'.$_html_class.'"'; ?> prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo !empty($_meta['title']) ? $_meta['title'] : $_PARAMS['meta_title']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="<?php echo escHtml(!empty($_meta['description']) ? $_meta['description'] : $_PARAMS['meta_description'], true); ?>">
	<meta name="keywords" content="<?php echo escHtml(!empty($_meta['keywords']) ? $_meta['keywords'] : $_PARAMS['meta_keywords'], true); ?>">
	<meta name="robots" content="index, follow">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
	<link rel="sitemap" type="application/xml" title="Sitemap" href="<?php echo _ROOT; ?>sitemap.xml">
	<link rel="shortcut icon" type="image/png" href="<?php echo _ROOT._DIR_IMG; ?>favicon.png?<?php echo !empty(_DEBUG_MODE) ? time() : _VERSION; ?>">
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<meta property="fb:app_id" content="154854211756546">
	<meta property="og:locale" content="<?php echo $_LANGS[$_lang]['locale']; ?>">
	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php echo escHtml(!empty($_meta['title']) ? $_meta['title'] : $_PARAMS['meta_title'], true); ?>">
	<meta property="og:url" content="<?php echo _PROTOCOL.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>">
	<meta property="og:site_name" content="<?php echo _TITLE; ?>">
	<?php
	if(!empty($_og_image))
		echo '<meta property="og:image" content="'.$_og_image.'">';
	if(!empty($_og_description))
		echo '<meta property="og:description" content="'.$_og_description.'">';
	?>
	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="<?php echo escHtml(!empty($_meta['title']) ? $_meta['title'] : $_PARAMS['meta_title'], true); ?>">
	<?php
	print_loadCSS();
	?>
	<link rel="stylesheet" href="<?php echo _ROOT._DIR_CSS; ?>bundle.css?<?php echo !empty(_DEBUG_MODE) ? time() : _VERSION; ?>">
	<script type="text/javascript">
	var $_ROOT = "<?php echo _ROOT; ?>";
	var $_ROOT_LANG = "<?php echo _ROOT_LANG; ?>";
	function loadGA() {
		var s = document.createElement("script");
		s.src = "https://www.googletagmanager.com/gtag/js?id=UA-127422419-1";
		document.getElementsByTagName("head")[0].appendChild(s);
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-127422419-1');
	}
	function parseJSAtOnload() {
		<?php
		if( !empty($_COOKIE['_rgpd_ok']) ) {
			?>
			loadGA();
			<?php
		}
		?>
	}
	if (window.addEventListener)
		window.addEventListener("load", parseJSAtOnload, false);
	else if (window.attachEvent)
		window.attachEvent("onload", parseJSAtOnload);
	else
		window.onload = parseJSAtOnload;
	</script>

</head>
<body class="<?php if( !empty($_body_class) ) echo $_body_class; ?> <?php echo !$mobile_detect->isMobile() && !$mobile_detect->isTablet() ? 'notouch' : 'touch'; ?>">
