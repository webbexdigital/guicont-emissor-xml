<?php

function all($table, $fields = '*'){
    try{
        $conn = connect();
        $query = $conn->query("select $fields from $table");
        return $query->fetchAll();
    }catch(PDOException $e){
        var_dump($e->getMessage());
    }
}