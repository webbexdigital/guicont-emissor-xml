<?php

ini_set('display_errors', 'Off');

date_default_timezone_set('America/Sao_Paulo');

ini_set('max_execution_time', 0);

error_reporting(E_ERROR | E_WARNING | E_PARSE);

ob_start();

session_start();

use CoffeeCode\Router\Router;

require __DIR__ . "/vendor/autoload.php";

$router = new Router(URL_BASE);

$router->namespace("Source\Controllers");

$router->get("/", "App:home", "app.home");

$router->get("/home", "App:home", "app.home");

$router->get("/lote", "App:lote", "app.lote");

$router->get("/lote-baixar", "App:loteBaixar", "app.loteBaixar");

$router->get("/lote-listar", "App:loteListar", "app.loteListar");

$router->get("/logout", "App:logout", "app.logout");

$router->get("/login", "App:login", "app.login");

$router->get("/cadastro", "App:cadastro", "app.cadastro");

$router->get("/extrair", "App:extrair", "app.extrair");


$router->namespace("Source\Models");


$router->group('/app');

$router->post("/logout", "App:logout", "app.logout");

$router->post("/login", "App:login", "app.login");

$router->post("/getXml", "App:getXml", "app.getXml");

$router->post("/getXmlLote", "App:getXmlLote", "app.getXmlLote");

$router->post("/getPdf", "App:getPdf", "app.getPdf");

$router->post("/tratarChaves", "App:tratarChaves", "app.tratarChaves");

$router->post("/tratarSped", "App:tratarSped", "app.tratarSped");

$router->post("/insertUser", "App:insertUser", "app.insertUser");

$router->post("/insertLote", "App:insertLote", "app.insertLote");

$router->post("/downloadZip", "App:downloadZip", "app.downloadZip");

$router->post("/retomarDownload", "App:retomarDownload", "app.retomarDownload");


$router->group('/configuracao');

$router->post("/selectId", "Configuracao:selectId", "configuracao.selectId");

$router->post("/edit", "Configuracao:edit", "configuracao.edit");



$router->group('/dashboard');

$router->post("/manageCount", "Dashboard:manageCount", "dashboard.manageCount");

$router->post("/manageRecebidosPagos", "Dashboard:manageRecebidosPagos", "dashboard.manageRecebidosPagos");


$router->group('/autocomplete');

$router->post("/client", "AutoComplete:client", "autocomplete.client");

$router->post("/produto", "AutoComplete:produto", "autocomplete.produto");


$router->group('/select');

$router->post("/conta", "Select:conta", "select.conta");


$router->dispatch();

ob_end_flush();