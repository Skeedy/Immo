<?php

function parseContent($content) {
	global $_PARAMS, $_LANGS, $_lang;
	if(!empty($content)) {
		foreach($content as $v) {

			if(!empty($v->row1)) {
				$el = 'row1';
				?>
				<div class="<?php echo $v->{$el}->container; ?> container-article<?php if(!empty($v->{$el}->classes)) echo ' '.$v->{$el}->classes; ?>">
					<?php
					if(!empty($v->{$el}->col0->cols) && $v->{$el}->col0->cols != 12) {
						echo '<div class="row align-items-'.$v->{$el}->align.'">';
						echo '<div class="'.$v->{$el}->align.' '.'col-sm-'.$v->{$el}->col0->cols.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
					}
					if(!empty($v->{$el}->col0->content))
						parseContent($v->{$el}->col0->content);
					if(!empty($v->{$el}->col0->cols) && $v->{$el}->col0->cols != 12)
						echo '</div></div>';
					?>
				</div>
				<?php
			}
			else if(!empty($v->row2)) {
				$el = 'row2';
				?>
				<div class="<?php echo $v->{$el}->container; ?> container-article<?php if(!empty($v->{$el}->classes)) echo ' '.$v->{$el}->classes; ?>">
					<div class="row <?php echo 'align-items-'.$v->{$el}->align ?>">
						<?php
						if(!empty($v->{$el}->col0->cols) && $v->{$el}->col0->cols != 6)
							echo '<div class="col-sm-'.$v->{$el}->col0->cols.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
						else
							echo '<div class="col-sm-6'.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
				
							if(!empty($v->{$el}->col0->content))
								parseContent($v->{$el}->col0->content);

						echo '</div>';

						if(!empty($v->{$el}->col1->cols) && $v->{$el}->col1->cols != 6)
							echo '<div class="col-sm-'.$v->{$el}->col1->cols.(!empty($v->{$el}->col1->offset) ? ' offset-sm-'.$v->{$el}->col1->offset : '').'">';
						else
							echo '<div class="col-sm-6'.(!empty($v->{$el}->col1->offset) ? ' offset-sm-'.$v->{$el}->col1->offset : '').'">';
				
							if(!empty($v->{$el}->col1->content))
								parseContent($v->{$el}->col1->content);

						echo '</div>';
						?>
					</div>
				</div>
				<?php
			}
			else if(!empty($v->row3)) {
				$el = 'row3';
				?>
				<div class="<?php echo $v->{$el}->container; ?> container-article<?php if(!empty($v->{$el}->classes)) echo ' '.$v->{$el}->classes; ?>">
					<div class="row <?php echo 'align-items-'.$v->{$el}->align ?>">
						<?php
						if(!empty($v->{$el}->col0->cols) && $v->{$el}->col0->cols != 4)
							echo '<div class="col-sm-'.$v->{$el}->col0->cols.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
						else
							echo '<div class="col-sm-4'.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
				
							if(!empty($v->{$el}->col0->content))
								parseContent($v->{$el}->col0->content);

						echo '</div>';

						if(!empty($v->{$el}->col1->cols) && $v->{$el}->col1->cols != 4)
							echo '<div class="col-sm-'.$v->{$el}->col1->cols.(!empty($v->{$el}->col1->offset) ? ' col-sm-offset-'.$v->{$el}->col1->offset : '').'">';
						else
							echo '<div class="col-sm-4'.(!empty($v->{$el}->col1->offset) ? ' offset-sm-'.$v->{$el}->col1->offset : '').'">';
				
							if(!empty($v->{$el}->col1->content))
								parseContent($v->{$el}->col1->content);

						echo '</div>';

						if(!empty($v->{$el}->col2->cols) && $v->{$el}->col2->cols != 4)
							echo '<div class="col-sm-'.$v->{$el}->col2->cols.(!empty($v->{$el}->col2->offset) ? ' col-sm-offset-'.$v->{$el}->col2->offset : '').'">';
						else
							echo '<div class="col-sm-4'.(!empty($v->{$el}->col2->offset) ? ' offset-sm-'.$v->{$el}->col2->offset : '').'">';
				
							if(!empty($v->{$el}->col2->content))
								parseContent($v->{$el}->col2->content);

						echo '</div>';
						?>
					</div>
				</div>
				<?php
			}
			else if(!empty($v->row4)) {
				$el = 'row4';
				?>
				<div class="<?php echo $v->{$el}->container; ?> container-article<?php if(!empty($v->{$el}->classes)) echo ' '.$v->{$el}->classes; ?>">
					<div class="row <?php echo 'align-items-'.$v->{$el}->align ?>">
						<?php
						if(!empty($v->{$el}->col0->cols) && $v->{$el}->col0->cols != 3)
							echo '<div class="col-sm-'.$v->{$el}->col0->cols.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
						else
							echo '<div class="col-sm-3'.(!empty($v->{$el}->col0->offset) ? ' offset-sm-'.$v->{$el}->col0->offset : '').'">';
				
							if(!empty($v->{$el}->col0->content))
								parseContent($v->{$el}->col0->content);

						echo '</div>';

						if(!empty($v->{$el}->col1->cols) && $v->{$el}->col1->cols != 3)
							echo '<div class="col-sm-'.$v->{$el}->col1->cols.(!empty($v->{$el}->col1->offset) ? ' offset-sm-'.$v->{$el}->col1->offset : '').'">';
						else
							echo '<div class="col-sm-3'.(!empty($v->{$el}->col1->offset) ? ' offset-sm-'.$v->{$el}->col1->offset : '').'">';
				
							if(!empty($v->{$el}->col1->content))
								parseContent($v->{$el}->col1->content);

						echo '</div>';

						if(!empty($v->{$el}->col2->cols) && $v->{$el}->col2->cols != 3)
							echo '<div class="col-sm-'.$v->{$el}->col2->cols.(!empty($v->{$el}->col2->offset) ? ' offset-sm-'.$v->{$el}->col2->offset : '').'">';
						else
							echo '<div class="col-sm-3'.(!empty($v->{$el}->col2->offset) ? ' offset-sm-'.$v->{$el}->col2->offset : '').'">';
				
							if(!empty($v->{$el}->col2->content))
								parseContent($v->{$el}->col2->content);

						echo '</div>';

						if(!empty($v->{$el}->col3->cols) && $v->{$el}->col3->cols != 3)
							echo '<div class="col-sm-'.$v->{$el}->col3->cols.(!empty($v->{$el}->col3->offset) ? ' offset-sm-'.$v->{$el}->col3->offset : '').'">';
						else
							echo '<div class="col-sm-3'.(!empty($v->{$el}->col3->offset) ? ' offset-sm-'.$v->{$el}->col3->offset : '').'">';
				
							if(!empty($v->{$el}->col3->content))
								parseContent($v->{$el}->col3->content);

						echo '</div>';
						?>
					</div>
				</div>
				<?php
			}
			else if(!empty($v->text)) {
				$el = 'text';
				?>
				<div class="content <?php if(!empty($v->{$el}->classes)) echo escHtml($v->{$el}->classes); ?>">
					<?php
					if(!empty(__lang($v->{$el})))
						echo decodeDirs(fixSpecials(__lang($v->{$el})));
					?>
				</div>
				<?php
			}
			else if(!empty($v->texthidden)) {
				$el = 'texthidden';
				?>
				<div class="texthidden collapse content <?php if(!empty($v->{$el}->classes)) echo escHtml($v->{$el}->classes); ?>">
					<?php
					if(!empty(__lang($v->{$el}->text)))
						echo decodeDirs(fixSpecials(__lang($v->{$el}->text)));
					?>
				</div>
				<div class="toggle_texthidden notdevelopped" data-text-notdevelopped="<?php echo escHtml('<span><span class="icon icon-fleche-down"></span><br>'.(!empty(__lang($v->{$el}->btntext)) ? __lang($v->{$el}->btntext) : __str('Lire la suite')).'</span>', true); ?>" data-text-developped="<?php echo escHtml('<span><span class="icon icon-fleche-up"></span><br>'.__str('Retour en haut').'</span>'); ?>">
					<span>
						<span class="icon icon-fleche-down"></span><br><?php echo escHtml(!empty(__lang($v->{$el}->btntext)) ? __lang($v->{$el}->btntext) : __str('Lire la suite'), true); ?>
					</span>
				</div>
				<?php
			}
			else if(!empty($v->imageblock)) {
				$el = 'imageblock';
				?>
				<div class="imageblock <?php if(!empty($v->{$el}->classes)) echo escHtml($v->{$el}->classes); ?>">
					<?php
					if(!empty($v->{$el}->image))
						echo '<img class="img" src="'._ROOT._DIR_MEDIA.$v->{$el}->image.'" alt="">';
					if(!empty(__lang($v->{$el}->content))) {
						echo '<div class="content"><div class="dummy-middle"></div><div>';
							echo decodeDirs(fixSpecials(__lang($v->{$el}->content)));
						echo '</div></div>';
					}
					if(!empty(__lang($v->{$el}->url)))
						echo '<a class="link"'.(!empty($v->{$el}->target) ? ' target="_blank"' : '').' href="'.decodeDirs(__lang($v->{$el}->url)).'"></a>';
					?>
				</div>
				<?php
			}
			else if(!empty($v->gallery)) {
				$el = 'gallery';
				?>
				<div class="images-grid grid-<?php echo $v->{$el}->nb; ?>">
					<?php
					if(!empty($v->{$el}->images)) {
						for($i = 0; $i < count($v->{$el}->images); $i++) {
							?>
							<div class="grid-item">
								<?php
								if(!empty($v->{$el}->fancybox))
									echo '<a class="fancyboxgallery" href="'._ROOT._DIR_MEDIA.$v->{$el}->images[$i]->image.'" rel="gallery"'.(!empty(escHtml(strip_tags(__lang($v->{$el}->images[$i]->legend)))) ? ' title="'.escHtml(strip_tags(__lang($v->{$el}->images[$i]->legend))).'"' : '').'>';
								?>
									<img class="lazyload" data-src="<?php echo _ROOT._DIR_MEDIA.$v->{$el}->images[$i]->image; ?>" alt="<?php echo escHtml(strip_tags(__lang($v->{$el}->images[$i]->legend))); ?>">
									<noscript>
										<img src="<?php echo _ROOT._DIR_MEDIA.$v->{$el}->images[$i]->image; ?>" alt="<?php echo escHtml(__lang($v->{$el}->images[$i]->legend)); ?>">
									</noscript>
								<?php
								if(!empty($v->{$el}->fancybox))
									echo '</a>';
								?>
							</div>
							<?php
						}
					}
					?>
				</div>
				<?php
			}
			else if(!empty($v->diaporama)) {
				$el = 'diaporama';
				$data = array();
				if(!empty($v->{$el}->nb) && is_numeric($v->{$el}->nb))
					$data[] = 'data-nb="'.$v->{$el}->nb.'"';
				if(!empty($v->{$el}->time) && is_numeric($v->{$el}->time))
					$data[] = 'data-time="'.$v->{$el}->time.'"';
				if(!empty($v->{$el}->nav))
					$data[] = 'data-nav="true"';
				if(!empty($v->{$el}->dots))
					$data[] = 'data-dots="true"';
				?>
				<div class="constructor owl-carousel owl-theme" <?php echo implode(' ', $data); ?>>
					<?php
					if(!empty($v->{$el}->images)) {
						foreach($v->{$el}->images as $w) {
							echo '<div class="item"'.(!empty($v->{$el}->height) && is_numeric($v->{$el}->height) ? ' style="height:'.$v->{$el}->height.'px;"' : '').'>';
								if(!empty($v->{$el}->height) && is_numeric($v->{$el}->height))
									echo '<div class="bg" style="background-image:url(\''._ROOT._DIR_MEDIA.$w->image.'\');"></div>';
								else
									echo '<img src="'._ROOT._DIR_MEDIA.$w->image.'" alt="'.escHtml(strip_tags(__lang($w->legend))).'">';
								if(!empty($w->content))
									echo '<div class="container content"><div class="dummy-middle"></div><div>'.decodeDirs(fixSpecials(__lang($w->content))).'</div></div>';
								if(!empty($v->{$el}->fancybox))
									echo '<a class="link fancyboxgallery" href="'._ROOT._DIR_MEDIA.$w->image.'" rel="gallery"'.(!empty(escHtml(strip_tags(__lang($w->legend)))) ? ' title="'.escHtml(strip_tags(__lang($w->legend))).'"' : '').'></a>';
								if(!empty(__lang($w->url)))
									echo '<a class="link" href="'.decodeDirs(__lang($w->url)).'"'.(!empty($w->blank) ? ' target="_blank"' : '').'></a>';
							echo '</div>';
						}
					}
					?>
				</div>
				<?php
			}
			else if(!empty($v->space)) {
				$el = 'space';
				?>
				<div class="vertical-space<?php echo $v->{$el}; ?>"></div>
				<?php
			}
			else if(!empty($v->instagram)) {
				$el = 'instagram';
				?>
				<div class="instagram_wrap"<?php if(!empty($v->{$el}->nb) && is_numeric($v->{$el}->nb)) echo ' data-count="'.$v->{$el}->nb.'"'; ?><?php if(!empty($v->{$el}->infinite)) echo ' data-lastid=""'; ?> data-action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<div class="inner"></div>
					<?php
					if(!empty($v->{$el}->infinite)) {
						?>
						<div class="loader_circular">
							<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			else if(!empty($v->map)) {
				$el = 'map';
				if(is_numeric($v->{$el}->latitude) && is_numeric($v->{$el}->longitude)) {
					loadJS('https://unpkg.com/leaflet@1.4.0/dist/leaflet.js');

					echo '<div class="hidden-print gmap gmap-constructor" data-lat="'.$v->{$el}->latitude.'" data-lng="'.$v->{$el}->longitude.'" data-pointer="'.(!empty($v->{$el}->pointer) ? 1 : 0).'"></div>';
				}
			}
			
		}
	}
}
