<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="exampleModalLabel"><?php if (!empty($infoPermiso["fk_id_tipo_funcionario"])) { ?> Permisos Funcionarios <?php } else { ?> Permisos Visitantes <?php } ?>
	</h4>
</div>
<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<div class="row">
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<div class="form-group">
					<div align="center">
						<img src="<?php echo base_url($infoPermiso["qr_code_img_doc"]); ?>" class="img-rounded" width="250" height="250" alt="QR CODE" />
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="tipoDocumento">Tipo Documento:</label>
					<input type="text" id="tipoDocumento" name="tipoDocumento" class="form-control" value="<?php echo $infoPermiso["tipo_documento"]; ?>" readOnly >
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="numeroDocumento">Número Documento:</label>
					<input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" value="<?php echo $infoPermiso["numero_documento"]; ?>" readOnly >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="firstName">Nombres:</label>
					<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $infoPermiso["nombres"]; ?>" readOnly >
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="lastName">Apellidos:</label>
					<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $infoPermiso["apellidos"]; ?>" readOnly >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="ocupacion">Ocupación:</label>
					<input type="text" class="form-control" id="ocupacion" name="ocupacion" value="<?php echo $infoPermiso["ocupacion"]; ?>" readOnly />
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="movilNumber">Número Celular:</label>
					<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $infoPermiso["telefono"]; ?>" readOnly >
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<a href="<?php echo base_url('entrances/permissionsVisitantes'); ?>" class="btn btn-primary">
	                    Aceptar <span class="fa fa-check" aria-hidden="true"></span>
	                </a>
				</div>
			</div>
		</div>
	</form>
</div>