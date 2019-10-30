<?php
//mise en forme des champs
	

//frm_menu_menu
if(isset($_POST['menu']))
	$frm_menu_menu = json_decode(json_encode($_POST['menu']));
else if(!empty($_menus))
	$frm_menu_menu = $_menus;
else
	$frm_menu_menu = array();

?>
<ol class="breadcrumb quickaccess">
	<li>Accès rapide : </li>
	<?php
	foreach($_menus as $v)
		echo '<li><a href="#anchor-'.clean_str($v->id).'">'.escHtml($v->label).'</a></li>';
	?>
	<li><a href="#anchor-enregistrer">Enregistrer</a></li>
</ol>


<form method="post">

	<?php
	foreach($_menus as $currentmenu) {
		$k = $currentmenu->id;
		$menu = $currentmenu->data;
		?>	
		<br>
		<h2 id="anchor-<?php echo clean_str($k); ?>"><?php echo escHtml($currentmenu->label); ?></h2><hr>

		<div class="list-sortable-handle-dissmissable">
			<?php
			if(!empty($menu)) {
				foreach($menu as $v) {
					$i = rand();
					$iterator = 'iteration'.$i;
					?>
					<div class="dissmissable-block simple">
						<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
						<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
						<?php
						foreach($_LANGS as $l => $ll) {
							?>
							<div class="form-group lang_toggle lang_<?php echo $l; ?>">
								<div class="input-group">
									<span class="input-group-addon"><?php printToggleLang(); ?></span>
									<input type="text" name="menu[<?php echo $k; ?>][<?php echo $iterator; ?>][text][<?php echo $l; ?>]" class="form-control" placeholder="Texte <?php echo printLangTag($l); ?>" value="<?php echo escHtml($v->text->{$l}); ?>">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#menu<?php echo $i; ?>">Détails</button>
									</span>
								</div>
							</div>
							<?php
						}
						?>
						<div class="collapse" id="menu<?php echo $i; ?>">
							<div class="form-inline">
								<div class="form-group">
									<label class="sr-only" for="frm_menu_type_<?php echo $k; ?>_<?php echo $i; ?>">Type</label>
									<select id="frm_menu_type_<?php echo $k; ?>_<?php echo $i; ?>" name="menu[<?php echo $k; ?>][<?php echo $iterator; ?>][type]" class="form-control select_type_menu" data-id="menu_<?php echo $k; ?>_<?php echo $i; ?>">
										<option value="">-- Choisir un type --</option>
										<?php
										foreach(array('page' => 'Page', 'url' => 'URL', 'anchor' => 'Ancre', 'text' => 'Texte') as $kk => $vv)
											echo '<option value="'.$kk.'"'.($kk == $v->type ? ' selected' : '').'>'.$vv.'</option>';
										?>
									</select>
								</div>
								<div class="form-group menu_<?php echo $k; ?>_<?php echo $i; ?> menucontent_page<?php if($v->type != 'page') echo ' hidden'; ?>">
									<label class="sr-only" for="frm_menu_page_<?php echo $k; ?>_<?php echo $i; ?>">Page</label>
									<select id="frm_menu_page_<?php echo $k; ?>_<?php echo $i; ?>" name="menu[<?php echo $k; ?>][<?php echo $iterator; ?>][page]" class="form-control">
										<?php
										foreach($_pages as $vv) {
											$titre = json_decode($vv->titre);
											echo '<option value="'.$vv->id.'"'.($vv->id == $v->page ? ' selected' : '').'>'.escHtml($titre->{_LANG_DEFAULT}).'</option>';
										}	
										?>
									</select>
								</div>
								<div class="form-group menu_<?php echo $k; ?>_<?php echo $i; ?> menucontent_url<?php if($v->type != 'url') echo ' hidden'; ?>">
									<label class="sr-only" for="frm_menu_url_<?php echo $k; ?>_<?php echo $i; ?>">URL</label>
									<input type="text" id="frm_menu_url_<?php echo $k; ?>_<?php echo $i; ?>" name="menu[<?php echo $k; ?>][<?php echo $iterator; ?>][url]" class="form-control" placeholder="URL" value="<?php echo escHtml($v->url); ?>">
								</div>
								<div class="form-group menu_<?php echo $k; ?>_<?php echo $i; ?> menucontent_url<?php if($v->type != 'url') echo ' hidden'; ?>">
									<div class="checkbox">
										<label><input type="checkbox" name="menu[<?php echo $k; ?>][<?php echo $iterator; ?>][targetblank]"<?php if(isset($v->targetblank)) echo ' checked'; ?>><span>Ouvrir dans un nouvel onglet</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>
			<button type="button" class="btn btn-primary btn-sm addsection nofocus" data-pattern="#pattern_menu" data-name="menu[<?php echo $k; ?>]" data-count="> .dissmissable-block" data-function="initVilleTypeahead"><span class="glyphicon glyphicon-plus"></span> Ajouter</button><br>
			<?php
			if(empty($frm_menu_menu)) {
				?>
				<script>
				$(window).load(function(){
					$('button[data-pattern="#pattern_menu"]').click();
				});
				</script>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>

	<hr id="anchor-enregistrer">
	
	<button type="submit" name="action_modify" class="btn btn-lg btn-success">Enregistrer</button>
</form>

<?php
// patterns
//----------------
?>
<div class="hidden">
	
	<div id="pattern_menu">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="{{name}}[{{tid}}][text][<?php echo $l; ?>]" class="form-control" placeholder="Texte <?php echo printLangTag($l); ?>">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#menu{{tid}}">Détails</button>
						</span>
					</div>
				</div>
				<?php
			}
			?>
			<div class="collapse" id="menu{{tid}}">
				<div class="form-inline">
					<div class="form-group">
						<label class="sr-only" for="frm_menu_type_{{tid}}">Type</label>
						<select id="frm_menu_type_{{tid}}" name="{{name}}[{{tid}}][type]" class="form-control select_type_menu" data-id="menu_{{tid}}">
							<option value="">-- Choisir un type --</option>
							<?php
							foreach(array('page' => 'Page', 'url' => 'URL', 'anchor' => 'Ancre', 'text' => 'Texte') as $kk => $vv)
								echo '<option value="'.$kk.'">'.$vv.'</option>';
							?>
						</select>
					</div>
					<div class="form-group menu_{{tid}} menucontent_page hidden">
						<label class="sr-only" for="frm_menu_page_{{tid}}">Page</label>
						<select id="frm_menu_page_{{tid}}" name="{{name}}[{{tid}}][page]" class="form-control">
							<?php
							foreach($_pages as $vv) {
								$titre = json_decode($vv->titre);
								echo '<option value="'.$vv->id.'">'.escHtml($titre->{_LANG_DEFAULT}).'</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group menu_{{tid}} menucontent_url hidden">
						<label class="sr-only" for="frm_menu_url_{{tid}}">URL</label>
						<input type="text" id="frm_menu_url_{{tid}}" name="{{name}}[{{tid}}][url]" class="form-control" placeholder="URL">
					</div>
					<div class="form-group menu_{{tid}} menucontent_url hidden">
						<div class="checkbox">
							<label><input type="checkbox" name="{{name}}[{{tid}}][targetblank]"><span>Ouvrir dans un nouvel onglet</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script>	
$(function() {
	$('body').on('change', '.select_type_menu', function() {
		var $this = $(this);
		var parent = $this.parents('.dissmissable-block').get(0);
		$('.' + $this.data('id') + '[class*="menucontent_"]', parent).addClass('hidden');
		$('.' + $this.data('id') + '.menucontent_' + $this.val(), parent).removeClass('hidden');
	});

});
</script>
