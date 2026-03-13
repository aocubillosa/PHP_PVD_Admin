<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/inventory.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-desktop fa-fw"></i> INVENTARIO
					</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-list"></i> Lista de Inventario
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="x">
								Adicionar Elemento <span class="fa fa-plus" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<?php
						$retornoExito = $this->session->flashdata('retornoExito');
						if ($retornoExito) {
					?>
							<div class="alert alert-success ">
								<span class="fa fa-check" aria-hidden="true"></span>
								<?php echo $retornoExito ?>
							</div>
					<?php
						}
						$retornoError = $this->session->flashdata('retornoError');
						if ($retornoError) {
					?>
							<div class="alert alert-danger ">
								<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
								<?php echo $retornoError ?>
							</div>
					<?php
						}
						if(!$info){ 
							echo '<div class="col-lg-12">
									<p class="text-danger"><span class="fa fa-exclamation-triangle" aria-hidden="true"></span> No hay registros en el sistema.</p>
								</div>';
						} else {
					?>
					<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">N°</th>
								<th class="text-center">Elemento</th>
								<th class="text-center">Descripción</th>
								<th class="text-center">Marca</th>
								<th class="text-center">Placa</th>
								<th class="text-center">Fecha Ingreso</th>
								<th class="text-center">Fecha Servicio</th>
								<th class="text-center">Valor</th>
								<th class="text-center">Estado</th>
								<th class="text-center">Opciones</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 1;
							foreach ($info as $lista):
								echo "<tr>";
								echo '<td class="text-center">' . $i . '</td>';
								echo "<td>" . $lista['elemento'] . "</td>";
								echo "<td>" . $lista['descripcion'] . "</td>";
								echo "<td>" . $lista['marca'] . "</td>";
								echo "<td class='text-right'>" . $lista['placa'] . "</td>";
								echo "<td class='text-center'>" . $lista["fecha_ingreso"] . "</td>";
								echo "<td class='text-center'>" . $lista["fecha_servicio"] . "</td>";
								echo "<td class='text-right'>" . '$ ' . $lista["valor"] . "</td>";
								echo "<td class='text-center'>" . $lista['estado'] . "</td>";
								echo "<td class='text-center'>";
								?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_inventario']; ?>" >
										Editar <span class="fa fa-pencil-square-o" aria-hidden="true">
									</button>
								<?php
								echo "</td>";
								echo "</tr>";
								$i++;
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="tablaDatos">
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 50
	});
});
</script>