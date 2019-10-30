<div class="container types">
    <ul class="d-flex justify-content-center flex-wrap">
        <?php
        if(empty($_annonce->date_vente)){
            ?>
            <li class="text-uppercase">
                <a href="<?php echo _ROOT.$_page->url?>">tous</a>
            </li>
            <?php
            foreach ($_types as $t) {
                $label = json_decode($t->label);
                if ($_annonce->isLocation) {
                    if (__lang($label) == 'Maison' || __lang($label) == 'Appartement') {
                        ?>
                        <li>
                            <a href="<?php echo _ROOT . $_page->url . '/' . clean_str(__lang($label)); ?>"<?php if ($_annonce->type == $t->id) echo ' class="active"'; ?>>
                                <?php echo __lang($label) ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                else{?>
                    <li>
                        <a href="<?php echo _ROOT . $_page->url . '/' . clean_str(__lang($label)); ?>"<?php if ($_annonce->type == $t->id) echo ' class="active"'; ?>>
                            <?php echo __lang($label) ?>
                        </a>
                    </li>
                    <?php
                }
            }
        }
        ?>
    </ul>
</div>
<section class="annonce">
    <div class="container">
        <h1 class="titre text-uppercase text-center"><?php echo __lang($_annonce->titre); ?></h1>
        <h2 class="titre text-uppercase text-center"><?php echo $_annonce->pieces. ' pièces' . (empty($_annonce->date_vente) && $_data->afficher_prix === "1" ?   '  -  '.number_format($_annonce->prix, 0, ',',' '). ' €' : ''); ?>  </h2>
        <div class="tiret_vertical"></div>
        <div class="constructor owl-carousel owl-theme " data-nodots>
            <?php
            $captions = array();
            $i = 0;
            foreach ($_annonce->images as $img) {
                $legende = !empty(__lang($img->legende)) ? __lang($img->legende) : '';
                if( !empty($legende) && !array_key_exists($legende, $captions) )
                    $captions[$legende] = $i;
                ?>
                <div class="item">
                    <a data-fancybox="images" href="<?php echo _ROOT.$db_annonce->getAnnonceDirImg($_annonce->id).$img->image; ?>" class="link">
                        <img src="<?php echo _ROOT.$db_annonce->getAnnonceDirImg($_annonce->id).'lg_'.$img->image; ?>" alt="<?php echo escHtml($legende); ?>">
                    </a>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
        <div class="row mt-5 pt-3">
            <div class="col-sm-5 offset-sm-1 d-flex flex-column">
                <div>
                    <h4 class="subtitle_biens text-uppercase">Description</h4>
                    <div class="description"><?php echo nl2br(escHtml(__lang($_data->description))); ?></div>
                </div>
                <?php if (!empty($_data->info)) {?>
                <div>
                    <h4 class="mt-5 subtitle_biens text-uppercase">infos essentielles</h4>
                    <div class="info_list d-flex flex-wrap">
                        <?php
                            foreach ($_data->info as $info) {
                                echo '<div class="d-flex align-items-center">';
                                echo '<img src="' . _ROOT . _DIR_IMG . 'pictos/' . $info->image . '">';
                                echo '<div class="text">' . nl2br($info->text) . '</div>';
                                echo '</div>';
                            }

                        ?>
                    </div>
                </div>
                <?php }?>
            </div>
            <div class="col-sm-5 offset-sm-1">
                <div>
                    <h4 class="subtitle_biens text-uppercase text-center">Votre contact</h4>
                    <div class="d-flex align-items-center contact_box">
                        <div>
                            <img class="portrait" src="<?php echo _ROOT._DIR_MEDIA.$_annonce->equipe_image; ?>">
                        </div>
                        <div class="coordonnees">
                            <h5><?php echo $_annonce->equipe_prenom . ' ' . $_annonce->equipe_nom; ?></h5>
                            <div><?php echo $_annonce->equipe_profession; ?></div>
                            <?php
                            if( !empty($_annonce->equipe_telephone) ) {
                                ?>
                                <div>
                                    <a href="tel:<?php echo preg_replace('/\s/', '', $_annonce->equipe_telephone); ?>"><?php echo $_annonce->equipe_telephone; ?></a>
                                </div>
                                <?php
                            }
                            if( !empty($_annonce->equipe_email) ) {
                                ?>
                                <div>
                                    <a href="mailto:<?php echo $_annonce->equipe_email; ?>"><?php echo $_annonce->equipe_email; ?></a>
                                </div>
                                <?php
                            }
                            ?>
                            <a data-fancybox data-src="#rappel" href="javascript:;" class="link_arrow">
                                <span class="arrow"></span><span class="text">être appelé</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="vertical-space50"></div>
                <?php
                if (!$_annonce->isLocation){
                    ?>

                    <div>
                        <div class="col-xs-6">
                            <h4 class="subtitle_biens text-uppercase text-center">Financement</h4>
                            <div class="d-flex align-items-center">
                                <ul class="point_list">
                                    <li>Prix TTC: <?php echo number_format($_annonce->prix, 0, ',', ' ') ?> €</li>
                                    <?php echo !empty($_data->prix_HC) ? '<li>Prix hors charge: '.number_format($_data->prix_HC, 0, ',', ' ').'  €  </li>' : '';?>
                                    <?php echo !empty($_data->honoraire) ? '<li>Taux honoraire: '. $_data->honoraire .' % </li>' : '' ;?>
                                    <?php echo !empty($_data->charge)? '<li>A la charge de l\'' .$_data->charge .' </li>' : '';?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        if (!$_annonce->isLocation && $_annonce->type == 2 && (!empty($_data->nb_rent_lot) || !empty($_data->nb_rent_copro))) {
                            ?>
                            <div class="vertical-space50"></div>
                            <div class="col-xs-6">
                                <h4 class="subtitle_biens text-uppercase text-center">Copropriété</h4>
                                <div class="d-flex align-items-center">
                                    <ul class="point_list">
                                        <?php echo !empty($_data->nb_rent_copro) ?'<li> Charge de copropriété:' . $_data->nb_rent_copro.' € / mois</li>': '' ;?>
                                        <?php echo !empty($_data->nb_rent_lot)? '<li> Nombre de lot: '.$_data->nb_rent_lot.' </li>' : '';?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                if(!empty($_data->point)){?>
                    <div class="vertical-space50"></div>
                    <div>

                        <h4 class="subtitle_biens text-uppercase text-center"> points d'intérêts </h4>
                        <div>
                            <ul class="point_list">
                                <?php foreach ($_data->point as $point) {
                                    echo '<li>' . $point . '</li>';
                                } ?>
                            </ul>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="mt-5 text-center">
            <a class="link_arrow" href="<?php echo _ROOT_LANG . $_page_contact->url; ?>">
                <span class="arrow"></span>Contactez-nous<br>pour tout renseignement<br>complémentaire
            </a>
        </div>
        <div class="tiret"></div>
        <div class="vertical-space60"></div>
        <?php
        if( !empty($_annonce->lat) && !empty($_annonce->lng) ) {
            ?>
            <div id="mapid" data-lat="<?php echo $_annonce->lat; ?>" data-lng="<?php echo $_annonce->lng; ?>"></div>
            <?php
        }
        ?>
    </div>

    <div class="d-none">
        <div id="rappel">
            <div class="container">
                <?php
                include _DIR_FORMS . 'rappel_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

