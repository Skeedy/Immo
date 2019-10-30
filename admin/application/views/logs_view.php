<ul class="nav nav-tabs">
	<li class="active"><a href="#tab_outils" data-toggle="tab"><span class="pictogram">&#128214;</span> Logs</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab_outils">
		
		<form class="form-inline" method="get">
			<input type="hidden" name="controller" value="<?php echo $_controller; ?>">
			<label for="frm_logs_debut">Début : </label>
			<input type="text" id="frm_logs_debut" name="debut" class="form-control" value="<?php echo $debut_str; ?>" placeholder="jj/mm/aaaa hh:mm:ss">
			<label for="frm_logs_fin">Fin : </label>
			<input type="text" id="frm_logs_fin" name="fin" class="form-control" value="<?php echo $fin_str; ?>" placeholder="jj/mm/aaaa hh:mm:ss">
			<label for="frm_logs_elements">Éléments : </label>
			<input style="width:300px;" type="text" id="frm_logs_elements" name="elements" class="form-control" value="<?php echo $elements; ?>" placeholder="RDV ADMIN1">
			<button type="submit" class="btn btn-primary">Rechercher</button>
			<?php
			if(!empty($search))
				echo '<a href="'._ROOT_ADMIN.'?controller='.$_controller.'" class="btn btn-danger">Réinitialiser</a>';
			?>
		</form>

		<br>

		<div class="table-responsive">
		<table id="clientslist" class="table table-list table-striped table-hover2">
			<tr>
				<th>Date</th>
				<th>Éléments</th>
				<th>Label</th>
				<th></th>
			</tr>
			<?php
			foreach($_logs as $v) {
				$data = json_decode($v->data);
				?>
				<tr>
					<td>
						<?php echo date_create($v->date)->format('d/m/Y H:i:s'); ?>
					</td>
					<td>
						<?php 
						$t = preg_replace('/ADMIN([0-9]+)/', '<a href="'._ROOT_ADMIN.'?controller=utilisateurs&view=utilisateur&utilisateur_id=$1">ADMIN$1</a>', $v->elements);
						
						echo $t;
						?>
					</td>
					<td>
						<?php echo $data->label; ?>
					</td>
					<td class="text-center">
						<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal_logs_<?php echo $v->id; ?>">Détails</button>
						<div class="modal fade text-left" id="modal_logs_<?php echo $v->id; ?>" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">ID : <?php echo $v->id; ?> - Date : <?php echo date_create($v->date)->format('d/m/Y H:i:s'); ?><br><?php echo $data->label; ?></h4>
									</div>
									<div class="modal-body">
										<?php
										$data = json_decode(htmlspecialchars($v->data, ENT_NOQUOTES));
										echo '<pre>';
											print_r($data->data);
										echo '</pre>';
										?>
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		</div>
		
	</div>	
</div>
