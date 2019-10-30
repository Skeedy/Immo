<?php

function printContentButtons($name, $onlyblocks = false, $classes = '') {
    ?>
    <div class="form-group">
        <div class="btn-group dropup<?php if(!empty($classes)) echo ' '.escHtml($classes); ?>">
            <button type="button" class="btn btn-primary dropdown-toggle nofocus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ajouter <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <?php
                if(!$onlyblocks) {
                    ?>
                    <li><a><strong>Sections</strong></a></li>
                    <li><a href="#" class="addsection" data-pattern="#pattern_row1" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">1 colonne</a></li>
                    <li><a href="#" class="addsection" data-pattern="#pattern_row2" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">2 colonnes</a></li>
                    <li><a href="#" class="addsection" data-pattern="#pattern_row3" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">3 colonnes</a></li>
                    <li><a href="#" class="addsection" data-pattern="#pattern_row4" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">4 colonnes</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a><strong>Blocs</strong></a></li>
                    <?php
                }
                ?>
                <li><a href="#" class="addsection" data-pattern="#pattern_text" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Contenu</a></li>
                <li><a href="#" class="addsection" data-pattern="#pattern_texthidden" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Contenu caché</a></li>
                <li><a href="#" class="addsection" data-pattern="#pattern_diaporama" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Diaporama</a></li>
                <li><a href="#" class="addsection" data-pattern="#pattern_gallery" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Galerie</a></li>
                <li><a href="#" class="addsection" data-pattern="#pattern_space" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Espace vertical</a></li>
                <li><a href="#" class="addsection" data-pattern="#pattern_map" data-name="<?php echo $name; ?>" data-count="> .dissmissable-block">Carte</a></li>
            </ul>
        </div>
    </div>
    <?php
}


function parseContent($content, $name, $onlyblocks = false) {
    global $_LANGS;
    if(!empty($content)) {
        foreach($content as $v) {
            $fields = rand();
            $iterator = 'iteration'.$fields;

            if(!empty($v->row1) || !empty($v->row2) || !empty($v->row3) || !empty($v->row4)) {
                if(!empty($v->row1))
                    $nb = 1;
                else if(!empty($v->row2))
                    $nb = 2;
                else if(!empty($v->row3))
                    $nb = 3;
                else
                    $nb = 4;
                $el = 'row'.$nb;
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Section <?php echo $nb; ?> colonne<?php if($nb > 1) echo 's'; ?></div>
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_container">Container</label>
                                    <select class="form-control" id="<?php echo $prefix; ?>_container" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][container]">
                                        <?php
                                        foreach(array('container', 'container-fluid') as $vv)
                                            echo '<option value="'.$vv.'"'.(!empty($valeur->container) && $valeur->container == $vv ? ' selected' : '').'>'.$vv.'</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_classes">Classes</label>
                                    <input type="text" class="form-control" id="<?php echo $prefix; ?>_classes" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][classes]" value="<?php if(!empty($valeur->classes)) echo escHtml($valeur->classes); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_valign">Alignement vertical</label>
                                    <select class="form-control" id="<?php echo $prefix; ?>_valign" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][align]">
                                        <option value="start" <?php echo (!empty($valeur->align) && $valeur->align == 'start' ? ' selected' : '')?>>Haut</option>
                                        <option value="center" <?php echo (!empty($valeur->align) && $valeur->align == 'center'? ' selected' : '')?>>Centre</option>
                                        <option value="end" <?php echo (!empty($valeur->align) && $valeur->align == 'end' ? ' selected' : '')?>>Bas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            for($i = 0; $i < $nb; $i++) {
                                $col = 'col'.$i;
                                ?>
                                <div class="col-sm-<?php echo 12 / $nb; ?>">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="<?php echo $prefix; ?>_<?php echo $col; ?>_cols">Largeur</label>
                                                <select class="form-control" id="<?php echo $prefix; ?>_<?php echo $col; ?>_cols" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][<?php echo $col; ?>][cols]">
                                                    <option value="">Défaut (<?php echo 12 / $nb; ?>/12)</option>
                                                    <?php
                                                    for($cols = 1; $cols < 13; $cols++)
                                                        echo '<option value="'.$cols.'"'.(!empty($valeur->{$col}->cols) && $valeur->{$col}->cols == $cols ? ' selected' : '').'>'.$cols.'/12</option>';
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="<?php echo $prefix; ?>_<?php echo $col; ?>_offset">Décalage</label>
                                                <select class="form-control" id="<?php echo $prefix; ?>_<?php echo $col; ?>_offset" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][<?php echo $col; ?>][offset]">
                                                    <option value="">Défaut (0/11)</option>
                                                    <?php
                                                    for($cols = 1; $cols < 12; $cols++)
                                                        echo '<option value="'.$cols.'"'.(!empty($valeur->{$col}->offset) && $valeur->{$col}->offset == $cols ? ' selected' : '').'>'.$cols.'/11</option>';
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="list-sortable-handle-dissmissable-interchangeable">
                                        <?php
                                        if(!empty($valeur->{$col}->content))
                                            parseContent($valeur->{$col}->content, $name.'['.$iterator.']['.$el.']['.$col.'][content]');
                                        ?>
                                    </div>
                                    <?php
                                    printContentButtons($name.'['.$iterator.']['.$el.']['.$col.'][content]', $onlyblocks, 'btn-group-sm');
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->text)) {
                $el = 'text';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Bloc contenu</div>
                    <div class="content">
                        <div class="block_to_parse" data-type="<?php echo $el; ?>">
                            <input type="hidden" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>]" value="<?php echo escHtml(json_encode($valeur)); ?>">
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->texthidden)) {
                $el = 'texthidden';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Bloc contenu caché</div>
                    <div class="content">
                        <div class="block_to_parse" data-type="<?php echo $el; ?>">
                            <input type="hidden" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>]" value="<?php echo escHtml(json_encode($valeur)); ?>">
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->gallery)) {
                $el = 'gallery';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Galerie</div>
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_height">Nombre / ligne</label>
                                    <select class="form-control" id="<?php echo $prefix; ?>_nb" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][nb]">
                                        <?php
                                        foreach(array(2, 3, 4, 6, 12) as $w)
                                            echo '<option value="'.$w.'"'.($valeur->nb == $w ? ' selected' : '').'>'.$w.'</option>';
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="checkbox">
                                        <label><input type="checkbox" id="<?php echo $prefix; ?>_fancybox" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][fancybox]" value="1"<?php if(!empty($valeur->fancybox)) echo ' checked'; ?>><span>Fancybox</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-sortable-handle-dissmissable all-directions clearfix">
                            <?php
                            $j = 0;
                            foreach($valeur->images as $w) {
                                $iterator2 = 'iteration'.$j;
                                ?>
                                <div class="dissmissable-block dissmissable-block-thumb">
                                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                                    <div class="block_title">&nbsp;</div>
                                    <div class="content">
                                        <div class="block_to_parse" data-type="<?php echo $el; ?>_element">
                                            <input type="hidden" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][images][<?php echo $iterator2; ?>]" value="<?php echo escHtml(json_encode($w)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $j++;
                            }
                            ?>
                        </div>
                        <div class="form-group form-group-sm" style="clear:both">
                            <a class="btn btn-sm btn-primary addsection nofocus" data-pattern="#pattern_gallery_element" data-name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][images]" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter une image</a>
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->diaporama)) {
                $el = 'diaporama';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Diaporama</div>
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_nb">Nombre / slide</label>
                                    <input type="text" class="form-control" id="<?php echo $prefix; ?>_nb" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][nb]" value="<?php if(!empty($valeur->nb)) echo escHtml($valeur->nb); ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_height">Hauteur (fluide si vide)</label>
                                    <input type="text" class="form-control" id="<?php echo $prefix; ?>_height" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][height]" value="<?php if(!empty($valeur->height)) echo escHtml($valeur->height); ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="<?php echo $prefix; ?>_time">Temps (en ms, défaut 5000)</label>
                                    <input type="text" class="form-control" id="<?php echo $prefix; ?>_time" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][time]" value="<?php if(!empty($valeur->time)) echo escHtml($valeur->time); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label><input type="checkbox" id="<?php echo $prefix; ?>_fancybox" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][fancybox]" value="1"<?php if(!empty($valeur->fancybox)) echo ' checked'; ?>><span>Fancybox</span></label>
                            </div>
                            <div class="checkbox-inline">
                                <label><input type="checkbox" id="<?php echo $prefix; ?>_nav" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][nav]" value="1"<?php if(!empty($valeur->nav)) echo ' checked'; ?>><span>Navigation</span></label>
                            </div>
                            <div class="checkbox-inline">
                                <label><input type="checkbox" id="<?php echo $prefix; ?>_dots" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][dots]" value="1"<?php if(!empty($valeur->dots)) echo ' checked'; ?>><span>Puces</span></label>
                            </div>
                        </div>
                        <div class="list-sortable-handle-dissmissable all-directions clearfix">
                            <?php
                            $j = 0;
                            foreach($v->{$el}->images as $w) {
                                $iterator2 = 'iteration'.$j;
                                ?>
                                <div class="dissmissable-block dissmissable-block-thumb">
                                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                                    <div class="block_title">&nbsp;</div>
                                    <div class="content">
                                        <div class="block_to_parse" data-type="<?php echo $el; ?>_element">
                                            <input type="hidden" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][images][<?php echo $iterator2; ?>]" value="<?php echo escHtml(json_encode($w)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $j++;
                            }
                            ?>
                        </div>
                        <div class="form-group form-group-sm" style="clear:both">
                            <a class="btn btn-sm btn-primary addsection nofocus" data-pattern="#pattern_diaporama_element" data-name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>][images]" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter un slide</a>
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->space)) {
                $el = 'space';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Espace vertical</div>
                    <div class="content">
                        <div class="form-inline">
                            <div class="form-group form-group-sm">
                                <label for="<?php echo $prefix; ?>_height">Hauteur</label> &nbsp;
                                <select class="form-control" id="<?php echo $prefix; ?>_height" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>]">
                                    <?php
                                    foreach(array(20, 30, 40, 50, 60, 70, 80, 90, 100) as $w)
                                        echo '<option value="'.$w.'"'.($valeur == $w ? ' selected' : '').'>'.$w.' px</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

            }
            else if(!empty($v->map)) {
                $el = 'map';
                $prefix = $el.'_'.$fields;
                $valeur = $v->{$el};
                ?>
                <div class="dissmissable-block">
                    <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                    <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                    <div class="block_title">Carte</div>
                    <div class="content">
                        <div class="block_to_parse" data-type="<?php echo $el; ?>">
                            <input type="hidden" name="<?php echo $name; ?>[<?php echo $iterator; ?>][<?php echo $el; ?>]" value="<?php echo escHtml(json_encode($valeur)); ?>">
                        </div>
                    </div>
                </div>
                <?php

            }

        }
    }

}



function printPatterns($onlyblocks = false) {
    global $_LANGS;


    for($nb = 1; $nb <= 4; $nb++) {
        $el = 'row'.$nb;
        $prefix = $el.'_{{tid}}';
        ?>
        <div id="pattern_<?php echo $el; ?>" class="hidden">
            <div class="dissmissable-block">
                <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
                <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
                <div class="block_title">Section <?php echo $nb; ?> colonne<?php if($nb > 1) echo 's'; ?></div>
                <div class="content">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="<?php echo $prefix; ?>_container">Container</label>
                                <select class="form-control" id="<?php echo $prefix; ?>_container" name="{{name}}[{{tid}}][<?php echo $el; ?>][container]">
                                    <?php
                                    foreach(array('container', 'container-fluid') as $vv)
                                        echo '<option value="'.$vv.'">'.$vv.'</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="<?php echo $prefix; ?>_classes">Classes</label>
                                <input type="text" class="form-control" id="<?php echo $prefix; ?>_classes" name="{{name}}[{{tid}}][<?php echo $el; ?>][classes]">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        for($i = 0; $i < $nb; $i++) {
                            $col = 'col'.$i;
                            ?>
                            <div class="col-sm-<?php echo 12 / $nb; ?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="<?php echo $prefix; ?>_<?php echo $col; ?>_cols">Largeur</label>
                                            <select class="form-control" id="<?php echo $prefix; ?>_<?php echo $col; ?>_cols" name="{{name}}[{{tid}}][<?php echo $el; ?>][<?php echo $col; ?>][cols]">
                                                <option value="">Défaut (<?php echo 12 / $nb; ?>/12)</option>
                                                <?php
                                                for($cols = 1; $cols < 13; $cols++)
                                                    echo '<option value="'.$cols.'">'.$cols.'/12</option>';
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="<?php echo $prefix; ?>_<?php echo $col; ?>_offset">Décalage</label>
                                            <select class="form-control" id="<?php echo $prefix; ?>_<?php echo $col; ?>_offset" name="{{name}}[{{tid}}][<?php echo $el; ?>][<?php echo $col; ?>][offset]">
                                                <option value="">Défaut (0/11)</option>
                                                <?php
                                                for($cols = 1; $cols < 12; $cols++)
                                                    echo '<option value="'.$cols.'">'.$cols.'/11</option>';
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-sortable-handle-dissmissable-interchangeable"></div>
                                <?php
                                printContentButtons('{{name}}[{{tid}}]['.$el.']['.$col.'][content]', $onlyblocks, 'btn-group-sm');
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    $el = 'text';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Bloc contenu</div>
            <div class="content">
                <div class="block_to_parse" data-type="<?php echo $el; ?>">
                    <input type="hidden" name="{{name}}[{{tid}}][<?php echo $el; ?>]">
                </div>
            </div>
        </div>
    </div>
    <?php
    $prefix = 'modal_pattern_'.$el;
    ?>
    <div id="<?php echo $prefix; ?>" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Bloc contenu</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_classes">Classes</label>
                            <input type="text" class="form-control" id="<?php echo $prefix; ?>_classes" name="classes">
                        </div>
                        <?php
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_text_<?php echo $l; ?>">Contenu <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <textarea class="form-control editor" id="<?php echo $prefix; ?>_text_<?php echo $l; ?>" data-container="#<?php echo $prefix; ?>" rows="4" name="<?php echo $l; ?>"></textarea>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php


    $el = 'texthidden';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Bloc contenu caché</div>
            <div class="content">
                <div class="block_to_parse" data-type="<?php echo $el; ?>">
                    <input type="hidden" name="{{name}}[{{tid}}][<?php echo $el; ?>]">
                </div>
            </div>
        </div>
    </div>
    <?php
    $prefix = 'modal_pattern_'.$el;
    ?>
    <div id="<?php echo $prefix; ?>" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Bloc contenu caché</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_classes">Classes</label>
                            <input type="text" class="form-control" id="<?php echo $prefix; ?>_classes" name="classes">
                        </div>
                        <?php
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_btntext_<?php echo $l; ?>">Texte bouton <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <input type="text" class="form-control" id="<?php echo $prefix; ?>_btntext_<?php echo $l; ?>" name="btntext[<?php echo $l; ?>]">
                            </div>
                            <?php
                        }
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_text_<?php echo $l; ?>">Contenu <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <textarea class="form-control editor" id="<?php echo $prefix; ?>_text_<?php echo $l; ?>" data-container="#<?php echo $prefix; ?>" rows="4" name="text[<?php echo $l; ?>]"></textarea>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php


    $el = 'gallery';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Galerie</div>
            <div class="content">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_height">Nombre / ligne</label>
                            <select class="form-control" id="<?php echo $prefix; ?>_nb" name="{{name}}[{{tid}}][<?php echo $el; ?>][nb]">
                                <?php
                                foreach(array(2, 3, 4, 6, 12) as $w)
                                    echo '<option value="'.$w.'">'.$w.'</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="checkbox">
                                <label><input type="checkbox" id="<?php echo $prefix; ?>_fancybox" name="{{name}}[{{tid}}][<?php echo $el; ?>][fancybox]" value="1"><span>Fancybox</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-sortable-handle-dissmissable all-directions clearfix"></div>
                <div class="form-group form-group-sm" style="clear:both">
                    <a class="btn btn-sm btn-primary addsection nofocus" data-pattern="#pattern_gallery_element" data-name="{{name}}[{{tid}}][<?php echo $el; ?>][images]" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter une image</a>
                </div>
            </div>
        </div>
    </div>
    <?php


    $el = 'gallery_element';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block dissmissable-block-thumb">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">&nbsp;</div>
            <div class="content">
                <div class="block_to_parse" data-type="<?php echo $el; ?>">
                    <input type="hidden" name="{{name}}[{{tid}}]">
                </div>
            </div>
        </div>
    </div>
    <?php
    $prefix = 'modal_pattern_'.$el;
    ?>
    <div id="<?php echo $prefix; ?>" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Galerie élément</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Image</label><br>
                            <input type="hidden" id="<?php echo $prefix; ?>_image" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), false, 'image');">
                            <a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filemanager/filemanager/dialog.php?type=1&field_id=<?php echo $prefix; ?>_image"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
                            <div class="row images-list list-sortable"></div>
                        </div>
                        <?php
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_legend_<?php echo $l; ?>">Légende <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <input type="text" class="form-control" id="<?php echo $prefix; ?>_legend_<?php echo $l; ?>" name="legend[<?php echo $l; ?>]">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php


    $el = 'diaporama';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Diaporama</div>
            <div class="content">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_nb">Nombre / slide</label>
                            <input type="text" class="form-control" id="<?php echo $prefix; ?>_nb" name="{{name}}[{{tid}}][<?php echo $el; ?>][nb]">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_height">Hauteur (fluide si vide)</label>
                            <input type="text" class="form-control" id="<?php echo $prefix; ?>_height" name="{{name}}[{{tid}}][<?php echo $el; ?>][height]">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_time">Temps (en ms, défaut 5000)</label>
                            <input type="text" class="form-control" id="<?php echo $prefix; ?>_time" name="{{name}}[{{tid}}][<?php echo $el; ?>][time]">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox-inline">
                        <label><input type="checkbox" id="<?php echo $prefix; ?>_fancybox" name="{{name}}[{{tid}}][<?php echo $el; ?>][fancybox]" value="1"><span>Fancybox</span></label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" id="<?php echo $prefix; ?>_nav" name="{{name}}[{{tid}}][<?php echo $el; ?>][nav]" value="1"><span>Navigation</span></label>
                    </div>
                    <div class="checkbox-inline">
                        <label><input type="checkbox" id="<?php echo $prefix; ?>_dots" name="{{name}}[{{tid}}][<?php echo $el; ?>][dots]" value="1"><span>Puces</span></label>
                    </div>
                </div>
                <div class="list-sortable-handle-dissmissable all-directions clearfix"></div>
                <div class="form-group form-group-sm" style="clear:both">
                    <a class="btn btn-sm btn-primary addsection nofocus" data-pattern="#pattern_diaporama_element" data-name="{{name}}[{{tid}}][<?php echo $el; ?>][images]" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter un slide</a>
                </div>
            </div>
        </div>
    </div>
    <?php


    $el = 'diaporama_element';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block dissmissable-block-thumb">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">&nbsp;</div>
            <div class="content">
                <div class="block_to_parse" data-type="<?php echo $el; ?>">
                    <input type="hidden" name="{{name}}[{{tid}}]">
                </div>
            </div>
        </div>
    </div>
    <?php
    $prefix = 'modal_pattern_'.$el;
    ?>
    <div id="<?php echo $prefix; ?>" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Diaporama élément</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Image</label><br>
                            <input type="hidden" id="<?php echo $prefix; ?>_image" onchange="insertImage($(this), $(this).parent('.form-group').find('.images-list'), false, 'image');">
                            <a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filemanager/filemanager/dialog.php?type=1&field_id=<?php echo $prefix; ?>_image"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
                            <div class="row images-list list-sortable"></div>
                        </div>
                        <?php
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_legend_<?php echo $l; ?>">Légende <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <input type="text" class="form-control" id="<?php echo $prefix; ?>_legend_<?php echo $l; ?>" name="legend[<?php echo $l; ?>]">
                            </div>
                            <?php
                        }
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_content_<?php echo $l; ?>">Contenu <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <textarea class="form-control editor" id="<?php echo $prefix; ?>_content_<?php echo $l; ?>" data-container="#<?php echo $prefix; ?>" name="content[<?php echo $l; ?>]" rows="4"></textarea>
                            </div>
                            <?php
                        }
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="<?php echo $prefix; ?>_url_<?php echo $l; ?>">URL <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <input type="text" class="form-control" id="<?php echo $prefix; ?>_url_<?php echo $l; ?>" name="url[<?php echo $l; ?>]">
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="blank" value="1"><span>Ouvrir dans un nouvel onglet</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php


    $el = 'space';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Espace vertical</div>
            <div class="content">
                <div class="form-inline">
                    <div class="form-group form-group-sm">
                        <label for="<?php echo $prefix; ?>_height">Hauteur</label> &nbsp;
                        <select class="form-control" id="<?php echo $prefix; ?>_height" name="{{name}}[{{tid}}][<?php echo $el; ?>]">
                            <?php
                            foreach(array(20, 30, 40, 50, 60, 70, 80, 90, 100) as $w)
                                echo '<option value="'.$w.'">'.$w.' px</option>';
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php


    $el = 'map';
    $prefix = $el.'_{{tid}}';
    ?>
    <div id="pattern_<?php echo $el; ?>" class="hidden">
        <div class="dissmissable-block">
            <button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
            <button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button>
            <div class="block_title">Carte</div>
            <div class="content">
                <div class="block_to_parse" data-type="<?php echo $el; ?>">
                    <input type="hidden" name="{{name}}[{{tid}}][<?php echo $el; ?>]">
                </div>
            </div>
        </div>
    </div>
    <?php
    $prefix = 'modal_pattern_'.$el;
    ?>
    <div id="<?php echo $prefix; ?>" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Carte</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div id="gmap-constructor" class="map_constructor" data-prefix="<?php echo $prefix; ?>_"></div>
                        </div>
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_adresse">Adresse à localiser</label>
                            <div class="input-group">
                                <input type="text" id="<?php echo $prefix; ?>_adresse" name="adresse" class="form-control">
                                <span class="input-group-btn">
									<button type="button" class="btn btn-primary" id="<?php echo $prefix; ?>_btn_localize"><span class="glyphicon glyphicon-map-marker"></span> Localiser</button>
								</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="<?php echo $prefix; ?>_pointer">Afficher le marqueur</label>
                            <div>
                                <button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
                                <input type="hidden" id="<?php echo $prefix; ?>_pointer" name="pointer" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Coordonnées</label>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" id="<?php echo $prefix; ?>_latitude" name="latitude" class="form-control" readonly>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="<?php echo $prefix; ?>_longitude" name="longitude" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php

}
