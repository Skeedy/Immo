<?php
$_controller = 'equipe';

$db_equipe = loadModel('equipe');

//vue par défaut
$_view_default = 'list';
if(empty($_GET['view']))
    redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// post forms
//

//ajout d'un équiper
if(isset($_POST['action_add'])) {
    $result = $db_equipe->addEquipe($_POST);
    if($result) {
        $_SESSION['_ALERTS'] = $_ALERTS;
        redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
    }
}

//modification d'un équiper
if(!empty($_POST['action_modify'])) {
    $result = $db_equipe->modifyEquipe($_POST);
    if($result) {
        $_SESSION['_ALERTS'] = $_ALERTS;
        redirect($_SERVER['REQUEST_URI']);
    }
}

//suppression d'un équiper
if(!empty($_POST['action_delete'])) {
    $result = $db_equipe->deleteEquipe($_POST['action_delete']);
    if($result) {
        $_SESSION['_ALERTS'] = $_ALERTS;
        redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
    }
}


//
// sélection des vues
//

//liste des équipers
if(!empty($_GET['view']) && $_GET['view'] == 'list') {
    $_equipe_sort = !empty($_GET['equipierssortby']) ? $_GET['equipierssortby'] : 'nom ASC';
    $_equipe = $db_equipe->getAllEquipes($_equipe_sort);
    $_view = $_controller.'_list';
}

//equipier
else if(!empty($_GET['view']) && $_GET['view'] == 'equipier' && !empty($_GET['id'])) {
    if($_equipe = $db_equipe->getEquipe($_GET['id'])) {
        $_view = $_controller.'_'.'equipier';
    }
    else {
        throwAlert('danger', 'Erreur', 'Cet équipier n\'existe pas.');
        $_SESSION['_ALERTS'] = $_ALERTS;
        redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
    }
}

//ajouter
if(!empty($_GET['view']) && $_GET['view'] == 'add') {
    $_view = $_controller.'_add';
}

//suppression équipier
else if(!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {
    if($_equipier = $db_equipe->getEquipe($_GET['id'])) {
        throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer l\'équipier "'.$_equipier->prenom.' '.$_equipier->nom.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_equipier->id.'">Supprimer</button> <a class="btn btn-default" href="'._ROOT_ADMIN.'?controller='.$_controller.'">Annuler</a></p></form>');
    }
    else {
        throwAlert('danger', 'Erreur', 'Cet équipier n\'existe pas.');
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
