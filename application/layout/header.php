<header id="header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="<?php echo _ROOT_LANG; ?>" data-url="<?php echo _ROOT_LANG; ?>">
                <img src="<?php echo _ROOT . _DIR_IMG; ?>LOGO.png" alt="LAPALUS IMMOBILIER">
            </a>
        </div>
        <div class="position-relative d-flex align-items-center flex-grow-1  justify-content-end">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    $menu = $db_menu->getMenu('main');
                    echo $db_menu->printHeaderMenu($menu);
                    ?>
                    <form id="searchbox" class="d-flex" method="get" action="<?php echo _ROOT_LANG; ?>rechercher">
                        <div class="form-control-container">
                            <input id="searchInput" class="form-control" type="text" name="s" placeholder="Rechercher">
                        </div>
                        <button type="button">
                            <img src="<?php echo _ROOT . _DIR_IMG; ?>search.png">
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</header>
<div id="main">
