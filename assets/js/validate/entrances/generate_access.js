$(document).ready(function() {

    $("#btnSubmit").click(function() {
        if ($("#form").valid() == true) {
            $('#btnSubmit').attr('disabled', '-1');
            $('#btnSuccess').attr('disabled', '-1');
            $("#div_error").css("display", "none");
            $("#div_load").css("display", "inline");
            $.ajax({
                type: "POST",
                url: base_url + "entrances/save_access",
                data: $("#form").serialize(),
                dataType: "json",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                cache: false,
                success: function(data) {
                    if (data.result == "error") {
                        $("#div_load").css("display", "none");
                        $("#div_error").css("display", "inline");
                        $("#span_msj").html(data.mensaje);
                        $('#btnSubmit').removeAttr('disabled');
                        $('#btnSuccess').removeAttr('disabled');
                        return false;
                    }
                    if (data.result) {
                        $("#div_load").css("display", "none");
                        $('#btnSubmit').removeAttr('disabled');
                        $('#btnSuccess').removeAttr('disabled');
                        var url = base_url + "entrances/show_qrcode/" + data.permiso;
                        $(location).attr("href", url);
                    } else {
                        alert('Error. Reload the web page.');
                        $("#div_load").css("display", "none");
                        $("#div_error").css("display", "inline");
                        $('#btnSubmit').removeAttr('disabled');
                        $('#btnSuccess').removeAttr('disabled');
                    }
                },
                error: function(result) {
                    alert('Error. Reload the web page.');
                    $("#div_load").css("display", "none");
                    $("#div_error").css("display", "inline");
                    $('#btnSubmit').removeAttr('disabled');
                    $('#btnSuccess').removeAttr('disabled');
                }
            });
        }
    });

    $('#btnSuccess').click(function() {
        var url = base_url + "entrances/searchVisitors";
        $(location).attr("href", url);
    });
});