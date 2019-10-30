<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="author" content="Benjamin Gouaud">
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo _TITLE; ?></title>
	<link rel="shortcut icon" href="<?php echo _ROOT_ADMIN; ?>img/favicon.png?v=0.6">
	<link rel="stylesheet" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>jquery-ui/css/jmetro/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="<?php echo _ROOT_ADMIN; ?>css/style.css?v=0.62" type="text/css">
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>jquery/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>jquery-ui/js/jquery.ui.datepicker-fr.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>jquery/jquery.placeholder.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/js/typeahead.bundle.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/js/confirmation.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>fancybox/jquery.fancybox.css" media="screen">
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>fancybox/helpers/jquery.fancybox-media.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/datepicker/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
	<link rel="stylesheet" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>bootstrap/datepicker/css/bootstrap-datepicker3.min.css" type="text/css">
	<script src="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filesuploader/SimpleAjaxUploader.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="">
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
	<script src="https://unpkg.com/leaflet-geosearch@latest/dist/bundle.min.js"></script>
</head>
<body<?php if(!empty($_body_class)) echo ' class="'.$_body_class.'"'; ?>>
