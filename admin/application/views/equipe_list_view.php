<div class="title clearfix">
    <h1 class="pull-left">Tous les équipiers</h1>
    <a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
</div>

<hr>


<input type="text" class="form-control filtertext" data-target="#utilisateurslist" placeholder="Rechercher par nom, prénom ou email">

<br>

<div class="table-responsive">
    <table id="equiperlist" class="table table-list table-striped">
        <thead>
        <tr>
            <th class="hassort"><?php printSortList('equipe', 'Nom', 'nom', $_equipe_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#equipierslist'); ?></th>
            <th class="hassort"><?php printSortList('equipe', 'Prénom', 'prenom', $_equipe_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#equipierslist'); ?></th>
            <th class="hassort"><?php printSortList('equipe', 'Email', 'email', $_equipe_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#equipierslist'); ?></th>
            <th class="hassort"><?php printSortList('equipe', 'Téléphone', 'telephone', $_equipe_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#equipierslist'); ?></th>
            <th class="hassort"><?php printSortList('equipe', 'Profession', 'profession', $_equipe_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#equipierslist'); ?></th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($_equipe as $u) {
            ?>
            <tr data-search="<?php echo htmlspecialchars(strip_tags(nl2br($u->nom.$u->prenom.$u->email)), ENT_QUOTES); ?>">
                <td>
                    <a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=equipier&id=<?php echo $u->id; ?>">
                        <?php echo $u->nom; ?>
                    </a>
                </td>
                <td>
                    <a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=equipier&id=<?php echo $u->id; ?>">
                        <?php echo $u->prenom; ?>
                    </a>
                </td>
                <td>
                    <a class="multiline" href="mailto:<?php echo $u->email; ?>"><?php echo $u->email; ?></a>
                </td>
                <td>
                    <div class="multiline"> <?php echo $u->telephone; ?></div>
                </td>
                <td>
                    <div class="multiline"> <?php echo $u->profession; ?></div>
                </td>
                <td class="text-center">
                    <a class="btn btn-primary btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=equipier&id=<?php echo $u->id; ?>" title="Modifier"><span class="glyphicon glyphicon-edit"></span></a>
                    <a class="btn btn-danger btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=list&action=delete&id=<?php echo $u->id; ?>" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
            <?php

        }
        ?>
        </tbody>
    </table>
</div>
