<?php

namespace Source\Models;

use Source\Conn\DataLayer;

/**
 * CLASSE RESPONSÁVEL PELO RETORNO DOS SELECT
 *
 * @author marques
 */
class Select extends DataLayer
{

  /**
   * @param array $data
   * @return void
   */
  public function conta(array $data)
  {
    $result = $this->db()->from('conta')
      ->where('conta_lixeira')->is(0)
      ->andWhere('conta_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->select()
      ->all();

    if ($result) {
      foreach ($result as $r) {
        if ($r->conta_codigo_banco == '077') {
          $r->conta_codigo_banco = '077 - INTER';
        }
        $rows['data'][] = $r;
      }
      echo json_encode($rows);
      return;
    }
  }
}
