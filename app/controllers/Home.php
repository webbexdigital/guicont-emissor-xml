<?php

namespace app\controllers;

class Home {
    
    public function index($params){
        return [
            'view' => 'home.php',
            'data' => $params
        ];
    }
    
}