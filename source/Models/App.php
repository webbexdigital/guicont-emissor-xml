<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Source\Models;

use Source\Conn\DataLayer;

/**
 * Description of App
 *
 * @author marques
 */
class App extends DataLayer {

  public function logout() {
    session_destroy();
    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["count" => 0]);
    return;
  }

  public function getXml(array $data) {
    $ano = date('Y');
    $mes = date('m');
    $dia = date('d');

    if (!is_dir('shared/xml/' . $ano . '/')) {
      mkdir('shared/xml/' . $ano . '/', 0777, true);
    }
    if (!is_dir('shared/xml/' . $ano . '/' . $mes . '/')) {
      mkdir('shared/xml/' . $ano . '/' . $mes . '/', 0777, true);
    }
    if (!is_dir('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/')) {
      mkdir('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/', 0777, true);
    }

    $chave = $data['chave'];

    $base64 = str_replace(' ', '+', $data['xml']);

    file_put_contents('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/' . $chave . '.xml', base64_decode($base64));


    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["caminho" => 'shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/' . $chave . '.xml', "chave" => $chave, "link" => base64_decode($data['link'])]);
    return;
  }

  public function getXmlLote(array $data) {
    $chave = $data['chave'];
    $status = $data['status'];

    $base64 = str_replace(' ', '+', $data['xml']);

    $result = $this->db()->from('lote')
      ->where('lote_uuid')->is($data['ide'])
      ->select(['lote_id'])
      ->all();

    if ($result) {
      foreach ($result as $r) {
        $up['item_xml'] = $base64;
        $up['item_status'] = $status;
        $result = $this->db()->update('item')->where('item_id_lote')->is($r->lote_id)->andWhere('item_chave')->is($chave)->set($up);
      }
    }


    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["count" => 0]);
    return;
  }

  public function getPdf(array $data) {
    $ano = date('Y');
    $mes = date('m');
    $dia = date('d');

    if (!is_dir('shared/xml/' . $ano . '/')) {
      mkdir('shared/xml/' . $ano . '/', 0777, true);
    }
    if (!is_dir('shared/xml/' . $ano . '/' . $mes . '/')) {
      mkdir('shared/xml/' . $ano . '/' . $mes . '/', 0777, true);
    }
    if (!is_dir('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/')) {
      mkdir('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/', 0777, true);
    }

    $chave = $data['chave'];

    $base64 = str_replace(' ', '+', $data['xml']);

    file_put_contents('shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/' . $chave . '.xml', base64_decode($base64));


    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["caminho" => 'shared/xml/' . $ano . '/' . $mes . '/' . $dia . '/' . $chave . '.xml', "chave" => $chave, "link" => url('pdf.php?chave=' . $chave)]);
    return;
  }

  public function tratarChaves(array $data) {

    $explodeKey = explode(PHP_EOL, $data['chaves']);


    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["inicio" => $data['inicio'] + 1, "chave" => $explodeKey[$data['inicio']]]);
    return;
  }

  public function tratarSped(array $data) {
    $nameSped = md5(date('Y-m-dH:i:s') . rand(100, 999));

    $sped = $_FILES['arquivo'];

    $extSped = explode(".", $sped["name"]);

    $randFolder = rand(100000, 999999);

    if ($extSped['1'] != 'txt') {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Só aceitamos extenções TXT"
      )->back(["count" => 0]);
      return;
    }
    if (mkdir('shared/sped/' . $randFolder . '/', 0777, true)) {
    }

    if (move_uploaded_file($sped['tmp_name'], 'shared/sped/' . $randFolder . '/' . $nameSped . '.txt')) {
      $handle = fopen('shared/sped/' . $randFolder . '/' . $nameSped . '.txt', 'r');
      if ($handle) {
        while (!feof($handle)) {
          $row = fread($handle, 4096);
          $explode_line = explode('|', $row);
          if ($explode_line['1'] == 'C100') {
            echo $explode_line['9'] . '<hr/>';
          }
          unset($row);

          flush();
          ob_flush();
        }
        fclose($handle);
      }
    }
    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Erro ao enviar arquivos"
    )->back(["count" => 0]);
    return;
  }

  public function insertUser(array $forms) {

    if (in_array('', $forms)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Todos os campos precisam ser preenchidos"
      )->back(["count" => 0]);
      return;
    }

    $result = $this->db()->from('user')
      ->where('user_email')->is($forms['user_email'])
      ->select()
      ->all();

    if ($result) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Já existe um email registrado em nosso banco de dados"
      )->back(["count" => 0]);
      return;
    }

    $forms['user_status'] = '0';


    if ($this->db()->insert($forms)->into('user')) {
      $this->call(
        '200',
        'Parabéns',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "ok",
        "Operação realizada com sucesso."
      )->back(["count" => 0]);
      return;
    }

    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Houve um problema no processamento da solicitação."
    )->back(["count" => 0]);
    return;
  }

  public function login(array $forms) {

    if (in_array('', $forms)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Todos os campos precisam ser preenchidos"
      )->back(["count" => 0]);
      return;
    }

    $result = $this->db()->from('user')
      ->where('user_email')->is($forms['user_email'])
      ->andWhere('user_senha')->is($forms['user_senha'])
      ->select()
      ->all();

    if (!$result) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Email e/ou senha inválidos"
      )->back(["count" => 0]);
      return;
    }

    foreach ($result as $r) {
      $_SESSION['APISIMPLES_DOWNLOAD'] = $r;
      $this->call(
        '200',
        'Parabéns',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "ok",
        "Operação realizada com sucesso."
      )->back(["count" => 0]);
      return;
    }

    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Houve um problema no processamento da solicitação."
    )->back(["count" => 0]);
    return;
  }

  public function insertLote(array $forms) {

    if (in_array('', $forms)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Todos os campos precisam ser preenchidos"
      )->back(["count" => 0]);
      return;
    }

    $explodeKey = explode(PHP_EOL, $forms['texto']);
    for ($x = 0; $x < count($explodeKey); $x++) {

      if ($x == '0') {
        $createBatch['lote_uuid']       = getUuid();
        $createBatch['lote_data_hora']  = date('Y-m-d H:i:s');
        $createBatch['lote_status']     = '0';
        $createBatch['lote_id_user']    = $_SESSION['APISIMPLES_DOWNLOAD']->user_id;
        $createBatch['lote_quantidade'] = count($explodeKey);
        $this->db()->insert($createBatch)->into('lote');


        $idLote = $this->lastRegistro('lote', 'lote_id', 'lote_id');
      }

      $chave = trim($explodeKey[$x]);

      $create['item_chave']   = $chave;
      $create['item_status']  = '0';
      $create['item_id_lote'] = $idLote;

      if ($chave != '') {
        $this->db()->insert($create)->into('item');
      }
    }

    $_SESSION['uuid_lote'] = $createBatch['lote_uuid'];

    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["count" => 0, "uuid" => $createBatch['lote_uuid']]);
    return;
  }

  public function downloadZip(array $data) {
    if (in_array('', $data)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Existem campos em branco"
      )->back(["count" => 0]);
      return;
    }


    $resultDoc = $this->db()->from('lote')
      ->join('item', function ($join) {
        $join->on('item_id_lote', 'lote_id');
      })
      ->where('lote_id_user')->is($_SESSION['APISIMPLES_DOWNLOAD']->user_id)
      ->andWhere('lote_status')->is(1)
      ->andWhere('lote_uuid')->is($data['uuid'])
      ->select()
      ->all();

    if ($resultDoc) {
      $randFolder = rand(100000, 999999);
      if (mkdir('shared/temp/' . $randFolder . '/', 0777, true)) {
        $zip = new \ZipArchive();
        $arqZip = 'shared/zipex/' . $randFolder . '.zip';
        $zip->open($arqZip, \ZipArchive::CREATE);
        foreach ($resultDoc as $r) {
          $nameFile = $r->item_chave . '.xml';
          $decoded_file_data = base64_decode($r->item_xml);
          file_put_contents('shared/temp/' . $randFolder . '/' . $nameFile, $decoded_file_data);

          $zip->addFile('shared/temp/' . $randFolder . '/' . $nameFile, $nameFile);
        }
        $zip->close();


        foreach ($resultDoc as $r) {
          $nameFile = $r->item_chave . '.xml';
          unlink('shared/temp/' . $randFolder . '/' . $nameFile);
        }

        rmdir('shared/temp/' . $randFolder . '/');
        $linkZip = '<a href="../' . $arqZip . '" class="btn btn-success" download>BAIXAR OS ARQUIVOS</a>';

        $this->call(
          '200',
          'Parabéns',
          '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
          "ok",
          "Documento processado com sucesso"
        )->back(["button" => $linkZip]);
        return;
      }

      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Sem informações"
      )->back(["count" => 0]);
      return;
    }

    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Não existem arquivos"
    )->back(["count" => 0]);
    return;
  }


  public function retomarDownload(array $data) {
    if (in_array('', $data)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Existem campos em branco"
      )->back(["count" => 0]);
      return;
    }


    $_SESSION['uuid_lote'] = $data['uuid'];
    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back(["count" => 0, "uuid" => $data['uuid']]);
    return;



    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Não existem arquivos"
    )->back(["count" => 0]);
    return;
  }
}
