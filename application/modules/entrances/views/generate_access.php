<script type="text/javascript" src="<?php echo base_url("assets/js/validate/entrances/generate_access.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-qrcode"></i> GENERAR ACCESO VISITANTE
					</h4>
				</div>
				<br>
				<div class="panel-body">
					<form name="form" id="form" role="form" method="post" >
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $infoVisitors[0]["id_visitante"]; ?>"/>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="tipoDocumento">Tipo Documento:</label>
									<input type="text" id="tipoDocumento" name="tipoDocumento" class="form-control" value="<?php echo $infoVisitors[0]["tipo_documento"]; ?>" readOnly >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="numeroDocumento">Número Documento:</label>
									<input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" value="<?php echo $infoVisitors[0]["numero_documento"]; ?>" readOnly >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="firstName">Nombres:</label>
									<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $infoVisitors[0]["nombres"]; ?>" readOnly >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="lastName">Apellidos:</label>
									<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $infoVisitors[0]["apellidos"]; ?>" readOnly >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="ocupacion">Ocupación:</label>
									<input type="text" class="form-control" id="ocupacion" name="ocupacion" value="<?php echo $infoVisitors[0]["ocupacion"]; ?>" readOnly />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group text-left">
									<label class="control-label" for="movilNumber">Número Celular:</label>
									<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $infoVisitors[0]["telefono"]; ?>" readOnly >
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
										Generar QR <span class="fa fa-qrcode" aria-hidden="true">
									</button>&nbsp;
					                <button type="button" class="btn btn-success" id="btnSuccess" name="btnSuccess">
									    Regresar <span class="fa fa-undo" aria-hidden="true"></span>
									</button>
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