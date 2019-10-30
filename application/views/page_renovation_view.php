<?php
$title= 'renover';
require_once _DIR_VIEWS.'content_parser.php';

if (!empty($_data->normal))
    parseContent($_data->normal);
?>
<div class="vertical-space80"></div>
<div class="container">
    <div id="container-biens" class="row">
        <?php

        include _DIR_VIEWS.'renovation_list_view.php';

        ?>

    </div>
</div>
<?php
if( $_biens->page_max > 1) {
    ?>
    <div class="text-center mt-1">
        <button type="button" id="btn_page_biens_vendus" data-href="<?php echo _ROOT_LANG . $_page->url; ?>?page=" data-page="<?php echo $_biens->page + 1; ?>">+</button>
    </div>
    <?php
}
?>