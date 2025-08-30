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

function userFindBy($field, $value, $fields = '*'){
    try{
        $conn = connect();
        $prepare = $conn->prepare("select $fields from user where $field = :$field");
        $prepare->execute([$field => $value]);
        return $prepare->fetch();
    }catch(PDOException $e){
        var_dump($e->getMessage());
    }
}