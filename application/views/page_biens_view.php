<section class="page page-biens" data-url="<?php echo _ROOT_LANG . $_page->url; ?>">
	<div class="content">
		<div class="container h-100">
			<div class="position-relative h-100">
				<div class="content-inner scrollbar scrollbar-inner">
					<?php
					if( !empty($_data->biens_titre) ) {
						echo '<h1>' . nl2br(__lang($_data->biens_titre)) . '</h1>';
					}
					?>
					<div class="list-biens d-flex align-items-stretch flex-wrap">
						<?php
						include _DIR_VIEWS . 'bien_view.php';
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page_bottom container">
		<span class="scroll"></span>
		<?php
		$page = $db_page->getPageFromTemplate('all_biens');
		if( $page ) {
			?>
			<span class="all_biens">
				<a href="<?php echo _ROOT_LANG . $page->url; ?>">Voir tous nos biens</a>
			</span>
			<?php
		}
		?>
	</div>
</section>
