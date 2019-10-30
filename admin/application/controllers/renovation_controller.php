<?php
$_controller = 'renovation';

if($_current_user->role != 'root' && $_current_user->role != 'administrateur') {
    $_view = 'forbidden';
    include _DIR_LAYOUT.'layout.php';
    exit;
}


//
//upload
//
if(!empty($_POST['deletetmpimg']) && isAjax()) {
    if(file_exists($_POST['deletetmpimg'])) {
        $t = pathinfo($_POST['deletetmpimg']);
        $upload_dir = _DIR_IMG_TMP.$_current_user->id.'/';
        if($upload_dir == $t['dirname'].'/')
            unlink($_POST['deletetmpimg']);
    }
    exit;
}

if(isset($_GET['uploadimageprogress'])) {
    if(isset($_REQUEST['progresskey']))
        $status = apc_fetch('upload_'.$_REQUEST['progresskey']);
    else
        exit(json_encode(array('success' => false)));
    $pct = 0;
    $size = 0;
    if(is_array($status)) {
        if(array_key_exists('total', $status) && array_key_exists('current', $status)) {
            if($status['total'] > 0) {
                $pct = round(($status['current'] / $status['total']) * 100);
                $size = round($status['total'] / 1024);
            }
        }
    }
    exit(json_encode(array('success' => true, 'pct' => $pct, 'size' => $size)));
}

if(isset($_GET['uploadimage'])) {
    require_once _DIR_LIB.'filesuploader/uploader.class.php';
    require_once _DIR_LIB.'phpthumb/ThumbLib.inc.php';
    umask(0);
    $upload_dir = _DIR_IMG_TMP.$_current_user->id.'/';
    if(!is_dir($upload_dir))
        mkdir($upload_dir, 0775, true);
    $valid_extensions = array('gif', 'png', 'jpeg', 'jpg');
    $uploader = new FileUpload('uploadfile');
    $ext = $uploader->getExtension();
    $uploader->newFileName = 'tmp_'.microtime().'.'.$ext;
    $result = $uploader->handleUpload($upload_dir, $valid_extensions);
    if(!$result) {
        exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));
    }
    $new_filename = clean_str(rand_str(10).microtime()).'.jpg';
    $img = PhpThumbFactory::create($uploader->getSavedFile());
    $img->resize(_IMG_MAX_WIDTH, _IMG_MAX_HEIGHT);
    $img->save($upload_dir.$new_filename, 'jpg');
    unlink($uploader->getSavedFile());
    exit(json_encode(array('success' => true, 'file' => $upload_dir.$new_filename)));
}


if(isset($_GET['uploadvideoprogress'])) {
    if(isset($_REQUEST['progresskey']))
        $status = apc_fetch('upload_'.$_REQUEST['progresskey']);
    else
        exit(json_encode(array('success' => false)));
    $pct = 0;
    $size = 0;
    if(is_array($status)) {
        if(array_key_exists('total', $status) && array_key_exists('current', $status)) {
            if($status['total'] > 0) {
                $pct = round(($status['current'] / $status['total']) * 100);
                $size = round($status['total'] / 1024);
            }
        }
    }
    exit(json_encode(array('success' => true, 'pct' => $pct, 'size' => $size)));
}

$db_annonce = loadModel('annonce');
$db_renovation = loadModel('renovation');

//searchrenovations_ids
if(isset($_GET['searchrenovations_ids']) && isAjax()) {
    echo json_encode($db_renovation->searchRenovationIds($_GET['searchrenovations_ids']));
    exit;
}

//searchrenovations_refs
if(isset($_GET['searchrenovations_refs']) && isAjax()) {
    echo json_encode($db_renovation->searchRenovationRefs($_GET['searchrenovations_refs']));
    exit;
}

//searchrenovationsvilles
if(isset($_GET['searchrenovationsvilles']) && isAjax()) {
    echo json_encode($db_renovation->searchRenovationsVille($_GET['searchrenovationsvilles']));
    exit;
}

//searchtitres
if(isset($_GET['searchtitres']) && isAjax()) {
    echo json_encode($db_renovation->searchTitres($_GET['searchtitres']));
    exit;
}

//searchville
if(isset($_GET['searchville']) && isAjax()) {
    echo json_encode($db_annonce->searchVille($_GET['searchville']));
    exit;
}



//getVilles
if(isset($_POST['getVilles']) && isAjax()) {
    echo json_encode($db_annonce->getVilles($_POST['getVilles']));
    exit;
}


if($_current_user->role == 'root' || $_current_user->role == 'administrateur') {

    //
    // post forms
    //

    //ajout d'une renovation
    if(isset($_POST['action_add'])) {
        $_POST = associative2array($_POST);
        $result = $db_renovation->addRenovation($_POST);
        if($result) {
            $_SESSION['_ALERTS'] = $_ALERTS;
            redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view=list');
        }
        else
            $_tab_active = 'add';
    }
    //modification d'une annonce
    if(isset($_POST['action_modify'])) {
        $_POST = associative2array($_POST);

        if(empty($verrou) || $verrou->token == $_POST['token']) {
            $result = $db_renovation->modifyRenovation($_POST);
            if($result) {
               $_SESSION['_ALERTS'] = $_ALERTS;
               redirect($_SERVER['REQUEST_URI']);
            }
        }
        else
            throwAlert('danger', 'Erreur', '<p>Cette renovation est verrouillé, il est en cours de modification par '.$verrou->prenom.' '.$verrou->nom.'</p>');

    }

    //delete
	if(!empty($_POST['action_delete'])) {
	    $result = $db_renovation->deleteRenovation($_POST['action_delete']);
	    if($result) {
	        $_SESSION['_ALERTS'] = $_ALERTS;
	        redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view=list');
	    }
	}




    //switchrenovation
    if(!empty($_POST['switchrenovation']) && !empty($_POST['renovation'])) {
        $result = $db_renovation->switchRenovation($_POST['renovation']);
        if($result)
            echo json_encode($result);
        else
            echo json_encode(null);
        exit;
    }

    //switchfeatured
    if(!empty($_POST['switchfeatured']) && !empty($_POST['renovation'])) {
        $result = $db_renovation->featureRenovation($_POST['renovation']);
        if($result)
            echo json_encode($result);
        else
            echo json_encode(null);
        exit;
    }

}

if($_current_user->role == 'root' || $_current_user->role == 'administrateur') {

	//ch_position
	if( isAjax()
		&& !empty($_POST['ch_position'])
		&& ( $_POST['ch_position'] == 'up' || $_POST['ch_position'] == 'down' )
		&& !empty($_POST['id'])
		&& !empty($_GET['view'])
		&& ( $_GET['view'] == 'list' || $_GET['view'] == 'vendus' )
	) {
		if( !($_annonce = $db_renovation->getRenovation($_POST['id'])) ) {
			echo json_encode(array('error' => 'Annonce ID invalide'));
			exit;
		}
		if( $_GET['view'] == 'list' ) {
			$_renovation_sort = !empty($_GET['renovationssortby']) ? $_GET['renovationssortby'] : 'ordre ASC';
			$result = $db_renovation->changePositionRenovations($_POST['id'], $_POST['ch_position'], $_renovation_sort, false);
			if(!empty($result)) {
				echo json_encode(array('error' => $result));
				exit;
			}
			$_renovation = $db_renovation->getRenovations($_renovation_sort);
			ob_start();
			include _DIR_VIEWS.'renovation_list_view.php';
			$html = ob_get_contents();
			ob_end_clean();
			echo json_encode(array('html' => $html));
			exit;
		}
	}
	
}



//vue par défaut
$_view_default = 'list';
if( empty($_GET['view']) )
    redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// sélection des vues
//

//liste des rénovations
if(!empty($_GET['view']) && $_GET['view'] == 'list') {
    $_renovation_sort = !empty($_GET['renovationssortby']) ? $_GET['renovationssortby'] : 'ordre ASC';
    $_renovation = $db_renovation->getRenovations($_renovation_sort);
    $_view = $_controller.'_list';
}


//ajouter
else if( !empty($_GET['view']) && $_GET['view'] == 'add' ) {
    if( !empty($_GET['model']) && $_renovation = $db_renovation->getRenovation($_GET['model']) ) {
        $_data = json_decode($_renovation->data);
        $_renovation->images = '[]';
        $_renovation->id = null;
    }
    $_view = $_controller.'_add';
}
//modify
else if( !empty($_GET['view']) && $_GET['view'] == 'renovation' && !empty($_GET['renovation']) ) {
    if($_renovation = $db_renovation->getRenovation($_GET['renovation'])) {
        $_data = json_decode($_renovation->data);
        $_view = $_controller.'_renovation';
        if(!empty($verrou) && (empty($_POST['token']) || $verrou->token != $_POST['token']))
            $locked = true;
    }
    else {
        throwAlert('danger', 'Erreur', 'Cette renovation n\'existe pas.');
        $_SESSION['_ALERTS'] = $_ALERTS;
        redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
    }
}

//suppression annonce
if( !empty($_GET['action']) && $_GET['action'] == 'delete_renovation' && !empty($_GET['renovation']) ) {
	if($_annonce = $db_renovation->getRenovation($_GET['renovation'])) {
		throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer la rénovation "'.$_annonce->ref.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_annonce->id.'">Supprimer</button> <a class="btn btn-default" href="'.(!empty($_GET['cancel_url']) ? $_GET['cancel_url'] : _ROOT_ADMIN.'?controller='.$_controller.'&view=list').'">Annuler</a></p></form>');
	}
	else {
		throwAlert('danger', 'Erreur', 'Cette renovation n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}





if(empty($_view))
    redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// rendu
//

include _DIR_LAYOUT.'layout.php';
