$(document).ready( function() {
	
	$(".btn-danger").click(function () {
		var oID = $(this).attr("id");
		Swal.fire({
			title: "Cancelar Permiso",
            text: "¿Está seguro de cancelar el permiso?",
            icon: "warning",
            confirmButtonText: "Confirmar",
            showCancelButton: true,
            cancelButtonColor: "#DD6B55",
            allowOutsideClick: false,
			allowEscapeKey: false
		}).then((result) => {
			if (result.isConfirmed) {
				$(".btn-danger").attr('disabled','-1');
				$(".btn-info").attr('disabled','-1');
				$.ajax ({
					type: 'POST',
					url: base_url + 'entrances/deletePermissions',
					data: {'identificador': oID},
					cache: false,
					success: function(data){
						if(data.result == "error")
						{
							alert(data.mensaje);
							$(".btn-danger").removeAttr('disabled');
							$(".btn-info").removeAttr('disabled');
							return false;
						} 
						if(data.result)
						{
							$(".btn-danger").removeAttr('disabled');
							$(".btn-info").removeAttr('disabled');
							var url = base_url + "entrances/permissionsVisitantes";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$(".btn-danger").removeAttr('disabled');
							$(".btn-info").removeAttr('disabled');
						}
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".btn-danger").removeAttr('disabled');
						$(".btn-info").removeAttr('disabled');
					}
				});
			}
		});
	});
});

$(function(){
	$(".btn-info").click(function () {
		var oID = $(this).attr("id");
        $.ajax ({
            type: 'POST',
			url: base_url + 'entrances/cargarModalPermisos',
            data: {'idPermiso': oID},
            cache: false,
            success: function (data) {
                $('#tablaDatos').html(data);
            }
        });
	});
});