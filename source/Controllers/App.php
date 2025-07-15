<?php


namespace Source\Controllers;


use League\Plates\Engine;

/**
 * Class App
 * @package Source\Controllers
 */
class App
{
  /**
   * @var Engine
   */
  private $view;
  /**
   * @var
   */
  protected $user;

  /**
   * App constructor.
   */

  public function __construct($router)
  {
    $this->view = Engine::create(__DIR__ . "/../../views/theme", "php");
    $this->view->addData(["router" => $router]);
  }

  /**
   *
   */
  public function home(): void
  {

    echo $this->view->render("home", [
      "title" => "Home | " . SITE
    ]);
  }

  public function lote(): void
  {

    echo $this->view->render("lote", [
      "title" => "Home | " . SITE
    ]);
  }

  public function loteBaixar($data): void
  {
    echo $this->view->render("lote-baixar", [
      "title" => "Empresa | " . SITE['name'],
      "param" => $data
    ]);
  }

  public function loteListar($data): void
  {
    echo $this->view->render("lote-listar", [
      "title" => "Empresa | " . SITE['name'],
      "param" => $data
    ]);
  }

  public function extrair(): void
  {

    echo $this->view->render("extrair", [
      "title" => "Home | " . SITE
    ]);
  }

  public function login(): void
  {
    echo $this->view->render("login", [
      "title" => "Login | " . SITE
    ]);
  }

  public function cadastro(): void
  {
    echo $this->view->render("cadastro", [
      "title" => "Login | " . SITE
    ]);
  }

  public function configuracao(): void
  {
    echo $this->view->render("configuracao", [
      "title" => "Login | " . SITE
    ]);
  }

  /**
   *
   */
  public function logout(): void
  {
    session_destroy();
    header("Location: " . url() . " ");
  }

  /**
   * @param array $data
   */
  public function error(array $data): void
  {
    echo $this->view->render("error", [
      "title" => "Error {$data['errcode']} |" . SITE,
      "error" => $data['errcode']
    ]);
  }
}
