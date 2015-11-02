<?php

require_once("GeneralValidations.php");

class JsActionsValidate extends GeneralValidations
{

    private $plugin;
    private $type;
    private $table;
    private $conx;
    private $path;

    public function __construct($conx,$path){
        $this->plugin = null;
        $this->type = "";
        $this->table = null;
        $this->conx = $conx;
        $this->path = $path;
    }

    public function createValidates($plugin,$table,$type){

        if(!empty($plugin) && !empty($table) && !empty($type)){
            $this->plugin = $plugin;
            $this->type = $type;
            $this->table = $table;

            $this->type = strtoupper($this->type);

            switch($this->type){
                case "ADD":
                    $this->addValidates();
                    break;

                case "EDIT":
                    $this->editValidates();
                    break;

                default:
                    throw new Exception("Error: No se reconoce el tipo de validacion '".$this->type."'");
                    break;
            }
        }else{
            throw new Exception("Error: Parametros invalidos en la construccion de las validaciones.");
        }
    }

    private function addValidates(){
        require_once("../../core/DBOMySQL.php");

        $view = file_get_contents("../../views/add".$this->table.".php");
        $actcode = file_get_contents($this->path.$this->plugin->activation['actcode']);
        $validate_file = file_get_contents($this->path.$this->plugin->addmodal['validate']);

        $plugin_structure = file_get_contents($this->path.$this->plugin['structure']);
        $plugin_css = is_file($this->path.$this->plugin['css']) ? file_get_contents($this->path.$this->plugin['css']) : "";
        $plugin_js = is_file($this->path.$this->plugin['js']) ? file_get_contents($this->path.$this->plugin['js']) : "";

        $tag_add = file_get_contents($this->path.$this->plugin->addmodal['js']);

        $fields = "";
        $validate = "";

        $flag = true;

        $this->conx->initializeQuery("SHOW COLUMNS FROM ".$this->table);

        $columns = $this->conx->getRequest();

        while($fila = $columns->fetch_array(MYSQL_NUM)){

            if($fila[3] != "PRI") {
                $fields .= "var " . $fila[0] . " = document.querySelector('#" . $fila[0] . "');\n";

                if ($flag) {
                    $flag = false;

                    $validate .= "if(" . $this->createValidateFields($fila[0], $fila[1]) . "){
                    textError = 'El campo \"" . $fila[0] . "\" esta vacio o es incorrecto.';
                    flag = false;
                }";
                } else {
                    $validate .= "else if(" . $this->createValidateFields($fila[0], $fila[1]) . "){
                    textError = 'El campo \"" . $fila[0] . "\" esta vacio o es incorrecto.';
                    flag = false;
                }";
                }
            }
        }

        $validate_file = str_replace("/*fields*/",$fields,$validate_file);
        $validate_file = str_replace("/*validate*/",$validate,$validate_file);
        $validate_file = str_replace("/*actcode*/",$actcode,$validate_file);

        file_put_contents("../../".$this->plugin->addmodal['folder']."/actionsAdd".$this->table."Validate.js",$validate_file);

        $tag_add = str_replace("{{folder}}",$this->plugin->addmodal['folder'],$tag_add);
        $tag_add = str_replace("{{table}}",$this->table,$tag_add);

        $view = str_replace("<!--modal_structure-->",$plugin_structure,$view);
        $view = str_replace("<!--modal_plugin_js-->",$plugin_js,$view);
        $view = str_replace("<!--modal_plugin_css-->",$plugin_css,$view);
        $view = str_replace("<!--jsvalidation_addplugin-->",$tag_add,$view);

        file_put_contents("../../views/add".$this->table.".php",$view);
    }


    private function editValidates(){
        require_once("../../core/DBOMySQL.php");
        $view = file_get_contents("../../views/edit".$this->table.".php");
        $actcode = file_get_contents($this->path.$this->plugin->activation['actcode']);
        $validate_file = file_get_contents($this->path.$this->plugin->editmodal['validate']);

        $plugin_structure = file_get_contents($this->path.$this->plugin['structure']);
        $plugin_css = is_file($this->path.$this->plugin['css']) ? file_get_contents($this->path.$this->plugin['css']) : "";
        $plugin_js = is_file($this->path.$this->plugin['js']) ? file_get_contents($this->path.$this->plugin['js']) : "";

        $tag_add = file_get_contents($this->path.$this->plugin->editmodal['js']);

        $fields = "";
        $validate = "";

        $flag = true;

        $this->conx->initializeQuery("SHOW COLUMNS FROM ".$this->table);

        $columns = $this->conx->getRequest();

        while($fila = $columns->fetch_array(MYSQL_NUM)){

            if($fila[3] != "PRI"){
                $fields .= "var ".$fila[0]." = document.querySelector('#".$fila[0]."');\n";

                if($flag){
                    $flag = false;

                    $validate .= "if(".$this->createValidateFields($fila[0],$fila[1])."){
                    textError = 'El campo \"".$fila[0]."\" esta vacio o es incorrecto.';
                    flag = false;
                }";
                }else{
                    $validate .= "else if(".$this->createValidateFields($fila[0],$fila[1])."){
                    textError = 'El campo \"".$fila[0]."\" esta vacio o es incorrecto.';
                    flag = false;
                }";
                }
            }

        }

        $validate_file = str_replace("/*fields*/",$fields,$validate_file);
        $validate_file = str_replace("/*validate*/",$validate,$validate_file);
        $validate_file = str_replace("/*actcode*/",$actcode,$validate_file);

        file_put_contents("../../".$this->plugin->editmodal['folder']."/actionsEdit".$this->table."Validate.js",$validate_file);

        $tag_add = str_replace("{{folder}}",$this->plugin->editmodal['folder'],$tag_add);
        $tag_add = str_replace("{{table}}",$this->table,$tag_add);

        $view = str_replace("<!--modal_structure-->",$plugin_structure,$view);
        $view = str_replace("<!--modal_plugin_js-->",$plugin_js,$view);
        $view = str_replace("<!--modal_plugin_css-->",$plugin_css,$view);
        $view = str_replace("<!--jsvalidation_editplugin-->",$tag_add,$view);

        file_put_contents("../../views/edit".$this->table.".php",$view);

    }


    private function createValidateFields($field,$type){
        if($this->isNumeric($type)){
            return " ".$field.".value == '' || isNaN(".$field.".value) ";
        }else{
            return " ".$field.".value == '' ";
        }
    }

}