<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Source\Models;

use Source\Conn\DataLayer;

/**
 * CLASSE RESPOSÁVEL PELO CONTROLE DE LOGIN
 *
 * @author marques
 */
class Login extends DataLayer
{

  /**
   * @param array $forms
   * @return void
   */
  public function send(array $forms)
  {
    if (in_array('', $forms)) {
      $this->call(
        '200',
        'Ops',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "error",
        "Todos os campos devem ser preenchidos."
      )->back(["count" => 0]);
      return;
    }



    $result = $this->db()->from('user')
      ->where('user_email')->is($forms['email'])
      ->andWhere('user_senha')->is($forms['password'])
      ->andWhere('user_status')->is(0)
      ->andWhere('user_lixeira')->is(0)
      ->select()
      ->all();


    if ($result) {
      foreach ($result as $r) {
        $_SESSION[SESSION_NAME] = $r;
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

    $this->call(
      '200',
      'Ops',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "error",
      "Registros não foram encontrados."
    )->back(["count" => 0]);
    return;
  }
}
