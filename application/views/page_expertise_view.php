<?php
$test = true;
require_once _DIR_VIEWS.'content_parser.php';


if (!empty($_data->normal))
    parseContent($_data->normal);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<div><a class="link_arrow" href="<?php echo _ROOT_LANG . $_page_contact->url;?>"> <span class="arrow"></span>Estimer votre bien<br>&agrave; sa juste valeur </a></div>
		</div>
	</div>
</div>
<div class="vertical-space60"></div>
<div class="container container-article">
	<div class="content text-center titre">
		<p><strong>VOIR NOS BIENS VENDUS</strong></p>
	</div>
	<div class="content tiret"></div>
</div>
<div class="vertical-space40"></div>
<div class="container">
	<div id="container-biens" class="row">

        <?php

        include _DIR_VIEWS.'bien_vendus_view.php';

        ?>
	</div>
    <?php
    if( $sold->page_max > 1) {
        ?>
		<div class="text-center mt-1">
			<button type="button" id="btn_page_biens_vendus" data-href="<?php echo _ROOT_LANG . $_page->url; ?>?page=" data-page="<?php echo $sold->page + 1; ?>">+</button>
		</div>
        <?php
    }
    ?>
</div>
