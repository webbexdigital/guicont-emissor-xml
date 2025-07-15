<?php
ini_set('display_errors', 'On');
require __DIR__ . "/vendor/autoload.php";

use NFePHP\DA\NFe\Danfe;

$jSON = array();
$getGet = filter_input(INPUT_GET, 'chave');
$Filename = "shared/xml/" . date('Y') . "/" . date('m') . "/" . date('d') . "/{$getGet}.xml";
$xml = file_get_contents($Filename);
$logo = '';

try {
  $danfe = new Danfe($xml);
  $danfe->debugMode(true);
  $danfe->creditsIntegratorFooter('Futura');
  $danfe->printParameters(
    $orientacao = 'P',
    $papel = 'A4',
    $margSup = 2,
    $margEsq = 2
  );
  //$danfe->logoParameters($logo, $logoAlign = 'C', $mode_bw = false);
  //Gera o PDF
  $pdf = $danfe->render();
  header('Content-Type: application/pdf');
  echo $pdf;
} catch (InvalidArgumentException $e) {
  echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
}
