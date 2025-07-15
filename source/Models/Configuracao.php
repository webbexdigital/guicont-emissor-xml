<?php

namespace Source\Models;

use Source\Conn\DataLayer;

/**
 *
 */
class Configuracao extends DataLayer
{
  /**
   * @param array $forms
   * @return void
   */
  public function edit(array $forms)
  {
    print_r($forms);
    $id = $forms['id'];
    unset($forms['id']);

    if ($forms['configuracao_dia_baixa'] == '') {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "CPF/CNPJ e Razão Social precisam ser preenchidos"
      )->back(["count" => 0]);
      return;
    }

    $result = $this->db()->update('configuracao')->where('configuracao_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)->set($forms);

    if ($result == '0' || $result == '1') {
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

  /**
   * @param array $data
   * @return void
   */
  public function selectId(array $data): void
  {
    $result = $this->db()->from('configuracao')
      ->where('configuracao_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->select()
      ->all();

    if ($result) {
      foreach ($result as $r) {
        echo json_encode($r);
      }
      return;
    }
  }
}
