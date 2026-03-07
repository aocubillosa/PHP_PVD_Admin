<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function(){
        $(".btn-primary").click(function () {
            var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
                url: base_url + 'reportes/modalIngresosFecha',
                data: {'idLink': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
        });
    });
</script>
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
        <div class="col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-12">
                            <i class="fa fa-list fa-fw"></i> <strong>LISTA DE ASISTENCIAS</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <strong>Fecha:</strong> <?php echo date('Y-m-d'); ?>
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
        <div class="col-lg-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-ticket fa-fw"></i> ASISTENCIAS
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item" disabled>
                            <p class="text-success"><i class="fa fa-tag fa-fw"></i><strong> No. Asistencias Hoy</strong>
                                <span class="pull-right text-muted small"><em><?php echo $noIngresosHOY; ?></em>
                                </span>
                            </p>
                        </a>
                        <a href="#" class="list-group-item" disabled>
                            <p class="text-info"><i class="fa fa-tag fa-fw"></i><strong> No. Asistencias esta Semana</strong>
                                <span class="pull-right text-muted small"><em><?php echo $noIngresosSEMANA; ?></em>
                                </span>
                            </p>
                        </a>
                        <a href="#" class="list-group-item" disabled>
                            <p class="text-warning"><i class="fa fa-tag fa-fw"></i><strong> No. Asistencias este Mes</strong>
                                <span class="pull-right text-muted small"><em><?php echo $noIngresosMES; ?></em>
                                </span>
                            </p>
                        </a>
                    </div>
                    <div class="list-group">
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal" id="x">Buscar Asistencias x Fecha <span class="fa fa-search" aria-hidden="true"></span> 
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="tablaDatos">
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