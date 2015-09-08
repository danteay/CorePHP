<?php
require_once("DBOMySQL.php");

$conx = new DBOMySQL();

$conx->initializeQuery("SHOW COLUMNS FROM test.admin");

$result = $conx->getRequest();

while($fila = $result->fetch_array(MYSQL_NUM)){
    echo "<pre>";
    print_r($fila);
    echo "</pre>";
}