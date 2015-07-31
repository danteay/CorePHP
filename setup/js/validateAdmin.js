$(document).ready(function(){

    $('#sendAdmin').click(function(){

        //Utilizamos una expresion regular
        var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        //Se utiliza la funcion test() nativa de JavaScript
        if (regex.test($('#correo').val().trim())) {
            if($('#nickname').val() != ''){
                if($('#passwd').val().length >= 8){
                    if($('#passConfirm').val() == $('#passwd').val()){
                        if($('#name').val() != ''){
                            $('#Alert').removeClass('alert');
                            $('#Alert').addClass('success');
                            $('#contentAlert').html('Se esta procesando la informacion.');
                            $('#Alert').css('display','block');
                            document.formLiveAdmin.submit();
                        }else{
                            $('#contentAlert').html('Debes ingresar tu nombre completo.');
                            $('#Alert').css('display','block');
                        }
                    }else{
                        $('#contentAlert').html('Las contraseñas no coinciden.');
                        $('#Alert').css('display','block');
                    }
                }else{
                    $('#contentAlert').html('El password debe tener minimo 8 caracteres.');
                    $('#Alert').css('display','block');
                }
            }else{
                $('#contentAlert').html('El nombre de usuario no puede quedar vacio.');
                $('#Alert').css('display','block');
            }
        }else{
            $('#contentAlert').html('El correo no tiene el formato requerido.');
            $('#Alert').css('display','block');
        }

    });

});
