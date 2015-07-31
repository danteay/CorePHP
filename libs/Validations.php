<?php

class Validations {

    /**
     * @param $sess = 'admin' - Nombre de la session. Valor por defecto 'admin'.
     *
     * Función:
     *      Valida la existencia de la sesión de administrador.
     *      En caso de no existir la sesión se redirecciona a la pantalla de login.
     * Prototipo:
     *      void function sessionValidate();
     */
    public function sessionValidate($sess = "admin"){
        require_once("../core/SessionUtils.php");

        $session = new SessionUtils();

        if(!isset($_SESSION[$sess]) || $_SESSION[$sess] == null){
            header("Location: ../steps.php");
        }
    }

    /**
     * @param $sess = 'admin' - Nombre de la session. Valor por defecto 'admin'.
     *
     * @return boolean
     *
     * Función:
     *      Valida la existencia de la sesión de administrador.
     *      En caso de no existir la sesión se redirecciona a la pantalla de login.
     * Prototipo:
     *      void function sessionValidate();
     */
    public function sessionValidateUser($sess = "user"){
        require_once("../core/SessionUtils.php");

        $session = new SessionUtils();

        if(!isset($_SESSION[$sess]) || $_SESSION[$sess] == null){
            return false;
        }else{
            return true;
        }
    }


    /**
     * @param $instance - objeto del modelo correspondiente a la tabla de la cual se editara el registro
     *
     * Funcion:
     *      Valida la existencia del parametro edit, el cual indica que elemento se modificara.
     *      En caso de no cumplir los requisistos es redireccionado la pantalla de Home.
     * Prototipo:
     *      void function editItemValidate();
     */
    public function editItemValidate(&$instance){
        if(!(isset($_GET['edit']) && is_numeric($_GET['edit']))){
            header("Location: home.php");
        }else{
            try{
                $instance->getItem($_GET['edit']);
            }catch (Exception $e) {
                echo $e->getTraceAsString();
            }
        }
    }
} 