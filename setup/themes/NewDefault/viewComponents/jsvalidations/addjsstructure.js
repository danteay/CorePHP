var Actions = {
    __init__: function(){
        document.querySelector("#send-addform").onclick = Actions.trigger;
    },

    trigger: function(){
        if(Actions.validate()){
            document.addform.submit();
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