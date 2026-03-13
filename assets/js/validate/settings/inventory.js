$(document).ready( function() {

    $(function() {
        var rangoFechas = $('#fecha_ingreso, #fecha_servicio').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            maxDate: 0,
            onSelect: function (selectedDate) {
                var option = this.id == 'fecha_ingreso' ? 'minDate' : 'maxDate',
                    instance = $(this).data('datepicker');
                date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                rangoFechas.not(this).datepicker('option', option, date);
            }
        });
    });

    $("#descripcion").bloquearNumeros().maxlength(50);
    $("#valor").bloquearTexto().maxlength(10);
    $("#placa").bloquearTexto().maxlength(10);
    
    $("#form").validate( {
        rules: {
            elemento:           { required: true },
            descripcion:        { minlength: 3, maxlength:50 },
            marca:              { required: true },
            placa:              { required: true, maxlength:10 },
            fecha_ingreso:      { required: true },
            valor:              { required: true, maxlength:10 },
            estado:             { required: true }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            error.addClass( "help-block" );
            error.insertAfter( element );
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-4" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-4" ).addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function (form) {
            return true;
        }
    });
    
    $("#btnSubmit").click(function(){
        if ($("#form").valid() == true){
            $('#btnSubmit').attr('disabled','-1');
            $("#div_error").css("display", "none");
            $("#div_load").css("display", "inline");
            $.ajax({
                type: "POST",
                url: base_url + "settings/save_inventory",
                data: $("#form").serialize(),
                dataType: "json",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                cache: false,
                success: function(data){
                    if(data.result == "error")
                    {
                        $("#div_load").css("display", "none");
                        $("#div_error").css("display", "inline");
                        $("#span_msj").html(data.mensaje);
                        $('#btnSubmit').removeAttr('disabled');
                        return false;
                    } 
                    if(data.result)
                    {
                        $("#div_load").css("display", "none");
                        $('#btnSubmit').removeAttr('disabled');
                        var url = base_url + "settings/inventory";
                        $(location).attr("href", url);
                    }
                    else
                    {
                        alert('Error. Reload the web page.');
                        $("#div_load").css("display", "none");
                        $("#div_error").css("display", "inline");
                        $('#btnSubmit').removeAttr('disabled');
                    }   
                },
                error: function(result) {
                    alert('Error. Reload the web page.');
                    $("#div_load").css("display", "none");
                    $("#div_error").css("display", "inline");
                    $('#btnSubmit').removeAttr('disabled');
                }
            });
        }
    });
});

$(function(){ 
	$(".btn-success").click(function () {
		var oID = $(this).attr("id");
        $.ajax ({
            type: 'POST',
			url: base_url + '/settings/cargarModalInventory',
            data: {'idInventario': oID},
            cache: false,
            success: function (data) {
                $('#tablaDatos').html(data);
            }
        });
	});	
});