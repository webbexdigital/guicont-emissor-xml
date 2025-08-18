<?php

namespace app\controllers;

class Login {
    
    public function index($params){
        return [
            'view' => 'login.php',
            'data' => ['title' => 'Login']
        ];
    }
    
}