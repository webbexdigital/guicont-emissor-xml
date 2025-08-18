<?php

namespace app\controllers;

class Cadastro {
    
    public function index($params){
        return [
            'view' => 'cadastro.php',
            'data' => ['title' => 'Cadastro']
        ];
    }
    
}