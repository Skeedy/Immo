<?php
require_once _DIR_VIEWS.'content_parser.php';


if (!empty($_data->normal))
    parseContent($_data->normal);

?>
<div class="container types">
    <ul class="d-flex justify-content-center flex-wrap">
        <li class="text-uppercase">
            <a href="<?php echo  _ROOT.$_page->url?>" <?php if( empty($_filter['type'])) echo ' class="active"'; ?>>tous</a>
        </li>
        <?php

        foreach ($_types as $t) {

            $label = json_decode($t->label);
            if (__lang($label) == 'Maison' || __lang($label) == 'Appartement') {
                ?>
                <li>

                    <a href="<?php echo _ROOT . $_page->url . '/' . clean_str(__lang($label)); ?>"<?php if (!empty($_filter['type']) && $_filter['type'] == $t->id) echo ' class="active"'; ?>>
                        <?php echo __lang($label) ?>
                    </a>
                </li>
                <?php
            }
        }
        ?>

    </ul>
</div>
<div class="container">
    <div id="container-biens" class="row">
        <?php

        include _DIR_VIEWS.'louer_view.php';

        ?>
    </div>
</div>
<?php
if( $_biens->page_max > 1) {
    ?>
    <div class="text-center mt-1">
        <button type="button" id="btn_page_biens_vendus" data-href="<?php echo $_url; ?>?page=" data-page="<?php echo $_biens->page + 1; ?>">+</button>
    </div>
    <?php
}
?>


<div class="text-center mt-5 mb-4">
	<a class="link_arrow" href="https://www.gotobordeaux.com">
		<span class="arrow"></span>vous êtes à la recherche<br>d'une location meublée ?
	</a>
	<div class="content tiret"></div>
</div>