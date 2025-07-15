<?php

namespace Source\Models;

use Source\Conn\DataLayer;

class AutoComplete extends DataLayer
{
  public function client(array $data)
  {
    $result = $this->db()->from('contato')
      ->where('contato_nome_razao')->like('%' . $data['q'] . '%')
      ->andWhere('contato_lixeira')->is(0)
      ->andWhere('contato_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->limit(25)
      ->select()
      ->all();


    if ($result) {
      $returnDado = '[';
      foreach ($result as $r) {
        $returnDado .= '{"label":"' . $r->contato_nome_razao . ' | ' . $r->contato_nome_fantasia . ' | ' . $r->contato_cpf_cnpj . '","value":"' . $r->contato_id . '"},';
      }
      $return = substr($returnDado, 0, -1);
      $return .= ']';
      echo $return;
      return;
    } else {
      $return = '[{"label":"Não encontrado","value":""}]';
      echo $return;
      return;
    }
  }

  public function produto(array $data)
  {
    $result = $this->db()->from('produto')
      ->where('produto_descricao')->like('%' . $data['q'] . '%')
      ->andWhere('produto_lixeira')->is(0)
      ->andWhere('produto_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->limit(25)
      ->select()
      ->all();


    if ($result) {
      $returnDado = '[';
      foreach ($result as $r) {
        $returnDado .= '{"label":"' . $r->produto_descricao . '","value":"' . $r->produto_id . '"},';
      }
      $return = substr($returnDado, 0, -1);
      $return .= ']';
      echo $return;
      return;
    } else {
      $return = '[{"label":"Não encontrado","value":""}]';
      echo $return;
      return;
    }
  }
}
