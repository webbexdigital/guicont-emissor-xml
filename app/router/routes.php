<?php

return [
    "POST" => [],
    "GET" => [
        "/emissor-xml" => "DownloadXml@index",
        "/login" => "Login@index",
        "/cadastro" => "Cadastro@index",
        "/user/[0-9]+" => "User@index"
    ]
];