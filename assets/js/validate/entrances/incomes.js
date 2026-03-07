$(document).ready( function() {

	$(".btn-success").click(function () {
		var oID = $(this).attr("id");
		Swal.fire({
			title: "Registrar Ingreso",
            text: "¿Desea registrar el ingreso del visitante?",
            icon: "success",
            confirmButtonText: "Confirmar",
            showCancelButton: true,
            cancelButtonColor: "#DD6B55",
            allowOutsideClick: false,
			allowEscapeKey: false
		}).then((result) => {
			if (result.isConfirmed) {
				$(".btn-success").attr('disabled','-1');
				$(".btn-warning").attr('disabled','-1');
				$.ajax ({
					type: 'POST',
					url: base_url + 'entrances/checkIn',
					data: {'identificador': oID},
					cache: false,
					success: function(data){
						if(data.result == "error")
						{
							alert(data.mensaje);
							$(".btn-success").removeAttr('disabled');
							$(".btn-warning").removeAttr('disabled');
							return false;
						} 
						if(data.result)
						{
							$(".btn-success").removeAttr('disabled');
							$(".btn-warning").removeAttr('disabled');
							var url = base_url + "entrances/incomesVisitantes";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$(".btn-success").removeAttr('disabled');
							$(".btn-warning").removeAttr('disabled');
						}
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".btn-success").removeAttr('disabled');
						$(".btn-warning").removeAttr('disabled');
					}
				});
			}
		});
	});
});