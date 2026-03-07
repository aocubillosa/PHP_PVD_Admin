<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<?php echo $titulo; ?>
					</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $titulo; ?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<div class="alert <?php echo $clase;?>"> <span class="glyphicon glyphicon-ok">&nbsp;</span>
										<?php echo $msj; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<?php if($linkBack){ ?>
									<a class="btn btn-success" href=" <?php echo base_url($linkBack); ?> "><span class="fa fa-undo" aria-hidden="true"></span> Regresar </a> 
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>