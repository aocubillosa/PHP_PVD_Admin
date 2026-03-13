<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/inventory.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario de Inventario
		<br><small>Adicionar/Editar Elemento</small>
	</h4>
</div>
<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_inventario"]:""; ?>"/>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="elemento">Elemento: *</label>
					<select name="elemento" id="elemento" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($elementos); $i++) { ?>
							<option value="<?php echo $elementos[$i]["id_elemento"]; ?>" <?php if($information && $information[0]["fk_id_elemento"] == $elementos[$i]["id_elemento"]) { echo "selected"; }  ?>><?php echo $elementos[$i]["elemento"]; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="descripcion">Descripción:</label>
					<input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo $information?$information[0]["descripcion"]:""; ?>" placeholder="Descripción">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="marca">Marca: *</label>
					<select name="marca" id="marca" class="form-control" required>
						<option value=''>Seleccione...</option>
						<?php for ($i = 0; $i < count($marcas); $i++) { ?>
							<option value="<?php echo $marcas[$i]["id_marca"]; ?>" <?php if($information && $information[0]["fk_id_marca"] == $marcas[$i]["id_marca"]) { echo "selected"; }  ?>><?php echo $marcas[$i]["marca"]; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="placa">Placa: *</label>
					<input type="text" id="placa" name="placa" class="form-control" value="<?php echo $information?$information[0]["placa"]:""; ?>" placeholder="Placa" required >
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="fecha_ingreso">Fecha de Ingreso: *</label>
					<input type="text" id="fecha_ingreso" name="fecha_ingreso" class="form-control" value="<?php echo $information?$information[0]["fecha_ingreso"]:""; ?>" placeholder="Fecha de Ingreso" readOnly required/>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="fecha_servicio">Fecha de Servicio:</label>
					<input type="text" class="form-control" id="fecha_servicio" name="fecha_servicio" value="<?php echo $information?$information[0]["fecha_servicio"]:""; ?>" placeholder="Fecha de Servicio" readOnly/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="valor">Valor: *</label>
					<input type="text" id="valor" name="valor" class="form-control" value="<?php echo $information?$information[0]["valor"]:""; ?>" placeholder="Valor" required >
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group text-left">
					<label class="control-label" for="estado">Estado: *</label>
					<select name="estado" id="estado" class="form-control" required >
						<option value="">Seleccione...</option>
						<?php for ($i = 0; $i < count($estados); $i++) { ?>
							<option value="<?php echo $estados[$i]["id_estado"]; ?>" <?php if($information && $information[0]["fk_id_estado"] == $estados[$i]["id_estado"]) { echo "selected"; }  ?>><?php echo $estados[$i]["estado"]; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div id="div_load" style="display:none">
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% completado</span>
					</div>
				</div>
			</div>
			<div id="div_error" style="display:none">
				<div class="alert alert-danger"><span class="fa fa-times" id="span_msj">&nbsp;</span></div>
			</div>
		</div>
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Guardar <span class="fa fa-floppy-o" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
	</form>
</div>