<?php
class Model_menu extends Model {

    public function getMenu($id) {
        $sql = $this->db->prepare('SELECT data
			FROM '.$this->menu.'
			WHERE id = ?;');
        $sql->execute(array($id));
        if($res = $sql->fetchColumn())
            return $this->parseMenu(json_decode($res));
        else
            return false;
    }


    public function parseMenu($_menu, $parent = '') {
        global $_current_user, $_wishlist;
        $menu = array();
        $i = 0;
        foreach($_menu as $v) {
            $m = new stdClass();
            $m->type = $v->type;
            if($m->type == 'page') {
                $sql = $this->db->prepare('SELECT url, template
					FROM '.$this->page.'
					WHERE id = ?;');
                $sql->execute(array($v->page));
                $t = $sql->fetch();
                $m->url = _ROOT_LANG.(!empty($t->home) ? '' : $t->url);
                $m->template = $t->template;
            }
            else if($m->type == 'page_static') {
                $sql = $this->db->prepare('SELECT url
					FROM '.$this->page_static.'
					WHERE id = ?;');
                $sql->execute(array($v->page_static));
                $t = $sql->fetch();
                $m->url = _ROOT_LANG.$t->url;
            }
            else if($m->type == 'categorie') {
                $sql = $this->db->prepare('SELECT url
					FROM '.$this->categorie.'
					WHERE id = ?;');
                $sql->execute(array($v->categorie));
                $t = $sql->fetch();
                $m->url = _ROOT_LANG.$t->url.(!empty($v->parametres) ? '?'.$v->parametres : '');
            }
            else if($m->type == 'combinaison') {
                $sql = $this->db->prepare('SELECT url
					FROM '.$this->combinaison.'
					WHERE id = ?;');
                $sql->execute(array($v->combinaison));
                $t = $sql->fetch();
                $m->url = _ROOT_LANG.$t->url;
            }
            else if($m->type == 'annonce') {
                $sql = $this->db->prepare('SELECT url
					FROM '.$this->annonce.'
					WHERE id = ?;');
                $sql->execute(array($v->annonce));
                $t = $sql->fetch();
                $m->url = _ROOT_LANG.$t->url;
            }
            else if($m->type == 'url') {
                $m->url = $v->url;
                $m->targetblank = isset($v->targetblank) ? true : false;
            }
            else if($m->type == 'anchor')
                $m->url = $v->url;
            else if($m->type == 'text')
                $m->url = '';
            if(empty($m->text))
                $m->text = $v->text;
            $i++;
            $menu[] = $m;
        }
        return $menu;
    }


    public function checkMenuSelected($v) {
        global $_req, $_force_menu;
        $active = false;
        if(preg_replace(array('/^'.preg_quote(_PROTOCOL.$_SERVER['SERVER_NAME'], '/').'/', '/\/$/'), '', $v->url) == preg_replace(array('/^'.preg_quote(_PROTOCOL.$_SERVER['SERVER_NAME'], '/').'/', '/\/$/'), '', _ROOT_LANG.implode('/', $_req)))
            $active = true;
        else if(($v->type == 'page' && !empty($_req[0]) && $v->url == _ROOT_LANG.$_req[0]) || ($v->type == 'page' && !empty($_req[0]) && $_req[0] == 'index' && $v->url == _ROOT_LANG))
            $active = true;
        else if( $v->type == 'page' && !empty($v->template) && !empty($_force_menu) && $_force_menu == $v->template )
        	$active = true;
        return $active;
    }


    public function checkMenuDevelopped($v) {
        $developped = false;
        if(!empty($v->sousmenu)) {
            $j = 0;
            while(!$developped && $j < count($v->sousmenu)) {
                if($this->checkMenuSelected($v->sousmenu[$j]))
                    $developped = true;
                $j++;
            }
        }
        return $developped;
    }


    public function hasSelection($menu) {
        foreach($menu as $v) {
            if($this->checkMenuSelected($v))
                return true;
        }
        return false;
    }


    public function printHeaderMenu($menu, $sousmenu = '', $developped = false, $collapse = false) {
        global $_lang;
        $return = '';
        $attr = array(
            'id' => '',
            'classes' => array()
        );
        if($this->hasSelection($menu))
            $attr['classes'][] = 'hasselection';
        $return .= '<ul'.(!empty($attr['id']) ? ' id="'.$attr['id'].'" ' : '').(!empty($attr['classes']) ? ' class="'.implode(' ', $attr['classes']).' navbar-nav"' : ' class="navbar-nav"').'>';
        $i = 0;
        foreach($menu as $v) {
            $v->selected = $this->checkMenuSelected($v);
            $v->developped = $this->checkMenuDevelopped($v);
            $return .= '<li'.($v->selected || $v->developped ? ' class="nav-link header-menu active"' : ' class= "header-menu nav-link"').'>';
            $return .= '<a '.(!empty($v->classes) ? 'class="'.$v->classes.'" ' : '').'href="'.$v->url.'"'.(!empty($v->targetblank) ? ' target="_blank"' : '').' data-url="'.$v->url.'">'.__lang($v->text).'</a>';
            if(!empty($v->sousmenu))
                $return .= $this->printMenu($v->sousmenu, $v->url, $v->developped ? true : false);
            $return .= '<span '.($v->selected || $v->developped ? ' class="active"' : 'class="nope"').'> </span></li> ';
        }
        $return .= '</ul>';
        return $return;
    }

    public function printFooterMenu($menu, $sousmenu = '', $developped = false, $collapse = false) {
        global $_lang;
        $return = '';
        $attr = array(
            'id' => '',
            'classes' => array()
        );
        $return .= '<div'.(!empty($attr['id']) ? ' id="'.$attr['id'].'" ' : '').' class="footer-menu">';
        $return .= '<div class="footer-col">';
        $i = 0;
        foreach($menu as $v) {
            $i++;
            $return .= '<div>';
            $return .= '<a class="shiny" href="'.$v->url.'"'.(!empty($v->targetblank) ? ' target="_blank"' : '').' data-url="'.$v->url.'">'.__lang($v->text).'</a>';
            $return .= '</div>';
            if( $i == ceil(count($menu) / 2) )
            	$return .= '</div><div class="footer-col">';
        }
        $return .= '</div>';
        $return .= '</div>';
        return $return;
    }

}
