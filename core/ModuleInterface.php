<?php

/*
 * Este archivo esel modelo del cual se crean las coberturas para la base de datos
 * (las coberturas son creadas al momento de la instalacion y puestas en la carpeta models)
 */

interface ModuleInterface{

    public function getItem($id);
    public function setItem($data=array());
    public function getAllItems();
    public function dropItem($id);
    public function updateItem($id,$data = array());
    public function getColumns();
    public function getFilter($filter,$patern);

}