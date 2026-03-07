<script type="text/javascript" src="<?php echo base_url("assets/js/validate/password.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-user fa-fw"></i> Hola <?php echo $information[0]["first_name"] . ' ' . $information[0]["last_name"]; ?>
					</h4>
				</div>
			</div>
		</div>				
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-unlock"></i> CAMBIAR CONTRASEÑA
				</div>
				<div class="panel-body">
					<form  name="form" id="form" class="form-horizontal" method="post" action="<?php echo base_url("usuarios/updatePassword"); ?>" >
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $information[0]["id_user"]; ?>"/>
						<input type="hidden" id="hddUser" name="hddUser" value="<?php echo $information[0]["log_user"]; ?>"/>
						<div class="form-group">
							<div class="col-lg-12">
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Ingrese su nueva contraseña y confirmela</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="firstName">Nombres: </label>
							<div class="col-sm-5">
								<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $information[0]['first_name']; ?>" readOnly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="lastName">Apellidos: </label>
							<div class="col-sm-5">
								<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $information[0]['last_name']; ?>" readOnly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="username">Usuario: </label>
							<div class="col-sm-5">
								<input type="text" id="user" name="user" class="form-control" value="<?php echo $information[0]['log_user']; ?>" readOnly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="inputPassword">Contraseña: *</label>
							<div class="col-sm-5">
								<input type="password" id="inputPassword" name="inputPassword" class="form-control" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="inputConfirm">Confirmar Contraseña: *</label>
							<div class="col-sm-5">
								<input type="password" id="inputConfirm" name="inputConfirm" class="form-control" >
							</div>
						</div>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
										Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button> 
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>