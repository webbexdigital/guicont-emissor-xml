<?php
function getUuid($data = null)
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function download($filename, $filepath, $base64_encoded_file_data)
{
    if (ob_get_level()) {
        ob_end_clean();
    }

    $decoded_file_data = base64_decode($base64_encoded_file_data);

    file_put_contents($filepath, $decoded_file_data);

    header('Expires: 0');
    header('Pragma: public');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($filepath));
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filepath);

    if (file_exists($filepath)) {
        //unlink($filepath);
    }
}

/**
 * @param array $string
 * @return false|string
 */
function converterJson(array $string)
{
    $result = json_encode($string, true);
    return $result;
}

/**
 * @param string $path
 * @param $time
 * @return string
 */
function asset(string $path, $time = true): string
{

    $file = SITE["root"] . "/views/assets/{$path}";
    $fileOnDir = dirname(__DIR__, 1) . "/views/assets/{$path}";
    if ($time && file_exists($fileOnDir)) {
        $file .= "?time=" . filemtime($fileOnDir);
    }
    return $file;
}

/**
 * @param string|null $param
 * @return string
 */
function site(string $param = null): string
{

    if ($param && !empty(SITE[$param])) {
        return SITE[$param];
    }

    return SITE["root"];
}

/**
 * @param $buffer
 * @return string
 */
function translate_similar_chars($buffer)
{
    $single_double = array(
        'Æ' => 'AE',
        'æ' => 'ae'
    );

    $buffer = strtr(
        $buffer,
        $single_double
    );

    $buffer = strtr(
        utf8_decode($buffer),
        utf8_decode('áàâäãåÁÀÂÄÃÅÞþßćčçĆČÇđĐÐéèêëÉÈÊËíìîïÍÌÎÏñÑóòôöõðøÓÒÔÖÕŕŔšŠ$úùûüÚÙÛÜýÿÝžŽØªº¹²³'),
        utf8_decode('aaaaaaAAAAAAbbBcccCCCdDDeeeeEEEEiiiiIIIInNoooooooOOOOOrRsSSuuuuUUUUyyYzZ0ao123')
    );

    $buffer = utf8_encode($buffer);

    return $buffer;
}

/**
 *
 * @param string $buffer
 * @return type
 */
function slug(string $buffer)
{
    $buffer = html_entity_decode($buffer);                       // Converte todas as entidades HTML para os seus caracteres
    $buffer = translate_similar_chars($buffer);                  // Converte caracteres que não estão no padrão para representações deles
    $buffer = strtolower($buffer);                               // Converte uma string para minúscula
    $buffer = preg_replace("/[\s]+/", " ", $buffer);             // Comprime múltiplas ocorrências de espaços
    $buffer = preg_replace("/[_]+/", "_", $buffer);              // Comprime múltiplas ocorrências de underscores
    $buffer = preg_replace("/[-]+/", "-", $buffer);              // Comprime múltiplas ocorrências de hífens
    $buffer = preg_replace("/[\/]+/", "-", $buffer);             // Comprime múltiplas ocorrências de barras
    $buffer = preg_replace("/[\\\]+/", "-", $buffer);            // Comprime múltiplas ocorrências de barras invertidas
    $buffer = preg_replace("/[[\s]+]?-[[\s]+]?/", "-", $buffer); // Remove espaços antes e após hífens
    $buffer = preg_replace("/[\s]/", "-", $buffer);              // Converte espaços em hífens
    $buffer = preg_replace("/[_]/", "-", $buffer);               // Converte underscores em hífens
    $buffer = preg_replace("/[\/]/", "-", $buffer);              // Converte barras em hífens
    $buffer = preg_replace("/[\\\]/", "-", $buffer);             // Converte barras invertidas em hífens
    $buffer = preg_replace("/[^a-z0-9_-]/", "", $buffer);      // Remove caracteres que não estejam no padrão

    return $buffer;
}


/**
 * @param $value
 * @return string
 */
function format_money($value)
{
    return number_format($value, 2, ',', '.');
}

/**
 * @param $value
 * @return false|string
 */
function format_datetime_br($value)
{
    return date("d/m/Y H:i:s", strtotime($value));
}

/**
 * @param $value
 * @return false|string
 */
function format_date_br($value)
{
    return date("d/m/Y", strtotime($value));
}

/**
 * @param $data
 * @return bool
 */
function validar_data($data)
{
    $d = explode('-', $data);

    if (checkdate($d['1'], $d['2'], $d['0'])) {
        return true;
    }
    return false;
}

function validate_cpf($cpf)
{
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function getCopyPaste($payload)
{
    $url = 'https://gerarqrcodepix.com.br/api/v1?nome=.&cidade=.&saida=br&location=' . $payload;

    $json = json_decode(file_get_contents($url), true);

    return $json['brcode'];
}

function getQRCode($payload)
{
    $url = 'https://gerarqrcodepix.com.br/api/v1?nome=.&cidade=.&saida=qr&location=' . $payload;
    if (!is_dir(__DIR__ . '/../shared/qrcode/' . date('Y'))) {
        mkdir(__DIR__ . '/../shared/qrcode/' . date('Y'), 0755, true);
    }
    if (!is_dir(__DIR__ . '/../shared/qrcode/' . date('Y') . '/' . date('m'))) {
        mkdir(__DIR__ . '/../shared/qrcode/' . date('Y') . '/' . date('m'), 0755, true);
    }
    if (!is_dir(__DIR__ . '/../shared/qrcode/' . date('Y') . '/' . date('m') . '/' . date('d'))) {
        mkdir(__DIR__ . '/../shared/qrcode/' . date('Y') . '/' . date('m') . '/' . date('d'), 0755, true);
    }
    $nameArchive = md5(date('YmdHis') . rand(100000, 999999)) . '.png';
    $urlArchive = file_get_contents($url);

    file_put_contents(__DIR__ . '/../shared/qrcode/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $nameArchive, $urlArchive);

    return URL_BASE . '/shared/qrcode/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $nameArchive;
}

function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function get_gravatar($email)
{
    $default = "https://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Item_sem_imagem.svg/1024px-Item_sem_imagem.svg.png";
    $size = 40;
    $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default) . "&s=" . $size;

    return $grav_url;
}

function get_chave($uuid)
{
    $db = new Source\Conn\DataLayer();

    $result = $db->db()->from('lote')
        ->join('item', function ($join) {
            $join->on('item_id_lote', 'lote_id');
        })
        ->where('lote_uuid')->is($uuid)
        ->andWhere('lote_id_user')->is($_SESSION['APISIMPLES_DOWNLOAD']->user_id)
        ->andWhere('lote_status')->is(0)
        ->andWhere('item_status')->is(0)
        ->orderBy(function ($expr) {
            $expr->op('rand()');
        })
        ->limit(1)
        ->select(['item_chave'])
        ->all();

    if ($result) {
        foreach ($result as $r) {
            return $r->item_chave;
        }
    }


    return false;
}

function get_total($uuid)
{
    $db = new Source\Conn\DataLayer();

    $result = $db->db()->from('lote')
        ->join('item', function ($join) {
            $join->on('item_id_lote', 'lote_id');
        })
        ->where('lote_uuid')->is($uuid)
        ->andWhere('lote_id_user')->is($_SESSION['APISIMPLES_DOWNLOAD']->user_id)
        ->andWhere('lote_status')->is(0)
        ->select(['lote_id'])
        ->all();

    return count($result);
}

function get_concluido($uuid)
{
    $db = new Source\Conn\DataLayer();

    $result = $db->db()->from('lote')
        ->join('item', function ($join) {
            $join->on('item_id_lote', 'lote_id');
        })
        ->where('lote_uuid')->is($uuid)
        ->andWhere('lote_id_user')->is($_SESSION['APISIMPLES_DOWNLOAD']->user_id)
        ->andWhere('lote_status')->is(0)
        ->andWhere('item_status')->is(1)
        ->select(['lote_id'])
        ->all();

    return count($result);
}
