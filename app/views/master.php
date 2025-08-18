<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo ASSETS."css/global.css" ?>" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet"/>
    <link href="<?php echo ASSETS."css/download-xml.css" ?>" rel="stylesheet"/>
    <link href="<?php echo ASSETS."css/login.css" ?>" rel="stylesheet"/>
    <title><?php echo "Guia Cont | $title" ?></title>
</head>
<body class="grid-layout">
    <?php require "partials/header.php" ?>
    <?php require "partials/sidebar.php" ?>
    <div>
        <?php require VIEWS.$view ?>
    </div>
    <?php require "partials/footer.php" ?>
</body>
</html>