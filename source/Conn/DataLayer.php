<?php



namespace Source\Conn;



use Opis\Database\Connection;

use Opis\Database\Database;

use Ifsnop\Mysqldump as IMysqldump;



/**

 * Class DataLayer

 * @package Source\Conn

 */

class DataLayer

{

  /**

   * @var string

   */

  protected $dsn = DSN;

  /**

   * @var string

   */

  protected $user = USER;

  /**

   * @var string

   */

  protected $pass = PASS;



  private $response;



  /**

   * @return Connection

   */

  public function conn()

  {

    $connection = new Connection(

      $this->dsn,

      $this->user,

      $this->pass

    );

    $connection->persistent();

    return $connection;

  }



  /**

   * @return Database

   */

  public function db()

  {

    $db = new Database($this->conn());

    return $db;

  }



  public function call(int $code, string $title, string $footer, string $type = null, string $message = null, $rule = '0')

  {

    http_response_code($code);



    if (!empty($type)) {

      $this->response = [

        $rule => [

          "code" => $code,

          "title" => $title,

          "footer" => $footer,

          "type" => $type,

          "message" => (!empty($message) ? $message : null)

        ]

      ];

    }

    return $this;

  }



  public function lastRegistro($table, $coluna, $registro)

  {

    $result = $this->db()->from($table)

      ->orderBy($coluna, 'DESC')

      ->limit('1')

      ->select()

      ->all();



    if ($result) {

      foreach ($result as $r) {

        $id_registro = $r->$registro;

      }

      return $id_registro;

    }

  }



  public function back(array $response = null)

  {

    if (!empty($response)) {

      $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);

    }



    echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    return $this;

  }

}

