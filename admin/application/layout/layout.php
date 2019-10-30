<?php

include _DIR_LAYOUT.'head.php';
include _DIR_LAYOUT.'header.php';
?>
<a id="back_to_top" href="#header"><i class="glyphicon glyphicon-arrow-up"></i></a>
<div id="sidebar">
	<div data-spy="affix" data-offset-top="50">
		<ul>
			<?php
			foreach($_MENU as $k => $m) {
				if(empty($m['access']) || in_array($_current_user->role, $m['access'])) {
					echo '<li'.($_GET['controller'] == $k ? ' class="developped"' : '').'>';
						echo '<a href="'._ROOT_ADMIN.'?controller='.$m['url'].'" title="'.$m['title'].'"'.($_GET['controller'] == $k ? ' class="active"' : '').'><span class="pictogram">'.$m['icon'].'</span> '.$m['title'].'</a>';
						if(!empty($m['sousmenu'])) {
							echo '<ul>';
								foreach ($m['sousmenu'] as $kk => $mm) {
									echo '<li>';
										echo '<a href="'._ROOT_ADMIN.'?controller='.$m['url'].'&view='.$mm['url'].'" title="'.$mm['title'].'"'.($_GET['controller'] == $k && !empty($_GET['view']) && $_GET['view'] == $kk ? ' class="active"' : '').'>'.$mm['title'].'</a>';
									echo '</li>';
								}
							echo '</ul>';
						}
					echo '</li>';
				}
			}
			?>
		</ul>
	</div>
</div>

<div id="mobile_menu">
	<select class="form-control">
		<?php
		foreach($_MENU as $k => $m) {
			if(empty($m['access']) || in_array($_current_user->acces, $m['access']))
				echo '<option value="'._ROOT_ADMIN.'?controller='.$m['url'].'"'.($_GET['controller'] == $k ? ' selected' : '').'>'.$m['title'].'</option>';
		}
		?>
	</select>
</div>

<section id="main">
	<div class="row-fluid">
		<div class="col_md_12">
			<?php
			if(!empty($_ALERTS))
				echo '<div id="alerts">'.$_ALERTS.'</div>';
				
			include _DIR_VIEWS.$_view.'_view.php';
			?>
		</div>
	</div>
</section>
<?php

include _DIR_LAYOUT.'footer.php';
