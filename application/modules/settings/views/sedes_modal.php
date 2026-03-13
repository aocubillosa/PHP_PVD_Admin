<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/sedes.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario de Sede
		<br><small>Adicionar/Editar Sede</small>
	</h4>
</div>
<div class="modal-body">
	<p class="text-danger text-left">Los campos con * son obligatorios.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_sede"]:""; ?>"/>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="sede">Sede: *</label>
					<input type="text" id="sede" name="sede" class="form-control" value="<?php echo $information?$information[0]["nombre_sede"]:""; ?>" placeholder="Sede" required >
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