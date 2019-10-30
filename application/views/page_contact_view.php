<?php
$title= 'contact';
require_once _DIR_VIEWS.'content_parser.php';

if (!empty($_data->normal))
    parseContent($_data->normal);
?>
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<?php
			include _DIR_FORMS . 'contact_form.php';
			?>
		</div>
	</div>
	<div class="content mt-5">
		<h2 class="mb-4">Nous trouver</h2>
	</div>
	<div id="mapid" data-lat="44.844218" data-lng="-0.588173"></div>
	<div class="vertical-space60"></div>
	<div class="text-center">
		<a class="link_arrow" target="_blank" href="https://www.google.com/maps/place/33+Rue+Capdeville,+33000+Bordeaux/@44.8441288,-0.588229,3a,75y,31.94h,78.49t/data=!3m7!1e1!3m5!1sP6Ex5DijNZjKCEvjz44ArQ!2e0!6s%2F%2Fgeo1.ggpht.com%2Fcbk%3Fpanoid%3DP6Ex5DijNZjKCEvjz44ArQ%26output%3Dthumbnail%26cb_client%3Dmaps_sv.tactile.gps%26thumb%3D2%26w%3D203%26h%3D100%26yaw%3D9.852643%26pitch%3D0%26thumbfov%3D100!7i16384!8i8192!4m5!3m4!1s0xd5527e40bd48dc1:0x252808efcd1b95c3!8m2!3d44.8442085!4d-0.5881713">
			<span class="arrow">
			</span>Visiter<br>nos locaux
		</a>
	</div>
	<div class="tiret"></div>
</div>