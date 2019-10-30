<?php
$_controller = 'page';

$db_parametre = loadModel('parametre');
$_parametres = $db_parametre->getAll();
$_page = empty($_req) ? $db_page->getHome() : $db_page->getPage($_req[0]);
$_page_trouver = $db_page->getPageFromTemplate('trouver');
$_page_contact = $db_page->getPageFromTemplate('contact');
$_page_mention = $db_page->getPageFromTemplate('mention_legale');

if(!$_page) {
	include _DIR_CONTROLLERS.'404_controller.php';
	exit;
}


$_view = $_controller.'_'.$_page->template;

$_body_class = $_page->template;
if( $_page->template == 'search') {
    $_url = _ROOT_LANG . $_page->url;
    $_url_params = array();
    $page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    $_filter = array();

    //
    //filtres
    //

    $ids_types = array();
    foreach ($_types as $t) {
        $label = json_decode($t->label);
        $ids_types[clean_str(__lang($label))] = $t->id;
        $label_types[clean_str(__lang($label))] = clean_str($t->label);
    }
    if (!empty($_req[1]) && array_key_exists($_req[1], $ids_types)) {
        $_filter['label'] = $label_types[$_req[1]];
        $_filter['type'] = $ids_types[$_req[1]];
        $_url .= '/' . $_req[1];
    }
    $total = $db_annonce->getTotalAnnonces($_filter);
    $nb = $total < 9 ? 4 : 9;
    $_biens = $db_annonce->searchAnnonces($_GET['s'], $page);

    if (isAjax()) {
        $return = new stdClass();
        ob_start();
        include _DIR_VIEWS . 'bien_view.php';
        $return->content = ob_get_contents();
        ob_end_clean();
        $return->page = $_biens->page < $_biens->page_max ? $_biens->page + 1 : false;
        exit(json_encode($return));
    }
}

if( $_page->template == 'expertise' ) {
    $page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    $sold = $db_annonce->getAllAnnoncesVendus($page);
    if( isAjax() ) {
        $return = new stdClass();
        ob_start();
        include _DIR_VIEWS.'bien_vendus_view.php';
        $return->content = ob_get_contents();
        ob_end_clean();
        $return->page = $sold->page < $sold->page_max ? $sold->page + 1 : false;
        exit( json_encode($return) );
    }

    $_page_contact = $db_page->getPageFromTemplate('contact');
}

else if( $_page->template == 'acheter' ) {

	$_url = _ROOT_LANG . $_page->url;
	$_url_params = array();
	$page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    $immeuble = $db_page->getImmeublePage();
    $data_immeuble= json_decode($immeuble->data);
	$_filter = array();

	//
	//filtres
	//

    $ids_types = array();
    foreach($_types as $t) {
        $label = json_decode($t->label);
        $ids_types[clean_str(__lang($label))] = $t->id;
        $label_types[clean_str(__lang($label))] = clean_str($t->label);
    }
    if( !empty($_req[1]) && array_key_exists($_req[1], $ids_types) ) {
        $_filter['label'] = $label_types[$_req[1]];
    	$_filter['type'] = $ids_types[$_req[1]];
    	$_url .= '/' . $_req[1];
    }
    $total = $db_annonce->getTotalAnnonces($_filter);
    $nb = $total < 9 ? 4 : 9;
	$_biens = $db_annonce->getAnnonces($_filter, $page, $nb);

	if( isAjax() ) {
		$return = new stdClass();
		ob_start();
		include _DIR_VIEWS.'bien_view.php';
		$return->content = ob_get_contents();
		ob_end_clean();
		$return->page = $_biens->page < $_biens->page_max ? $_biens->page + 1 : false;
		exit( json_encode($return) );
	}


}
else if ($_page->template == 'normal'){
    $db_equipe= loadModel('equipe');
    $equipe = $db_equipe->getAllEquipiers();
}
else if ($_page->template == 'renovation'){

	$_url = _ROOT_LANG . $_page->url;
	$_url_params = array();
	$page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

	$_filter = array();

    $_biens = $db_renovation->getRenovations($_filter, $page);

    if( isAjax() ) {
		$return = new stdClass();
		ob_start();
		include _DIR_VIEWS.'renovation_list_view.php';
		$return->content = ob_get_contents();
		ob_end_clean();
		$return->page = $_biens->page < $_biens->page_max ? $_biens->page + 1 : false;
		exit( json_encode($return) );
	}

}

else if( $_page->template == 'louer' ) {

    $_url = _ROOT_LANG . $_page->url;
    $_url_params = array();
    $page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    $_filter = array(
        'islocation' => 1,
    );

    //
    //filtres
    //
    $ids_types = array();
    foreach ($_types as $t) {
        $label = json_decode($t->label);
        $ids_types[clean_str(__lang($label))] = $t->id;
    }
    if (!empty($_req[1]) && array_key_exists($_req[1], $ids_types)) {
        $_filter['type'] = $ids_types[$_req[1]];
        $_url .= '/' . $_req[1];
    }
    $total = $db_annonce->getTotalAnnonces($_filter);
    $nb = $total < 9 ? 4 : 9;
    $_biens = $db_annonce->getAnnonces($_filter, $page, $nb);

    if (isAjax()) {
        $return = new stdClass();
        ob_start();
        include _DIR_VIEWS . 'louer_view.php';
        $return->content = ob_get_contents();
        ob_end_clean();
        $return->page = $_biens->page < $_biens->page_max ? $_biens->page + 1 : false;
        exit(json_encode($return));
    }

    $_page_trouver = $db_page->getPageFromTemplate('trouver');

}

else if( $_page->template == 'mentio_legale' ) {

}



$_data = json_decode(decodeDirs($_page->data));

//attribution des metas
$_meta['title'] = __lang($_data->meta_titre);
$_meta['description'] = __lang($_data->meta_description);
$_meta['keywords'] = __lang($_data->meta_keywords);




include _DIR_LAYOUT.'layout.php';
