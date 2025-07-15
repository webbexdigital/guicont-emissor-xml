<?php

namespace source\Controllers;


use League\Plates\Engine;
use Source\Models\TypeDocument;

/**
 *
 */
class Web
{
    /**
     * @var Engine
     */
    private $view;

    /**
     *
     */
    public function __construct()
    {
        $this->view = Engine::create(__DIR__."/../../views/", "php");
    }

    /**
     * @return void
     */
    public function login(): void{

        echo $this->view->render('theme/login', [
            "title" => "Login |".SITE
        ]);
    }

    /**
     * @return void
     */
    public function index_type_document(): void{

        echo $this->view->render('theme/type_document/index', [
            "read" => (new TypeDocument())->read()
        ]);
    }

    /**
     * @return void
     */
    public function home(): void{

        echo $this->view->render('theme/home', [
            "title" => "Login |".SITE
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function error(array $data): void
    {
        echo $this->view->render("error", [
            "title" => "Erro {$data['errcode']} |".SITE,
            "error" => $data['errcode']
        ]);
    }
}