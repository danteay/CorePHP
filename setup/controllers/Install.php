<?php
require_once("../../core/DirectoryUtils.php");

class Install {

    private $DBO;
    private $configdb;
    private $activePanel;
    private $version;
    private $queryMap;
    private $theme_name;
    private $theme;
    private $viewGenerate;
    private $dir_to_copy;

    public function __construct($configdb = array()){
        $this->configdb = $configdb;
        $this->activePanel = false;
        $this->version = $configdb['version'];
        $this->theme_name = empty($configdb['theme']) ? "Default" : $configdb['theme'];

        if(!empty($configdb['viewGenerate'])){
            $this->viewGenerate = explode("|",$configdb['viewGenerate']);

            foreach($this->viewGenerate as $key => $value){
                $this->viewGenerate[$key] = explode(",",$value);
            }
        }

        $this->queryMap = file_get_contents("../files/queryFile.txt");

        $this->__init__();
    }

    public function __init__(){

        if(!isset($this->configdb['adminTab'])){
            $this->valida();
            $this->setConexion();
        }

        if($this->configdb['panel'] == 1 && !isset($this->configdb['adminTab'])){
            header("Location: ../configPanel.php?db=".$this->configdb['dbas']."&version=".$this->version);
        }elseif(isset($this->configdb['adminTab'])){

            if($this->configdb['adminTab'] == "defaultAdminTable"){
                $this->activePanel = "default";
                $this->createModels();
            }else{
                if(isset($this->configdb['llave1']) && !empty($this->configdb['llave1']) && isset($this->configdb['llave2']) && !empty($this->configdb['llave2'])){
                    $this->activePanel = "custom";
                    $this->createModels();
                }else{
                    header("Location: ../configPanel.php?db=".$this->configdb['dbas']."&tb=".$this->configdb['adminTab']."&theme=".$this->theme_name);
                }
            }


        }else{
            $this->createModels();
        }

    }

    private function setConexion(){
        $conxFile = file_get_contents("../files/DBOMySQL.txt");

        $conxFile = str_replace("{{HOST}}",$this->configdb['host'],$conxFile);
        $conxFile = str_replace("{{USER}}",$this->configdb['user'],$conxFile);
        $conxFile = str_replace("{{PASS}}",$this->configdb['pass'],$conxFile);
        $conxFile = str_replace("{{DBAS}}",$this->configdb['dbas'],$conxFile);

        file_put_contents("../../core/DBOMySQL.php",$conxFile);
        chmod("../../core/DBOMySQL.php",0777);
    }

    private function createModels(){

        $this->theme = new SimpleXMLElement(file_get_contents("../themes/".$this->theme_name."/configTheme.xml"));
        $this->dir_to_copy = "../themes/".$this->theme_name."/directoryToCopy";

        require_once("../../core/DBOMySQL.php");

        $this->DBO = new DBOMySQL();
        $this->DBO->initializeQuery("SHOW TABLES from ".$this->configdb['dbas']);
        $tables = $this->DBO->getRequest();

        if($tables === null){
            header("Location: ../steps.php?error=204&v=3");
        }

        $tables->fetch_array();

        $totalQuery = "";

        $queryModel = $this->version == 1 ? file_get_contents("../files/queryModel.txt") : file_get_contents("../files/queryModel-no-prepare.txt");

        foreach($tables as $value){

            $this->DBO->initializeQuery("show columns from ".$this->configdb['dbas'].".".$value["Tables_in_".$this->configdb['dbas']]);

            if(!preg_match("/^(thecore_)/",$value["Tables_in_".$this->configdb['dbas']])){
                $variables = "";
                $initialize = "";
                $validate = "";
                $leters = "";
                $inserts = "";
                $varQuery = "";
                $insertsQuery = "";
                $primary = "";
                $seters = "";
                $array_setmodel = "array(";
                $array_updatemodel = "array('[[id]]' => \$id, ";

                $flag = true;

                try{
                    $columns = $this->DBO->getRequest();

                    if($columns != null){
                        while($col = $columns->fetch_array(MYSQL_NUM)){
                            $variables .= "private $".$col[0].";\n\t";
                            $initialize .= "\t$"."this->".$col[0]." = null;\n\t";

                            if($col[3] != "PRI"){
                                $leters .= "s";

                                if($flag){
                                    $validate .= $this->createValidate($col[1],$col[0],$col[2]);
                                    $inserts .= "$".$col[0];
                                    $varQuery .= $col[0];
                                    $seters .= $this->version == 1 ? $col[0]." = ?" : $this->createSeters($col[1],$col[0]);
                                    $insertsQuery .= $this->version == 1 ? "?": $this->createInsertsQuery($col[1],$col[0]);
                                    $array_setmodel .= "'[[".$col[0]."]]' => $".$col[0];
                                    $array_updatemodel .= "'[[".$col[0]."]]' => $".$col[0];
                                    $flag = false;
                                }else{
                                    $validate .= " && ".$this->createValidate($col[1],$col[0],$col[2]);
                                    $inserts .= ", $".$col[0];
                                    $varQuery .= ",".$col[0];
                                    $seters .= $this->version == 1 ? ", ".$col[0]." = ?" : ", ".$this->createSeters($col[1],$col[0]);
                                    $insertsQuery .= $this->version == 1 ? ", "."?": ", ".$this->createInsertsQuery($col[1],$col[0]);
                                    $array_setmodel .= ", "."'[[".$col[0]."]]' => $".$col[0];
                                    $array_updatemodel .= ", "."'[[".$col[0]."]]' => $".$col[0];
                                }
                            }else{
                                $primary = $col[0];
                            }
                        }

                        $array_setmodel .= ")";
                        $array_updatemodel .= ")";
                    }
                }catch (Exception $e){
                    echo $e->getTraceAsString();
                }

                switch ($this->activePanel){
                    case "default":
                        if($value["Tables_in_".$this->configdb['dbas']] != "thecore_defaultAdminTable"){
                            $model = $this->version == 1 ? file_get_contents("../files/model.txt") : file_get_contents("../files/model-no-prepare.txt");

                            $model = str_replace("{{model}}",$value["Tables_in_".$this->configdb['dbas']],$model);

                            $model = str_replace("{{variables}}",$variables,$model);
                            $model = str_replace("{{initialize}}",$initialize,$model);
                            $model = str_replace("{{leters}}",$leters,$model);
                            $model = str_replace("{{inserts}}",$inserts,$model);
                            $model = str_replace("{{insertsUpdate}}",$inserts,$model);
                            $model = str_replace("{{validateUpdate}}",$validate,$model);
                            $model = str_replace("{{validate}}",$validate,$model);

                            $model = str_replace("{{array-setmodel}}",$array_setmodel,$model);
                            $model = str_replace("{{array-updatemodel}}",$array_updatemodel,$model);

                            file_put_contents("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",$model);
                            chmod("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",0777);

                            $aux = str_replace("{{table}}",$value["Tables_in_".$this->configdb['dbas']],$queryModel);
                            $aux = str_replace("{{insertsQuery}}",$insertsQuery,$aux);
                            $aux = str_replace("{{varQuery}}",$varQuery,$aux);
                            $aux = str_replace("{{seters}}",$seters,$aux);
                            $aux = str_replace("{{primary}}",$primary,$aux);
                            $aux .= "\n\n";

                            $totalQuery .= $aux;
                        }

                        break;

                    case "custom":
                        if($value["Tables_in_".$this->configdb['dbas']] != $this->configdb['adminTab']){
                            $model = $this->version == 1 ? file_get_contents("../files/model.txt") : file_get_contents("../files/model-no-prepare.txt");

                            $model = str_replace("{{model}}",$value["Tables_in_".$this->configdb['dbas']],$model);

                            $model = str_replace("{{variables}}",$variables,$model);
                            $model = str_replace("{{initialize}}",$initialize,$model);
                            $model = str_replace("{{leters}}",$leters,$model);
                            $model = str_replace("{{inserts}}",$inserts,$model);
                            $model = str_replace("{{insertsUpdate}}",$inserts,$model);
                            $model = str_replace("{{validateUpdate}}",$validate,$model);
                            $model = str_replace("{{validate}}",$validate,$model);

                            $model = str_replace("{{array-setmodel}}",$array_setmodel,$model);
                            $model = str_replace("{{array-updatemodel}}",$array_updatemodel,$model);

                            file_put_contents("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",$model);
                            chmod("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",0777);

                            $aux = str_replace("{{table}}",$value["Tables_in_".$this->configdb['dbas']],$queryModel);
                            $aux = str_replace("{{insertsQuery}}",$insertsQuery,$aux);
                            $aux = str_replace("{{varQuery}}",$varQuery,$aux);
                            $aux = str_replace("{{seters}}",$seters,$aux);
                            $aux = str_replace("{{primary}}",$primary,$aux);
                            $aux .= "\n\n";

                            $totalQuery .= $aux;
                        }else{
                            $this->createBasedAdmin($primary,$initialize,$variables,$validate,$leters,$inserts,$varQuery,$seters,$insertsQuery,$array_setmodel,$array_updatemodel);
                        }

                        break;

                    default:
                        $model = $this->version == 1 ? file_get_contents("../files/model.txt") : file_get_contents("../files/model-no-prepare.txt");

                        $model = str_replace("{{model}}",$value["Tables_in_".$this->configdb['dbas']],$model);

                        $model = str_replace("{{variables}}",$variables,$model);
                        $model = str_replace("{{initialize}}",$initialize,$model);
                        $model = str_replace("{{leters}}",$leters,$model);
                        $model = str_replace("{{inserts}}",$inserts,$model);
                        $model = str_replace("{{insertsUpdate}}",$inserts,$model);
                        $model = str_replace("{{validateUpdate}}",$validate,$model);
                        $model = str_replace("{{validate}}",$validate,$model);

                        $model = str_replace("{{array-setmodel}}",$array_setmodel,$model);
                        $model = str_replace("{{array-updatemodel}}",$array_updatemodel,$model);

                        file_put_contents("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",$model);
                        chmod("../../models/".$value["Tables_in_".$this->configdb['dbas']].".php",0777);

                        $aux = str_replace("{{table}}",$value["Tables_in_".$this->configdb['dbas']],$queryModel);
                        $aux = str_replace("{{insertsQuery}}",$insertsQuery,$aux);
                        $aux = str_replace("{{varQuery}}",$varQuery,$aux);
                        $aux = str_replace("{{seters}}",$seters,$aux);
                        $aux = str_replace("{{primary}}",$primary,$aux);
                        $aux .= "\n\n";

                        $totalQuery .= $aux;
                        break;
                }
            }
        }

        if($this->activePanel == 'default'){
            $this->createDefault();
        }

        $this->queryMap = str_replace("{{querys}}",$totalQuery,$this->queryMap);
        file_put_contents("../../libs/QueryMap.php",$this->queryMap);
        chmod("../../libs/QueryMap.php",0777);


        #------------------------------------------------------------------------------------------
        #----------------------------------- REDIRECCIONES FINALES --------------------------------
        #------------------------------------------------------------------------------------------


        switch($this->activePanel){
            case "default":
                $this->copyThemeFiles();
                $this->copyNavigation();
                header("Location: ../steps.php?error=0&ad=true");
                break;

            case "custom":
                $this->copyThemeFiles();
                $this->copyNavigation();
                header("Location: ../steps.php?error=0&ad=true&custom");
                break;

            default:
                header("Location: ../steps.php?error=0");
                break;
        }
    }

    private function createBasedAdmin(&$primary,&$initialize,&$campos,&$validates,&$params,&$inserts,&$queryInserts,&$updateQueryInserts,&$valueInserts,&$array_setadmin,&$array_updateadmin){
        $this->copyThemeFiles();

        $file = $this->version == 1 ? file_get_contents("../files/adminCustomModel.txt") : file_get_contents("../files/adminCustomModel-no-prepare.txt");

        $file = str_replace("{{campos}}",$campos,$file);
        $file = str_replace("{{initialize}}",$initialize,$file);
        $file = str_replace("{{validates}}",$validates,$file);
        $file = str_replace("{{params}}",$params,$file);
        $file = str_replace("{{inserts}}",$inserts,$file);
        $file = str_replace("{{table}}",$this->configdb['adminTab'],$file);

        $file = str_replace("{{array-setadmin}}",$array_setadmin,$file);
        $file = str_replace("{{array-updateadmin}}",$array_updateadmin,$file);

        file_put_contents("../../models/".$this->configdb['adminTab'].".php",$file);

        $queryLine = $this->version == 1 ? file_get_contents("../files/customQueryAdmin.txt") : file_get_contents("../files/customQueryAdmin-no-prepare.txt");

        $queryLine = str_replace("{{table}}",$this->configdb['adminTab'],$queryLine);
        $queryLine = str_replace("{{llave1}}",$this->configdb['llave1'],$queryLine);
        $queryLine = str_replace("{{llave2}}",$this->configdb['llave2'],$queryLine);
        $queryLine = str_replace("{{primary}}",$primary,$queryLine);
        $queryLine = str_replace("{{queryInserts}}",$queryInserts,$queryLine);
        $queryLine = str_replace("{{valueInserts}}",$valueInserts,$queryLine);
        $queryLine = str_replace("{{queryUpdateInserts}}",$updateQueryInserts,$queryLine);

        $this->queryMap = str_replace("{{adminsection}}",$queryLine,$this->queryMap);
        file_put_contents("../../libs/QueryMap.php",$this->queryMap);

        $login = file_get_contents("../files/loginController.txt");

        $login = str_replace("{{adminmodel}}",$this->configdb['adminTab'],$login);
        $login = str_replace("{{llave1}}",$this->configdb['llave1'],$login);
        $login = str_replace("{{llave2}}",$this->configdb['llave2'],$login);
        $login = str_replace("{{primarykey}}",$primary,$login);

        file_put_contents("../../controllers/loginController.php",$login);

        $list = $this->createListHome();

        $home = file_get_contents($this->theme->views->homeview['structure']);
        $home = str_replace("{{reports}}",$list,$home);
        file_put_contents("../../views/home.php",$home);



        copy($this->theme->views->indexview['structure'],"../../index.php");

    }

    private function createDefault(){

        $query = "DROP TABLE thecore_defaultAdminTable";

        $this->DBO->initializeQuery($query);
        try{
            $this->DBO->setRequest();
        }catch (Exception $e){
            echo $e->getTraceAsString();
        }

        $query = "create table thecore_defaultAdminTable(
                                idAdmin integer not null auto_increment,
                                nickname varchar(50) not null,
                                passwd varchar(300) not null,
                                primary key(idAdmin)
                            )engine = InnODB;";

        $this->DBO->initializeQuery($query);
        try{
            $this->DBO->setRequest();
        }catch (Exception $e){
            echo $e->getTraceAsString();
        }

        $query = "INSERT INTO thecore_defaultAdminTable (nickname, passwd) VALUES ('root','root')";

        $this->DBO->initializeQuery($query);
        try{
            $this->DBO->setRequest();
        }catch (Exception $e){
            echo $e->getTraceAsString();
        }

        $class = file_get_contents("../files/adminModel.txt");
        file_put_contents("../../models/thecore_defaultAdminTable.php",$class);
        chmod("../../models/thecore_defaultAdminTable.php",0777);

        $querys = $this->version == 1 ? file_get_contents("../files/queryAdmin.txt") : file_get_contents("../files/queryAdmin-no-prepare.txt");

        $this->queryMap = str_replace("{{adminsection}}",$querys,$this->queryMap);

        $this->generateDefaultLogin();
        $list = $this->createListHome();

        $home = file_get_contents($this->theme->views->homeview['structure']);
        $home = str_replace("{{reports}}",$list,$home);
        file_put_contents("../../views/home.php",$home);


    }

    private function generateDefaultLogin(){

        $login = file_get_contents("../files/loginController.txt");
        $login = str_replace("{{llave1}}","nickname",$login);
        $login = str_replace("{{llave2}}","passwd",$login);
        $login = str_replace("{{primarykey}}","idAdmin",$login);
        $login = str_replace("{{adminmodel}}","thecore_defaultAdminTable",$login);

        file_put_contents("../../controllers/loginController.php",$login);
        copy($this->theme->views->indexview['structure'],"../../index.php");
    }


    #-------------------------------------------------------------------------------------------------------------------
    #                                               CREACION DE VISTAS
    #-------------------------------------------------------------------------------------------------------------------


    private function createListHome(){

        echo "<pre>";
        var_dump($this->viewGenerate);
        echo "</pre>";

        $this->DBO->initializeQuery("SHOW TABLES FROM ".$this->configdb['dbas']);
        $list = '';
        $flag = true;
        $cont = 0;

        $structure = file_get_contents($this->theme->views->homeview->listNavigation['structure']);

        try{
            $tables = $this->DBO->getRequest();

            foreach($tables as $value){
                if(!preg_match("/^(thecore_)/",$value['Tables_in_'.$this->configdb['dbas']])){
                    if($this->viewGenerate[$cont][0] == "true" || $this->activePanel == 'default'){

                        if($flag){
                            $list .= str_replace("{{tabla}}",$value['Tables_in_'.$this->configdb['dbas']],$structure)."\n";
                            $flag = false;
                        }else{
                            $list .= "                            ".str_replace("{{tabla}}",$value['Tables_in_'.$this->configdb['dbas']],$structure)."\n";
                        }

                        $this->createReports($value['Tables_in_'.$this->configdb['dbas']],$cont);

                    }

                    if($this->viewGenerate[$cont][1] == "true" || $this->activePanel == 'default'){

                        $this->createAdds($value['Tables_in_'.$this->configdb['dbas']]);
                        $this->createAddControllers($value['Tables_in_'.$this->configdb['dbas']]);

                    }

                    if($this->viewGenerate[$cont][2] == "true" || $this->activePanel == 'default'){

                        $this->createEdits($value['Tables_in_'.$this->configdb['dbas']]);
                        $this->createDeleteControllers($value['Tables_in_'.$this->configdb['dbas']]);
                        $this->createUpdateControllers($value['Tables_in_'.$this->configdb['dbas']]);

                    }

                    $cont++;
                }
            }

            return $list;
        }catch (Exception $e){
            echo $e->getTraceAsString();
            return false;
        }

    }

    private function createUpdateControllers($tabla = null){
        if(!empty($tabla)){
            $file = file_get_contents("../files/updateController.txt");

            $file = str_replace("{{tabla}}",$tabla,$file);

            file_put_contents("../../controllers/update".$tabla."Controller.php",$file);
        }
    }

    private function createReports($table, $contindex){
        if(!empty($table)){
            $file = file_get_contents($this->theme->views->reportview['structure']);

            $boton = file_get_contents($this->theme->views->reportview->editbutton['structure']);
            $header = file_get_contents($this->theme->views->reportview->editbutton['header']);

            $boton = str_replace("{{tabla}}",$table,$boton);

            if($this->viewGenerate[$contindex][2] == "true"){
                $file = str_replace("{{editHeader}}",$header,$file);
                $file = str_replace("{{reportEditButton}}",$boton,$file);
            }else{
                $file = str_replace("{{editHeader}}","",$file);
                $file = str_replace("{{reportEditButton}}","",$file);
            }

            $file = str_replace("{{tabla}}",$table,$file);

            file_put_contents("../../views/report".$table.".php",$file);
        }
    }

    private function createAddControllers($table = null){
        if(!empty($table)){
            $file = file_get_contents("../files/addController.txt");

            $file = str_replace("{{tabla}}",$table,$file);

            file_put_contents("../../controllers/add".$table."Controller.php",$file);
        }
    }

    private function createAdds($table = null){
        if(!empty($table)){
            $campos = "";

            $date_plugin = "<link rel='stylesheet' href='../js/date-picker/public/css/bootstrap.css'/>\n\t\t<script src='../js/date-picker/public/javascript/zebra_datepicker.js'></script>";
            $time_plugin = "<link rel='stylesheet' href='../js/time-picker/jquery.timepicker.css'/>\n\t\t<script src='../js/time-picker/jquery.timepicker.min.js'></script>";

            $time_actives = "";
            $date_actives = "";

            $flag_date = false;
            $flag_time = false;

            $date = file_get_contents($this->theme->views->addview->dateinput['structure']);
            $time = file_get_contents($this->theme->views->addview->timeinput['structure']);
            $text = file_get_contents($this->theme->views->addview->textarea['structure']);
            $default = file_get_contents($this->theme->views->addview->defaultinput['structure']);

            $this->DBO->initializeQuery("SHOW COLUMNS FROM ".$table);
            try{
                $data = $this->DBO->getRequest();

                if($data != null){
                    while($fila = $data->fetch_array(MYSQLI_NUM)){
                        if($fila[3] != 'PRI'){
                            switch($fila[1]){
                                case 'date':
                                    $campos .= str_replace("{{fila}}",$fila[0],$date);

                                    $date_actives .= "\n$('#".$fila[0]."').Zebra_DatePicker();";
                                    $flag_date = true;
                                    break;

                                case 'time':
                                    $campos .= str_replace("{{fila}}",$fila[0],$time);

                                    $time_actives .= "\n$('#".$fila[0]."').timepicker({ 'timeFormat': 'H:i:s' });";
                                    $flag_time = true;
                                    break;

                                case 'text':
                                    $campos .= str_replace("{{fila}}",$fila[0],$text);
                                    break;

                                default:
                                    $campos .= str_replace("{{fila}}",$fila[0],$default);
                                    break;
                            }
                        }
                    }

                    $add = file_get_contents($this->theme->views->addview['structure']);
                    $add = str_replace("{{tabla}}",$table,$add);
                    $add = str_replace("{{campos}}",$campos,$add);

                    if($flag_date){
                        $add = str_replace("<!--date_plugin-->",$date_plugin,$add);
                        $add = str_replace("<!--date_actives-->",$date_actives,$add);
                    }

                    if($flag_time){
                        $add = str_replace("<!--time_plugin-->",$time_plugin,$add);
                        $add = str_replace("<!--time_actives-->",$time_actives,$add);
                    }

                    file_put_contents("../../views/add".$table.".php",$add);
                }
            }catch (Exception $e){
                echo $e->getTraceAsString();
            }
        }
    }

    private function createEdits($table = null){
        if(!empty($table)){
            $campos = "";

            $date_plugin = "<link rel='stylesheet' href='../js/date-picker/public/css/bootstrap.css'/>\n\t\t<script src='../js/date-picker/public/javascript/zebra_datepicker.js'></script>";
            $time_plugin = "<link rel='stylesheet' href='../js/time-picker/jquery.timepicker.css'/>\n\t\t<script src='../js/time-picker/jquery.timepicker.min.js'></script>";

            $time_actives = "";
            $date_actives = "";

            $flag_date = false;
            $flag_time = false;

            $date = file_get_contents($this->theme->views->editview->dateinput['structure']);
            $time = file_get_contents($this->theme->views->editview->timeinput['structure']);
            $text = file_get_contents($this->theme->views->editview->textarea['structure']);
            $default = file_get_contents($this->theme->views->editview->defaultinput['structure']);

            $this->DBO->initializeQuery("SHOW COLUMNS FROM ".$table);
            try{
                $data = $this->DBO->getRequest();

                if($data != false){
                    while($fila = $data->fetch_array(MYSQLI_NUM)){
                        if($fila[3] != 'PRI'){
                            switch($fila[1]){
                                case 'date':
                                    $campos .= str_replace("{{fila}}",$fila[0],$date);

                                    $date_actives .= "\n$('#".$fila[0]."').Zebra_DatePicker();";
                                    $flag_date = true;
                                    break;

                                case 'time':
                                    $campos .= str_replace("{{fila}}",$fila[0],$time);

                                    $time_actives .= "\n$('#".$fila[0]."').timepicker({ 'timeFormat': 'H:i:s' });";
                                    $flag_time = true;
                                    break;

                                case 'text':
                                    $campos .= str_replace("{{fila}}",$fila[0],$text);
                                    break;

                                default:
                                    $campos .= str_replace("{{fila}}",$fila[0],$default);
                                    break;
                            }
                        }
                    }

                    $add = file_get_contents($this->theme->views->editview['structure']);
                    $add = str_replace("{{tabla}}",$table,$add);
                    $add = str_replace("{{campos}}",$campos,$add);

                    if($flag_date){
                        $add = str_replace("<!--date_plugin-->",$date_plugin,$add);
                        $add = str_replace("<!--date_actives-->",$date_actives,$add);
                    }

                    if($flag_time){
                        $add = str_replace("<!--time_plugin-->",$time_plugin,$add);
                        $add = str_replace("<!--time_actives-->",$time_actives,$add);
                    }

                    file_put_contents("../../views/edit".$table.".php",$add);
                }
            }catch (Exception $e){
                echo $e->getTraceAsString();
            }
        }
    }

    private function copyThemeFiles(){
        $obj_depend = new DirectoryUtils();
        $list_file_dependencies = $obj_depend->ListFiles($this->dir_to_copy);

        foreach($list_file_dependencies as $value){
            $obj_dir = new DirectoryUtils();
            $obj_dir->fullCopy($this->dir_to_copy."/".$value,"../../".$value);
        }
    }

    public function copyNavigation(){
        copy($this->theme->general->navigation['structure'],"../../libs/navigation.php");
    }

    private function createDeleteControllers($table = null){
        if(!empty($table)){
            $file = file_get_contents("../files/deleteController.txt");

            $file = str_replace("{{table}}",$table,$file);

            file_put_contents("../../controllers/delete".$table."Controller.php",$file);
        }
    }

    private function createValidate($case,$var,$nulable){
        if(preg_match("/int/",$case) || preg_match("/integer/",$case) || preg_match("/smallint/",$case) || preg_match("/tinyint/",$case) || preg_match("/decimal/",$case) || preg_match("/dec/",$case) || preg_match("/numeric/",$case) || preg_match("/mediumint/",$case) || preg_match("/bigint/",$case) || preg_match("/real/",$case) || preg_match("/float/",$case) || preg_match("/double/",$case) || preg_match("/precision/",$case)){
            return "isset($".$var.") && is_numeric($".$var.")";
        }else{
            return $nulable == "NO" ? "isset($".$var.") && !empty($".$var.")" : "isset($".$var.")";
        }
    }

    private function createInsertsQuery($case,$var){
        if(preg_match("/int/",$case) || preg_match("/integer/",$case) || preg_match("/smallint/",$case) || preg_match("/tinyint/",$case) || preg_match("/decimal/",$case) || preg_match("/dec/",$case) || preg_match("/numeric/",$case) || preg_match("/mediumint/",$case) || preg_match("/bigint/",$case) || preg_match("/real/",$case) || preg_match("/float/",$case) || preg_match("/double/",$case) || preg_match("/precision/",$case)){
            return "[[".$var."]]";
        }else{
            return "'[[".$var."]]'";
        }
    }

    private function createSeters($case,$var){
        if(preg_match("/int/",$case) || preg_match("/integer/",$case) || preg_match("/smallint/",$case) || preg_match("/tinyint/",$case) || preg_match("/decimal/",$case) || preg_match("/dec/",$case) || preg_match("/numeric/",$case) || preg_match("/mediumint/",$case) || preg_match("/bigint/",$case) || preg_match("/real/",$case) || preg_match("/float/",$case) || preg_match("/double/",$case) || preg_match("/precision/",$case)){
            return $var." = [[".$var."]]";
        }else{
            return $var." = '[[".$var."]]'";
        }
    }

    private function valida(){
        if(empty($this->configdb['host'])){
            header("Location: ../steps.php?error=204&v=1");
        }elseif(empty($this->configdb['user'])){
            header("Location: ../steps.php?error=204&v=2");
        }elseif(empty($this->configdb['dbas'])){
            header("Location: ../steps.php?error=204&v=4");
        }
    }

}

if($_POST){
    $obj =  new Install($_POST);
}else{
    header("Location: ../steps.php?error=201");
}
