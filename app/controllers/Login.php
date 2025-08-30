<?php

namespace app\controllers;

class Login {
    
    public function index($params){
        return [
            'view' => 'login.php',
            'data' => ['title' => 'Login']
        ];
    }
    
    public function store(){

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        
        if(empty($email) || empty($password)){
            return redirect('/login');
        }

        $user = userFindBy('email', $email);
        
        if(!$user){
            return redirect('/login');
        }
        
        //if(!password_verify($password, $user->password)){
            //return redirect('/login');
        //}
        
        if($password !== $user->password){
            return redirect('/login');
        }

        $_SESSION['logged'] = $user;

        return redirect('/download-xml');
    }
    
}