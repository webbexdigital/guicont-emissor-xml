<?php

namespace Source\Facades;

class Inter
{

  private $clientId;
  private $clientSecret;
  private $urlToken;
  private $url;
  private $environment;
  private $base64;
  private $headers;
  private $fields;
  private $certificatePem;
  private $certificateKey;

  public function __construct($clientId, $clientSecret, $certificatePem, $certificateKey, $environment = 'production')
  {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->certificatePem = $certificatePem;
    $this->certificateKey = $certificateKey;

    $this->urlToken = 'https://cdpj.partners.bancointer.com.br/oauth/v2/token';
    $this->url = 'https://cdpj.partners.bancointer.com.br';
    $this->environment = $environment;

    if ($environment == 'sandbox') {
      $this->urlToken = '';
      $this->url = '';
      $this->environment = $environment;
    }
  }

  protected function createTempKey($base64)
  {
    $decode = base64_decode($base64);
    $nameCertificate = md5(date('Y-m-dH:i:s') . rand(1000, 9999)) . '.key';
    file_put_contents(__DIR__ . '/../../shared/certificate/' . $nameCertificate, $decode);

    return $nameCertificate;
  }

  protected function createTempPem($base64)
  {
    $decode = base64_decode($base64);
    $nameCertificate = md5(date('Y-m-dH:i:s') . rand(1000, 9999)) . '.pem';
    file_put_contents(__DIR__ . '/../../shared/certificate/' . $nameCertificate, $decode);

    return $nameCertificate;
  }

  protected function headers(array $headers)
  {
    if (!$headers) {
      return;
    }
    foreach ($headers as $k => $v) {
      $this->header($k, $v);
    }
  }

  protected function header(string $key, string $value)
  {
    if (!$key) {
      return;
    }
    $keys = filter_var($key, FILTER_SANITIZE_STRIPPED);
    $values = filter_var($value, FILTER_SANITIZE_STRIPPED);
    $this->headers[] = "{$keys}: {$values}";
  }

  protected function fields(array $fields, string $format = "json")
  {
    if ($format == "json") {
      $this->fields = (!empty($fields) ? json_encode($fields) : null);
      return;
    }
    if ($format == "query") {
      $this->fields = (!empty($fields) ? http_build_query($fields) : null);
      return;
    }
  }


  /*public function getTokenAccess()
  {
    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->urlToken,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_POSTFIELDS => http_build_query(array(
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
        'scope' => 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.write pagamento-boleto.read cob.write cob.read cobv.write cobv.read pix.write pix.read webhook.read webhook.write pagamento-pix.write pagamento-pix.read webhook-banking.write webhook-banking.read',
        'grant_type' => 'client_credentials'
      )),
      CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
    ));

    return json_decode(curl_exec($curl));
  }*/

  public function getTokenAccess()
  {
    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init();

    if (is_file(__DIR__ . '/../../shared/access_token/' . $this->clientId . '.txt')) {

      $open = file_get_contents(__DIR__ . '/../../shared/access_token/' . $this->clientId . '.txt');

      $explode = explode(';', $open);
      $sumDate = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($explode['1'])));

      if (strtotime($sumDate) > strtotime(date('Y-m-d H:i:s'))) {

        $arrRet['access_token'] = $explode['0'];

        return (object)$arrRet;
      } else {
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->urlToken,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
          CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
          CURLOPT_POSTFIELDS => http_build_query(array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.write pagamento-boleto.read cob.write cob.read cobv.write cobv.read pix.write pix.read webhook.read webhook.write pagamento-pix.write pagamento-pix.read webhook-banking.write webhook-banking.read',
            'grant_type' => 'client_credentials'
          )),
          CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
        ));

        $response =  json_decode(curl_exec($curl));

        $conArchive = $response->access_token . ';' . date('Y-m-d H:i:s');
        file_put_contents(__DIR__ . '/../../shared/access_token/' . $this->clientId . '.txt', $conArchive);
        return $response;
      }
    } else {
      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->urlToken,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
        CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
        CURLOPT_POSTFIELDS => http_build_query(array(
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'scope' => 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.write pagamento-boleto.read cob.write cob.read cobv.write cobv.read pix.write pix.read webhook.read webhook.write pagamento-pix.write pagamento-pix.read webhook-banking.write webhook-banking.read',
          'grant_type' => 'client_credentials'
        )),
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
      ));

      $response =  json_decode(curl_exec($curl));

      $conArchive = $response->access_token . ';' . date('Y-m-d H:i:s');
      file_put_contents(__DIR__ . '/../../shared/access_token/' . $this->clientId . '.txt', $conArchive);
      return $response;
    }

    return json_decode(curl_exec($curl));
  }

  public function registerPix(array $fields, $txtId = null)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/cob/{$txtId}");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }


  public function registerWebhook(array $fields, $keyPix)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/webhook/{$keyPix}");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function readWebhook($keyPix)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init("{$this->url}/webhook/{$keyPix}");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function devolutionPix(array $fields, $e2eid = null, $id = null)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/pix/{$e2eid}/devolucao/{$id}");

    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function readPix($txtId)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init("{$this->url}/cob/{$txtId}");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLINFO_HEADER_OUT => true
    ]);

    return json_decode(curl_exec($curl));
  }

  public function registerBillet(array $fields, $txtId = null)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/cobranca/v3/cobrancas");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function cancelBillet(array $fields, $code = null)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/cobranca/v3/cobrancas/{$code}/cancelar");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function incluirPaymentPix(array $fields)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $this->fields($fields, 'json');

    $curl = curl_init("{$this->url}/banking/v2/pix");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    ]);
    return json_decode(curl_exec($curl));
  }

  public function getBilletPDF($code)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init("{$this->url}/cobranca/v3/cobrancas/{$code}/pdf");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLINFO_HEADER_OUT => true
    ]);

    return json_decode(curl_exec($curl));
  }

  public function getBalance()
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init("{$this->url}/banking/v2/saldo");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLINFO_HEADER_OUT => true
    ]);

    return json_decode(curl_exec($curl));
  }

  public function getBilletPago($dataInicial, $dataFinal)
  {
    $this->headers([
      "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
      "Content-Type"      => "application/json"
    ]);
    if ($this->environment == 'sandbox') {
      $this->headers([
        "Authorization"     => "Bearer " . $this->getTokenAccess()->access_token,
        "Content-Type"      => "application/json"
      ]);
    }

    $nameCertificatePem = $this->createTempPem($this->certificatePem);
    $nameCertificateKey = $this->createTempKey($this->certificateKey);

    $curl = curl_init("{$this->url}/cobranca/v2/boletos?dataInicial={$dataInicial}&dataFinal={$dataFinal}&filtrarDataPor=SITUACAO&situacao=PAGO");
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => $this->fields,
      CURLOPT_HTTPHEADER => ($this->headers),
      CURLOPT_SSLCERT => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificatePem),
      CURLOPT_SSLKEY => realpath(__DIR__ . '/../../shared/certificate/' . $nameCertificateKey),
      CURLOPT_SSLCERTTYPE => 'PEM',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLINFO_HEADER_OUT => true
    ]);

    return json_decode(curl_exec($curl));
  }
}
