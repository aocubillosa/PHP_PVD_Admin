<script type="text/javascript" src="<?php echo base_url("assets/js/validate/reportes/buscar_ingresos_fecha.js"); ?>"></script>

<div id="page-wrapper">
	<div class="row"><br>
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-file-excel-o fa-fw"></i> REPORTE GESTIÓN DE ASISTENCIAS
					</h4>
				</div>
			</div>
		</div>
    </div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading"> 
					<div class="row">
                        <div class="col-lg-6">
                            <i class="fa fa-list fa-fw"></i> <strong>LISTA DE ASISTENCIAS</strong>
                        </div>
                        <div class="col-lg-4">
                        	<form  name="form_descarga" id="form_descarga" method="post" action="" target="_blank">
                            	<input type="hidden" class="form-control" id="from" name="from" value="<?php echo $from; ?>" />
								<input type="hidden" class="form-control" id="to" name="to" value="<?php echo $to; ?>" />
                                <?php
                                if($listaIngresos) {
                                ?>
                                    <button type="button" class="btn btn-secondary" id="btnXLS" name="btnXLS" style="background-image: url('<?php echo base_url('images/xls.png'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 30px; height: 30px; border: none; cursor: pointer; color: transparent;">
                                    </button>&ensp;&ensp;&ensp;
                                    <button type="button" class="btn btn-secondary" id="btnPDF" name="btnPDF" value="1" style="background-image: url('<?php echo base_url('images/pdf.png'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 30px; height: 30px; border: none; cursor: pointer; color: transparent;">
                                    </button>
                                <?php
                                }
                                ?>
                            </form>
                        </div>
                        <div class="col-lg-2">
                        	<div align="right">
                            	<a class="btn btn-success" href=" <?php echo base_url('reportes/entrances'); ?> ">Regresar <span class="fa fa-undo" aria-hidden="true"></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <strong>Desde:</strong> <?php echo $from; ?> - <strong>Hasta:</strong> <?php echo $to; ?>
                        </div>
                    </div>
				</div>
				<div class="panel-body">
				<?php
				if(!$listaIngresos){ 
				?>
			        <div class="col-lg-12">
			            <small>
			                <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No hay registros en la base de datos.</p>
			            </small>
			        </div>
				<?php
				} else {
				?>
					<table width="100%" class="table table-hover" id="dataTables">
						<thead>
							<tr>
                                <th class="text-center">N°</th>
                                <th class="text-center">NOMBRES Y APELLIDOS</th>
                                <th class="text-center">N° DOCUMENTO</th>
                                <th class="text-center">FECHA</th>
                                <th class="text-center">TELÉFONO</th>
                                <th class="text-center">OCUPACION</th>
                                <th class="text-center">HORA</th>
                                <th class="text-center">EDAD</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 1;
							foreach ($listaIngresos as $lista):
                                $ingreso = explode(' ', $lista['fecha_ingreso']);
                                $fecha = $ingreso[0];
                                $hora = $ingreso[1];
                                $edad = (new DateTime('today'))->diff(new DateTime($lista['fecha_nacimiento']))->y;
                                echo '<tr>';
                                echo '<td class="text-center">' . $i . '</td>';
                                echo '<td class="text-center">' . $lista['nombres'] . ' ' . $lista['apellidos'] . '</td>';
                                echo '<td class="text-center">' . $lista['numero_documento'] . '</td>';
                                echo '<td class="text-center">' . $fecha . '</td>';
                                echo '<td class="text-center">' . $lista['telefono'] . '</td>';
                                echo '<td class="text-center">' . $lista['ocupacion'] . '</td>';
                                echo '<td class="text-center">' . $hora . '</td>';
                                echo '<td class="text-center">' . $edad . '</td>';
                                echo '</tr>';
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

<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
		 "ordering": false,
		 paging: false,
		"searching": false,
		"info": false
    });
});
</script>