<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-qrcode"></i> INFORMACIÓN ACCESO
					</h4>
				</div>
				<br>
				<div class="panel-body">
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
									<input type="text" id="tipoDocumento" name="tipoDocumento" class="form-control" value="<?php echo $infoPermiso["tipo_documento"]; ?>" disabled >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="numeroDocumento">Número Documento:</label>
									<input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" value="<?php echo $infoPermiso["numero_documento"]; ?>" disabled >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="firstName">Nombres:</label>
									<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $infoPermiso["nombres"]; ?>" disabled >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="lastName">Apellidos:</label>
									<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $infoPermiso["apellidos"]; ?>" disabled >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="ocupacion">Ocupación:</label>
									<input type="text" class="form-control" id="ocupacion" name="ocupacion" value="<?php echo $infoPermiso["ocupacion"]; ?>" disabled />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="movilNumber">Número Celular:</label>
									<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $infoPermiso["telefono"]; ?>" disabled >
								</div>
							</div>
						</div>
						<br>
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
				<br>
			</div>
		</div>
	</div>
</div>