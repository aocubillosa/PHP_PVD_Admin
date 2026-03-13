<style>
    .panel-green:hover {
        cursor: pointer;
        background-color: #d4edda;
        border-color: #c3e6cb;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .panel-blue:hover {
        cursor: pointer;
        background-color: #337ab7;
        border-color: #337ab7;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
</style>

<div id="page-wrapper">
    <div class="row">
        <br>
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
                        <i class="fa fa-dashboard fa-fw"></i>
						<?php echo strtoupper($sedes[0]['nombre_sede']); ?>
					</h4>
				</div>
			</div>
		</div>
    </div>
    <br>
    <?php
    $retornoExito = $this->session->flashdata('retornoExito');
    if ($retornoExito) {
    ?>
    	<div class="row">
    		<div class="col-lg-12">	
    			<div class="alert alert-success ">
    				<span class="fa fa-check" aria-hidden="true"></span>
    				<strong><?php echo $this->session->userdata("firstname"); ?></strong> <?php echo $retornoExito ?>
    			</div>
    		</div>
    	</div>
    <?php
    }
    $retornoError = $this->session->flashdata('retornoError');
    if ($retornoError) {
    ?>
    	<div class="row">
    		<div class="col-lg-12">	
    			<div class="alert alert-danger ">
    				<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
    				<?php echo $retornoError ?>
    			</div>
    		</div>
    	</div>
    <?php
    }
    ?>
    <div class="row" style="display: flex; justify-content: center;">
        <div class="col-lg-2 col-md-4">
            <div class="panel panel-green" id="panel-visitantes">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user-secret fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $visitantes; ?></div>
                            <div>VISITANTES</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="panel panel-blue" id="panel-inventario">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-desktop fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $inventario; ?></div>
                            <div>INVENTARIO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
    <br><br>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart fa-fw"></i> ESTADISTICAS
                </div>
                <div class="panel-body small">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50%" class="text-center">ACTIVIDAD</th>
                                <th width="50%" class="text-center">PORCENTAJE</th>
                            </tr>
                        </thead>
                        <?php
                            if ($visitantes == 0){
                                $porcentaje = 0;
                            } else {
                                $porcentaje = ($ingresos * 100) / $visitantes;
                            }
                            if ($porcentaje > 70){
                                $estilos = "progress-bar-success";
                            } elseif ($porcentaje > 40 && $porcentaje <= 70){
                                $estilos = "progress-bar-warning";
                            } else {
                                $estilos = "progress-bar-danger";
                            }
                            echo "<tr>";
                            echo "<td>INGRESOS</td>";
                            echo "<td class='text-center'>";
                            echo "<b>" . number_format($porcentaje, 2) ."%</b>";
                            echo '<div class="progress progress-striped">
                                      <div class="progress-bar ' . $estilos . '" role="progressbar" style="width: '. number_format($porcentaje, 2) .'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' . number_format($porcentaje, 2) . '%</div>
                                    </div>';
                            echo "</td>";
                            echo "</tr>";
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#panel-visitantes').click(function() {
        var url = base_url + "entrances/incomesVisitantes";
        $(location).attr("href", url);
    });
    $('#panel-inventario').click(function() {
        var url = base_url + "settings/inventory";
        $(location).attr("href", url);
    });
</script>