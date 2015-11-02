var Actions = {
    __init__: function(){
        document.querySelector("#send-editform").onclick = Actions.trigger;
    },

    trigger: function(){
        if(Actions.validate()){
            document.editform.submit();
        }
    },

    validate: function(){
        /*fields*/

        var textError = "";
        var flag = true;

        /*validate*/

        document.querySelector("#textError").innerHTML = textError;

        if(flag){
            return true;
        }else{
            /*actcode*/
            return false;
        }
    }
};

document.onreadystatechange = Actions.__init__;