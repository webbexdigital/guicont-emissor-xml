<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Source\Controllers;

use League\Plates\Engine;

/**
 * Description of Login
 *
 * @author marques
 */
class Login
{
  /**
   * @var Engine
   */
  private $view;

  /**
   * Account constructor.
   */
  public function __construct($router)
  {
    $this->view = Engine::create(__DIR__ . "/../../views/theme", "php");
    $this->view->addData(["router" => $router]);
  }

  /**
   *
   */
  public function index(): void
  {
    echo $this->view->render("home/index", [
      "title" => "Login | " . SITE
    ]);
  }

  public function create(): void
  {
    echo $this->view->render("login/create", [
      "title" => "Cadastros | " . SITE
    ]);
  }
}
