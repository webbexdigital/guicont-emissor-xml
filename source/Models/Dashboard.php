<?php

namespace Source\Models;

use Source\Conn\DataLayer;

class Dashboard extends DataLayer
{
  public function manageCount(array $data): void
  {
    $resultContratos = $this->db()->from('contrato')
      ->where('contrato_lixeira')->is(0)
      ->andWhere('contrato_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->andWhere('contrato_status')->is(0)
      ->select()
      ->all();

    $resultContato = $this->db()->from('contato')
      ->where('contato_lixeira')->is(0)
      ->andWhere('contato_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->select()
      ->all();

    $resultContratosCancelados = $this->db()->from('contrato')
      ->where('contrato_lixeira')->is(0)
      ->andWhere('contrato_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->andWhere('contrato_status')->is(1)
      ->select()
      ->all();

    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back([
      "countContratos" => count($resultContratos),
      "countContato" => count($resultContato),
      "countContratosCancelados" => count($resultContratosCancelados),
    ]);
    return;
  }

  public function manageRecebidosPagos(array $data): void
  {
    $resultPix = $this->db()->from('financeiro')
      ->join('contato', function ($join) {
        $join->on('contato_id', 'financeiro_id_contato');
      })
      ->where('financeiro_lixeira')->is(0)
      ->andWhere('financeiro_id_empresa')->is($_SESSION[SESSION_NAME]->user_id_empresa)
      ->andWhere('financeiro_status')->is(1)
      ->orderBy('financeiro_data_pagamento', 'desc')
      ->limit(10)
      ->select()
      ->all();

    if ($resultPix) {
      $var = '<table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 25%">Cliente</th>
                    <th style="width: 20%">Descrição</th>
                    <th style="width: 10%">Valor</th>
                    <th style="width: 10%">Vencimento:</th>
                    <th style="width: 10%">Dt Pagto</th>
                    <th style="width: 10%">Vlr Pagto</th>
                    <th style="width: 10%">Status</th>
                </tr>
                </thead>
                <tbody>';

      $count = '0';

      foreach ($resultPix as $rPix) {
        $count++;
        if ($rPix->financeiro_tipo == '0') {
          $statusTxt = '<span class="badge bg-success">RECEBIDO</span>';
        } else {
          $statusTxt = '<span class="badge bg-danger">PAGO</span>';
        }
        $var .= '<tr>
                    <td>' . $count . '.</td>
                    <td>' . $rPix->contato_nome_razao . '</td>
                    <td>' . $rPix->financeiro_descricao . '</td>
                    <td>R$ ' . format_money($rPix->financeiro_valor) . '</td>
                    <td>' . format_date_br($rPix->financeiro_data_vencimento) . '</td>
                    <td>R$ ' . format_money($rPix->financeiro_valor_pagamento) . '</td>
                    <td>' . format_date_br($rPix->financeiro_data_pagamento) . '</td>
                    <td>' . $statusTxt . '</td>
                </tr>';
      }
      $var .= '</tbody>
            </table>';

      $this->call(
        '200',
        'Parabéns',
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
        "ok",
        "Operação realizada com sucesso."
      )->back([
        "retorno" => $var
      ]);
      return;
    }

    $var = '<table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 10%">#</th>
                    <th style="width: 30%">Cliente</th>
                    <th style="width: 30%">Descrição</th>
                    <th style="width: 15%">Valor</th>
                    <th style="width: 15%">Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>';

    $this->call(
      '200',
      'Parabéns',
      '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>',
      "ok",
      "Operação realizada com sucesso."
    )->back([
      "retorno" => count($var)
    ]);
    return;
  }
}
