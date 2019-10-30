<?php
$_controller = 'biens';

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


//
//upload video MP4
//
if(!empty($_POST['deletetmpvideo']) && isAjax()) {
	if(file_exists($_POST['deletetmpvideo'])) {
		$t = pathinfo($_POST['deletetmpvideo']);
		$upload_dir = _DIR_VIDEO_TMP.$_current_user->id.'/';
		if($upload_dir == $t['dirname'].'/')
			unlink($_POST['deletetmpvideo']);
	}
	exit;
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

if(isset($_GET['uploadvideo'])) {
	require_once _DIR_LIB.'filesuploader/uploader.class.php';
	umask(0);
	$upload_dir = _DIR_VIDEO_TMP.$_current_user->id.'/';
	if(!is_dir($upload_dir))
		mkdir($upload_dir, 0775, true);
	$valid_extensions = array('mp4', 'mpg4');
	$uploader = new FileUpload('uploadfile');
	$ext = $uploader->getExtension();
	$uploader->newFileName = 'tmp_'.microtime().'.'.$ext;
	$result = $uploader->handleUpload($upload_dir, $valid_extensions);
	if(!$result) {
		exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
	}
	$new_filename = clean_str(rand_str(10).microtime()).'.mp4';
	copy($uploader->getSavedFile(), $upload_dir.$new_filename);
	unlink($uploader->getSavedFile());
	exit(json_encode(array('success' => true, 'file' => $upload_dir.$new_filename)));
}




$db_annonce = loadModel('annonce');



//searchannonces_ids
if(isset($_GET['searchannonces_ids']) && isAjax()) {
	echo json_encode($db_annonce->searchAnnonceIds($_GET['searchannonces_ids']));
	exit;
}

//searchannonces_refs
if(isset($_GET['searchannonces_refs']) && isAjax()) {
	echo json_encode($db_annonce->searchAnnonceRefs($_GET['searchannonces_refs']));
	exit;
}

//searchannoncesvilles
if(isset($_GET['searchannoncesvilles']) && isAjax()) {
	echo json_encode($db_annonce->searchAnnoncesVille($_GET['searchannoncesvilles']));
	exit;
}

//searchtitres
if(isset($_GET['searchtitres']) && isAjax()) {
	echo json_encode($db_annonce->searchTitres($_GET['searchtitres']));
	exit;
}

//searchprix
if(isset($_GET['searchprix']) && isAjax()) {
	echo json_encode($db_annonce->searchAnnoncesPrix($_GET['searchprix']));
	exit;
}

//searchtags
if(isset($_GET['searchtags']) && isset($_GET['type']) && isset($_GET['langue']) && isAjax()) {
	echo json_encode($db_annonce->searchTags($_GET['searchtags'], $_GET['type'], $_GET['langue']));
	exit;
}

//searchtagvaleurs
if(isset($_GET['searchtagvaleurs']) && isset($_GET['type']) && isset($_GET['langue']) && isset($_GET['tag']) && isAjax()) {
	echo json_encode($db_annonce->searchTagValeurs($_GET['searchtagvaleurs'], $_GET['type'], $_GET['langue'], $_GET['tag']));
	exit;
}

//searchville
if(isset($_GET['searchville']) && isAjax()) {
	echo json_encode($db_annonce->searchVille($_GET['searchville']));
	exit;
}

//getCategories
if(isset($_POST['getCategories']) && isAjax()) {
	echo json_encode($db_annonce->getCategories($_POST['getCategories']));
	exit;
}

//getTypes
if(isset($_POST['getTypes']) && isAjax()) {
	echo json_encode($db_annonce->getTypes($_POST['getTypes']));
	exit;
}

//getVilles
if(isset($_POST['getVilles']) && isAjax()) {
	echo json_encode($db_annonce->getVilles($_POST['getVilles']));
	exit;
}

//getequipe
if(isset($_POST['getEquipes']) && isAjax()) {
    echo json_encode($db_annonce->getEquipe($_POST['getVilles']));
    exit;
}

$db_equipe = loadModel('equipe');
$equipiers = $db_equipe->getAllEquipes();


if($_current_user->role == 'root' || $_current_user->role == 'administrateur') {

	//
	// post forms
	//

	//ajout d'une annonce
	if(isset($_POST['action_add'])) {
		$_POST = associative2array($_POST);
		$result = $db_annonce->addAnnonce($_POST);
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

		//verrou
		$verrou = $db_annonce->getVerrou($_POST['action_modify']);
		if(empty($verrou) || $verrou->token == $_POST['token']) {
			$result = $db_annonce->modifyAnnonce($_POST);
			if($result) {
				$db_annonce->deleteVerrou($_POST['action_modify']);
				$_SESSION['_ALERTS'] = $_ALERTS;
				redirect($_SERVER['REQUEST_URI']);
			}
		}
		else
			throwAlert('danger', 'Erreur', '<p>Ce bien est verrouillé, il est en cours de modification par '.$verrou->prenom.' '.$verrou->nom.'</p>');
		
	}

	//suppression d'une annonce
	if(!empty($_POST['action_delete'])) {
		$result = $db_annonce->deleteAnnonce($_POST['action_delete']);
		if($result) {
			$_SESSION['_ALERTS'] = $_ALERTS;
			redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view=list');
		}
	}

	//switchannonce
	if(!empty($_POST['switchannonce']) && !empty($_POST['annonce'])) {
		$result = $db_annonce->switchAnnonce($_POST['annonce']);
		if($result)
			echo json_encode($result);
		else
			echo json_encode(null);
		exit;
	}

	//switchfeatured
	if(!empty($_POST['switchfeatured']) && !empty($_POST['annonce'])) {
		$result = $db_annonce->featureAnnonce($_POST['annonce']);
		if($result)
			echo json_encode($result);
		else
			echo json_encode(null);
		exit;
	}

}



//liste des types
$_types = $db_annonce->getAllTypes();



if($_current_user->role == 'root' || $_current_user->role == 'administrateur') {

	//ch_position
	if( isAjax()
		&& !empty($_POST['ch_position'])
		&& ( $_POST['ch_position'] == 'up' || $_POST['ch_position'] == 'down' )
		&& !empty($_POST['id'])
		&& !empty($_GET['view'])
		&& ( $_GET['view'] == 'list' || $_GET['view'] == 'vendus' )
	) {
		if( !($_annonce = $db_annonce->getAnnonce($_POST['id'])) ) {
			echo json_encode(array('error' => 'Annonce ID invalide'));
			exit;
		}
		if( $_GET['view'] == 'list' ) {
			$_annonces_sort = !empty($_GET['annoncessortby']) ? $_GET['annoncessortby'] : 'ordre ASC';
			$result = $db_annonce->changePositionAnnonces($_POST['id'], $_POST['ch_position'], $_annonces_sort, false);
			if(!empty($result)) {
				echo json_encode(array('error' => $result));
				exit;
			}
			$_annonces = $db_annonce->getAnnonces($_annonces_sort);
			ob_start();
			include _DIR_VIEWS.'biens_list_view.php';
			$html = ob_get_contents();
			ob_end_clean();
			echo json_encode(array('html' => $html));
			exit;
		}
		else if( $_GET['view'] == 'vendus' ) {
			$_annoncesvendus_sort = !empty($_GET['annoncesvendussortby']) ? $_GET['annoncesvendussortby'] : 'ordre ASC';
			$result = $db_annonce->changePositionAnnonces($_POST['id'], $_POST['ch_position'], $_annoncesvendus_sort, true);
			if(!empty($result)) {
				echo json_encode(array('error' => $result));
				exit;
			}
			$_annoncesvendus = $db_annonce->getAnnonces($_annoncesvendus_sort, true, 'p2');
			ob_start();
			include _DIR_VIEWS.'biens_vendus_view.php';
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

//liste des annonces
if( !empty($_GET['view']) && $_GET['view'] == 'list' ) {
	$_annonces_sort = !empty($_GET['annoncessortby']) ? $_GET['annoncessortby'] : 'ordre ASC';
	$_annonces = $db_annonce->getAnnonces($_annonces_sort);
	$_view = $_controller.'_list';
}

//liste des biens vendus
else if( !empty($_GET['view']) && $_GET['view'] == 'vendus' ) {
	$_annonces_sort = !empty($_GET['annoncessortby']) ? $_GET['annoncessortby'] : 'ordre ASC';
	$_annonces = $db_annonce->getAnnonces($_annonces_sort, true);
	$_view = $_controller.'_vendus';
}

//bien
else if( !empty($_GET['view']) && $_GET['view'] == 'bien' && !empty($_GET['bien']) ) {
	if($_annonce = $db_annonce->getAnnonce($_GET['bien'])) {
		$_data = json_decode($_annonce->data);
		$_view = $_controller.'_bien';

		//verrrou
		if(isAjax() && !empty($_POST['deleteverrou'])) {
			$db_annonce->deleteVerrou($_annonce->id, !empty($_POST['token']) ? $_POST['token'] : null);
			exit;
		}

		if(isAjax() && !empty($_POST['checkverrou'])) {
			$verrou = $db_annonce->getVerrou($_annonce->id);
			if($verrou->token == $_POST['checkverrou'])
				echo json_encode(array('success' => ''));
			else
				echo json_encode(array('success' => 'false', 'prenom' => $verrou->prenom, 'nom' => $verrou->nom));
			exit;
		}

		$token = !empty($_POST['token']) ? $_POST['token'] : clean_str(rand_str(10).microtime());
		$verrou = $db_annonce->getVerrou($_annonce->id);
		if(!empty($verrou) && (empty($_POST['token']) || $verrou->token != $_POST['token']))
			$locked = true;
		else
			$db_annonce->addVerrou($_annonce->id, $token);
	}
	else {
		throwAlert('danger', 'Erreur', 'Ce bien n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}

//ajouter
else if( !empty($_GET['view']) && $_GET['view'] == 'add' ) {
	if( !empty($_GET['model']) && $_annonce = $db_annonce->getAnnonce($_GET['model']) ) {
		$_data = json_decode($_annonce->data);
		$_annonce->images = '[]';
		$_annonce->id = null;
	}
	$_view = $_controller.'_add';
}


//suppression annonce
if( !empty($_GET['action']) && $_GET['action'] == 'delete_bien' && !empty($_GET['bien']) ) {
	if($_annonce = $db_annonce->getAnnonce($_GET['bien'])) {
		throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer le bien "'.$_annonce->ref.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_annonce->id.'">Supprimer</button> <a class="btn btn-default" href="'.(!empty($_GET['cancel_url']) ? $_GET['cancel_url'] : _ROOT_ADMIN.'?controller='.$_controller.'&view=annonces').'">Annuler</a></p></form>');
	}
	else {
		throwAlert('danger', 'Erreur', 'Ce bien n\'existe pas.');
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
