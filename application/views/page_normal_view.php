<?php
require_once _DIR_VIEWS.'content_parser.php';
?>

<?php
if (!empty($_data->normal))
    parseContent($_data->normal);

$i = 0;
foreach ($equipe as $equipier) {
    if ($equipier->isActive) {
        if ($i % 2 === 0) {
            ?>

            <div class="vertical-space50"></div>
			<div class="container container-article justify-content-around">
				<div class="row align-items-center">
					<div class="col-sm-4 offset-sm-1">
						<div class="content quote light-brown">
							<p class="text-center">“</p>
						</div>
						<div class="content titre-nom">
							<p class="text-uppercase">
								<strong><?php echo $equipier->nom . ' ' . $equipier->prenom; ?></strong></p>
						</div>
						<div class="content ">
							<p class="text-justify"><?php echo $equipier->description ?></p>
						</div>
						<div class="content quote-reverse light-brown">
							<p class="text-center">“</p>
						</div>
					</div>
					<div class="col-sm-6 offset-sm-1">
						<div class="content ">
							<p><img src="<?php echo _ROOT . _DIR_MEDIA . $equipier->img; ?>" alt=""></p>
						</div>
					</div>
				</div>
			</div>

            <?php
        } else {
            ?>
            <div class="vertical-space50"></div>
			<div class="container container-article justify-content-around cabinet-offset-negative">
				<div class="row align-items-center">
					<div class="col-sm-6">
						<div class="content text-center">
							<p><img src="<?php echo _ROOT . _DIR_MEDIA . $equipier->img; ?>" alt=""></p>
						</div>
					</div>
                    <div class="col-sm-2">
                    </div>
					<div class="col-sm-4 offset-sm-1">
						<div class="content quote blue">
							<p class="text-center"><em></em><span>“</span></p>
						</div>
						<div class="content titre-nom">
							<p class="text-uppercase">
								<strong><?php echo $equipier->nom . ' ' . $equipier->prenom; ?></strong></p>
						</div>
						<div class="content ">
							<p class="text-justify"><?php echo $equipier->description; ?></p>
						</div>
						<div class="content quote-reverse blue">
							<p class="text-center"><span>“</span></p>
						</div>
					</div>
				</div>
			</div>

            <?php
        }
        $i++;
    }
}
?>
<div class="vertical-space50"></div>
<div class="content text-center">
	<p>
		<img src="http://lapalus.thekub.com/media/home/fnaim.png?1566230689488" alt="FNAIM">
	</p>
	<div>
		<a class="link_arrow" target="_blank" href="https://extranet.fnaim.fr/fede/outils-communication/Documents/CODE_ETHIQUE_DEONTOLOGIE_2016_GD-PUBLIC_HD.pdf"> <span class="arrow"></span>Aller vers<br>la charte éthique </a>
	</div>
	<div class="tiret"></div>
	<div>
		<a class="link_arrow" target="_blank" href="https://www.google.com/maps/place/33+Rue+Capdeville,+33000+Bordeaux/@44.8441288,-0.588229,3a,75y,31.94h,78.49t/data=!3m7!1e1!3m5!1sP6Ex5DijNZjKCEvjz44ArQ!2e0!6s%2F%2Fgeo1.ggpht.com%2Fcbk%3Fpanoid%3DP6Ex5DijNZjKCEvjz44ArQ%26output%3Dthumbnail%26cb_client%3Dmaps_sv.tactile.gps%26thumb%3D2%26w%3D203%26h%3D100%26yaw%3D9.852643%26pitch%3D0%26thumbfov%3D100!7i16384!8i8192!4m5!3m4!1s0xd5527e40bd48dc1:0x252808efcd1b95c3!8m2!3d44.8442085!4d-0.5881713"> <span class="arrow"></span>Visiter notre<br>cabinet </a>
	</div>
</div>