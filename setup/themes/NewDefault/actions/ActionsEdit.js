var Actions = {
    __init__: function(){
        document.querySelector("#send-editform").onclick = Actions.editFormClick;
    },
        
    editFormClick: function(){
        if(Actions.validateData()){
            document.editform.submit();
        }
    },
    
    validateData(){
        var campo1 = document.querySelector("#campo1");
        var campo2 = document.querySelector("#campo2");
        var campo3 = document.querySelector("#campo3");
        var campo4 = document.querySelector("#campo4");
        
        var flag = true;
        var textError = "";
        
        if(campo1.value == "" || isNaN(campo1.value)){
            flag = false;
            textError = "El campo 'campo1' esta vacio o es incorrecto";
        }else if(campo2.value == "" || isNaN(campo2.value)){
            flag = false;
            textError = "El campo 'campo2' esta vacio o es incorrecto";
        }else if(campo3.value == "" || isNaN(campo3.value)){
            flag = false;
            textError = "El campo 'campo3' esta vacio o es incorrecto";
        }else if(campo4.value == ""){
            flag = false;
            textError = "El campo 'campo4' esta vacio o es incorrecto";
        }
        
        if(flag){
            return true;
        }else{
            document.querySelector("#textError").innerHTML = textError;
            $('#error-modal').foundation('reveal', 'open');
            return false
        }
    }
}

document.onreadystatechange = Actions.__init__;