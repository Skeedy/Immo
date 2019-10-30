</div>
<footer id="footer">
    <div class="container">
        <div class="row">
        	<div class="col-md-3 justify-content-end d-flex">
	            <div class="footer-adress">
	                L&A - LAPALUS IMMOBILIER & PATRIMOINE<br>
                    <a class="shiny" href="https://www.google.com/maps/place/33+Rue+Capdeville,+33000+Bordeaux/@44.8442085,-0.59036,17z/data=!3m1!4b1!4m5!3m4!1s0xd5527e40bd48dc1:0x252808efcd1b95c3!8m2!3d44.8442085!4d-0.5881713"> 33 rue Capdeville - 33000 BORDEAUX</a><br>
	                T. <a class="shiny" href="tel:0556382017">05 56 38 20 17</a><br>
	                RCS DE BORDEAUX n°535 101 489
	            </div>
	        </div>
            <div class="col-md-6 text-center">
                <div class="d-flex justify-content-center">
                	<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>Galian.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>FNAIM-black.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>logo_MA_AgPub_fr.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>logo_avenue70.png">
					</div>
                </div>
                <div class="d-flex justify-content-center mt-2">
                	<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>bien'ici.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>Banque_Populaire_2018.svg.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>ce.png">
					</div>
					<div class="text-center mx-4">
						<img src="<?php echo _ROOT . _DIR_IMG; ?>Go_To_Bordeaux.png">
					</div>
                </div>
            </div>
            <div class="col-md-3 d-flex">
				<?php
				$menus = $db_menu->getMenu('footer');
				echo $db_menu->printFooterMenu($menus);
				?>
            </div>
        </div>
    </div>
    <div class="container">
    	<div class="copy">
			<span class="mr-3">©L&A immobilier 2019</span>
			<a class="mr-3 shiny text-uppercase" href="<?php echo _ROOT_LANG. $_page_mention->url ;?>">Mentions légales</a>
			<span class="text-uppercase">Titulaire de la carte professionnelle n°33063-3212 « Transactions sur immeubles et fonds de commerce » delivrée par la préfecture de la Gironde</span>
		</div>
    </div>
</footer>
<script src="<?php echo _ROOT._DIR_LIB; ?>js/bundle.js?<?php echo !empty(_DEBUG_MODE) ? time() : _VERSION; ?>"></script>

<?php
if( empty($_COOKIE['_rgpd_ok']) ) {
    ?>
    <div id="cookies_banner">
        En poursuivant votre navigation sur ce site, vous acceptez l'utilisation de cookies pour vous proposer des services et des offres adaptés. <a href="<?php echo _ROOT_LANG; ?>politique-de-confidentialite">En savoir plus</a> <a class="link_accept_cookies" href="#">OK</a>
    </div>
    <?php
}
print_loadJS();
?>

</body>
</html>
