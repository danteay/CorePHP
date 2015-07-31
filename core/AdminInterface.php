<?php
/**
 * Created by PhpStorm.
 * User: DANTE
 * Date: 21/10/2014
 * Time: 12:16 PM
 */

interface AdminInterface {

    public function getByUser($user);
    public function getByPass($pass);
    public function getItem($id);
    public function getAllItems();
    public function setItem($data = array());
    public function dropItem($id);
    public function getColumns();
    public function getFilter($filter,$patern);
    public function updateItem($id,$data = array());
} 