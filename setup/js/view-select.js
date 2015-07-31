$(document).ready(function(){

    $("#finish").click(function(){

        var totalviews = $("#total-views").val();
        var string = "";
        var flag = true;

        for(var x = 0; x <= totalviews; x++){
            var report = $("#view-report-"+x).is(':checked') ? "true" : "false";
            var add = $("#view-add-"+x).is(':checked') ? "true" : "false";
            var edit = $("#view-edit-"+x).is(':checked') ? "true" : "false";

            if(flag){
                string += report+","+add+","+edit;
                flag = false;
            }else{
                string += "|"+report+","+add+","+edit;
            }
        }

        $("#viewGenerate").val(string);

        document.setInstallFinish.submit();

    });

});
