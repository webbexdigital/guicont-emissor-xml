<?php

namespace app\controllers;

class DownloadXml {
    
    public function index($params){
        //$data = all('user');
        return [
            'view' => 'download-xml.php',
            'data' => ['title' => 'XMLSimples']
        ];
    }
    
}