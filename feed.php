<?php

// test si token = md5('get my fucking feed') -> ebd28a55d1e6fea73da51c895599ae77
if( empty($_GET['token']) || $_GET['token'] != md5('get my fucking feed') ) {
	header('Content-Type: text/plain');
	echo 'Acces denied';
	exit; 
}


//chargement init
require_once 'lib/init.php';

$db_annonce = loadModel('annonce');

$annonces = $db_annonce->getAnnonces();



function __xml($str) {
	return '<![CDATA[' . $str . ']]>';
}


header('Content-Type: text/xml');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?><Agence>
<Client>
	<ClientDetails>
	<clientNom><?php echo __xml('Clever immobilier'); ?></clientNom>
	<clientContact><?php echo __xml(''); ?></clientContact>
	<clientContactEmail><?php echo __xml('fd@clever-immobilier.com'); ?></clientContactEmail>
	<clientTelephone><?php echo __xml('08 36 65 65 65'); ?></clientTelephone>
	</ClientDetails>
	<Annonces>
		<?php
		foreach ($annonces->annonces as $v) {
			$data = json_decode($v->data);
			$titre = json_decode($v->titre);
			$images = json_decode($v->images);
			$dir_img = $db_annonce->getAnnonceDirImg($v->id);
			$type_label = json_decode($v->type_label)
			?>
			<Annonce>
				<referenceInterne><?php echo __xml($v->ref); ?></referenceInterne>
				<statut><?php echo __xml( !empty($v->date_vente) ? 0 : 1 ); ?></statut>
				<referenceMandat><?php echo __xml($v->ref); ?></referenceMandat>
				<departementNum><?php echo __xml( substr($v->cp, 0, 2) ); ?></departementNum>
				<codePostal><?php echo __xml($v->cp); ?></codePostal>
				<typeTransaction><![CDATA[vente]]></typeTransaction>
				<typeBien><?php echo __xml($type_label->{_LANG_DEFAULT}); ?></typeBien>
				<ville><?php echo __xml($v->nom_reel); ?></ville>
				<?php
				preg_match('/^(\d*)\s*(.*)$/', $v->adresse, $matches);
				?>
				<numruepublic><?php echo __xml($matches[1]); ?></numruepublic>
				<ruepublic><?php echo __xml($matches[2]); ?></ruepublic>
				<prix><?php echo __xml($v->prix); ?></prix>
				<surface><?php echo __xml($v->superficie); ?></surface>
				<surfaceTerrain><?php echo __xml( !empty($data->superficie_terrain) ? $data->superficie_terrain : '' ); ?></surfaceTerrain>
				<nombrePiece><?php echo __xml( !empty($data->nbr_pieces) ? $data->nbr_pieces : '' ); ?></nombrePiece>
				<nombreEtages><?php echo __xml( !empty($data->nbr_etages) ? $data->nbr_etages : '' ); ?></nombreEtages>
				<niveauEtage><?php echo __xml( !empty($data->etage) ? $data->etage : '' ); ?></niveauEtage>
				<nombreChambre><?php echo __xml( !empty($data->nbr_chambres) ? $data->nbr_chambres : '' ); ?></nombreChambre>
				<NombreSalleDeBain><?php echo __xml( !empty($data->nbr_SDB) ? $data->nbr_SDB : '' ); ?></NombreSalleDeBain>
				<nombreSalleDeau><?php echo __xml( !empty($data->nbr_SDE) ? $data->nbr_SDE : '' ); ?></nombreSalleDeau>
				<garage><?php echo __xml( !empty($data->garage) ? 1 : 0 ); ?></garage>
				<parking><?php echo __xml( !empty($data->parking) ? 1 : 0 ); ?></parking>
				<ascenseur><?php echo __xml( !empty($data->ascenseur) ? 1 : 0 ); ?></ascenseur>
				<piscine><?php echo __xml( !empty($data->piscine) ? 1 : 0 ); ?></piscine>
				<dpe>
					<ges><?php echo __xml( !empty($data->ges) ? $data->ges : '' ); ?></ges>
					<?php
					$ges = '';
					if( !empty($data->ges) ) {
						if($data->ges <= 5)
							$ges = 'a';
						else if($data->ges <= 10)
							$ges = 'b';
						else if($data->ges <= 20)
							$ges = 'c';
						else if($data->ges <= 35)
							$ges = 'd';
						else if($data->ges <= 55)
							$ges = 'e';
						else if($data->ges <= 80)
							$ges = 'f';
						else
							$ges = 'g';
					}
					?>
					<bges><?php echo __xml($ges); ?></bges>
					<ce><?php echo __xml( !empty($data->dpe) ? $data->dpe : '' ); ?></ce>
					<?php
					$dpe = '';
					if( !empty($data->dpe) ) {
						if($data->dpe <= 50)
							$dpe = 'a';
						else if($data->dpe <= 90)
							$dpe = 'b';
						else if($data->dpe <= 150)
							$dpe = 'c';
						else if($data->dpe <= 230)
							$dpe = 'd';
						else if($data->dpe <= 330)
							$dpe = 'e';
						else if($data->dpe <= 450)
							$dpe = 'f';
						else
							$dpe = 'g';
					}
					?>
					<bce><?php echo __xml($dpe); ?></bce>
				</dpe>
				<titre><?php echo __xml($titre->{_LANG_DEFAULT}); ?></titre>
				<descriptif><?php echo __xml($data->description->{_LANG_DEFAULT}); ?></descriptif>
				<images>
					<?php
					$i = 1;
					foreach ($images as $img) {
						?>
						<image number='<?php echo $i; ?>'><?php echo __xml(_PROTOCOL . $_SERVER['HTTP_HOST'] . _ROOT . $dir_img . $img->image); ?></image>
						<?php
						$i++;
					}
					?>
				</images>
			</Annonce>
			<?php
		}
		?>
	</Annonces>
</Client>
</Agence>