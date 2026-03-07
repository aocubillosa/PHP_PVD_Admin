$(document).ready(function() {

	$("#form_descarga").validate( {
		rules: {
			from: 		{ required: true },
			to: 		{ required: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			error.addClass( "help-block" );
			error.insertAfter( element );
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-6" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnXLS").click(function(){
		if ($("#form_descarga").valid() == true) {
            var form = document.getElementById('form_descarga');
            form.action = base_url + "reportes/generarReporteIngresosXLS";
            form.submit();
        }
	});

	$("#btnPDF").click(function(){
		if ($("#form_descarga").valid() == true) {
            var form = document.getElementById('form_descarga');
            form.action = base_url + "reportes/generarReporteIngresosPDF";
            form.submit();
        }
	});
});