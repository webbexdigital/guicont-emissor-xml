<?php

$typeConnectionConfig = $_SERVER['HTTP_HOST'];
if ($typeConnectionConfig == 'localhost') {
    $url_info = 'http://localhost';
} else {
    $url_info = 'https://deltaway.com.br/testes/xml';
}


define("URL_BASE", $url_info);

define("SITE", [
    "name" => "INTEGRAPHP - ERP",
    "desc"  => "",
    "domain" => "integraphp.com",
    "locale" => "pt-br",
    "root" => $url_info
]);

const SESSION = 'SISTEMA_DOWNLOADXML';


const DSN = 'mysql:host=localhost;dbname=deltaway_xml';
const USER = 'deltaway_userxml';
const PASS = 'J98]_h&f[l?C';

const VALUE_KEY = '0.10';
const VALUE_KEY_GER = '10';

const TOKEN_ASAAS = '$==';
const ASAAS_AMB = 'homologacao';

const GURU = 'e389482772077dd3fb52edcc06d5d8';

const SESSION_NAME = 'GESTORAPISIMPLES';



$VAR_A4 = '<!DOCTYPE html>
<html>
    <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <style>
        caption{
            position: absolute;
            right: 5px;
            top: 5px;
        }
    </style>
    </head>
    <style>
        @page { margin: 10px; }
        body{
            font-family: "Helvetica";
            font-size: 11px;
            padding: 10px
        }

        .text-center{
            text-align: center;
        }

        #registros{
            border-collapse: collapse;
        }


        #registros tr th, #registros tr td{
            padding: 2px;
            text-align: center;
            border: #6B6B6B solid 1px !important;
        }

        tr th, tr td{
            font-size: 11px
        }

        h2{
        margin: 0;
        padding: 0
        }

        .nowrap{
        white-space: nowrap;
        }

        .table-padding-left-20px{
        padding-left: 20px
        }

        .exportar-excel{
        position: absolute;
        top: 10px;
        right: 10px
        }

        .hidden{
            display: none;
        }

        .logomarca{
            width: 60px
        }

        .fundo-escuro{
        background-color: #F0F0F0;
        }

        .margin-0{
        margin: 0
        }

        .text-left{
        text-align: left !important;
        }

        @media print
        {
            caption
            {
                display: none !important;
            }
        }
    </style>
    <body>
        <div>
            <div id="dv">
                <table cellpadding="2" cellspacing="2" >
                    <tr>
                        <td class="logomarca">
                            <h1>#LOG_REPORT#</h1>
                        </td>
                        <td>
                            <h2>#TITULO_REPORT#</h2>
                            <div>#DATA_HORA_REPORT#</div>
                        </td>
                    </tr>
                </table>
    
                <table id="registros" cellpadding="0" cellspacing="0" width = "100%">
                    <thead>
                        #TITLE_REG_REPORT#
                    </thead>
                    <tbody>
                        #REG_REPORT#
                    </tbody>
                </table>
                <table cellpadding="2" cellspacing="2" width = "100%">
                    #TOT_REPORT#
                </table>
            </div>
        </div>
    </body>
</html>';

define("HTML_PRINT_A4", $VAR_A4);



function url(string $uri = null): string {
    if ($uri) {
        return URL_BASE . "/{$uri}";
    }

    return URL_BASE;
}
