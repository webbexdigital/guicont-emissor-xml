<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XMLSimples | Gerencimento</title>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= asset("/_boot/plugins/fontawesome-free/css/all.min.css"); ?>">
    <link rel="stylesheet" href="<?= asset("/_boot/dist/css/adminlte.min.css"); ?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="<?= asset("tabulator-master/dist/css/tabulator.min.css"); ?>">

    <link rel="stylesheet" href="<?= asset("_boot/plugins/toastr/toastr.min.css"); ?>">
    <link rel="stylesheet" href="<?= asset("js/jquery-ui/jquery-ui.min.css"); ?>">

    <link rel="stylesheet" href="<?= asset("/_boot/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"); ?>">
    <link rel="stylesheet"
          href="<?= asset("/_boot/plugins/datatables-responsive/css/responsive.bootstrap4.min.css"); ?>">
    <link rel="stylesheet" href="<?= asset("/_boot/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"); ?>">

    <style type="text/css">
        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }

        .ui-autocomplete {
            height: 200px;
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 20px;
        }
    </style>
    <script src="<?= asset("js/jquery/jquery.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
    <script src="<?= asset("_boot/dist/js/adminlte.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/toastr/toastr.min.js"); ?>"></script>
    <script src="<?= asset("tabulator-master/dist/js/tabulator.min.js"); ?>"></script>

    <script src="<?= asset("js/jquery.mask.js"); ?>"></script>
    <script src="<?= asset("js/jquery.mask.min.js"); ?>"></script>
    <script src="<?= asset("js/jquery.maskMoney.js"); ?>"></script>

    <script src="<?= asset("js/jquery-ui/jquery-ui.js"); ?>"></script>

    <script src="<?= asset("_boot/plugins/datatables/jquery.dataTables.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-responsive/js/dataTables.responsive.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-buttons/js/dataTables.buttons.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/jszip/jszip.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/pdfmake/pdfmake.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/pdfmake/vfs_fonts.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-buttons/js/buttons.html5.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-buttons/js/buttons.print.min.js"); ?>"></script>
    <script src="<?= asset("_boot/plugins/datatables-buttons/js/buttons.colVis.min.js"); ?>"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


    <script>
        $(document).ajaxSend(function () {
            $("#overlay").fadeIn(300);
        });

        function loadOut() {
            setTimeout(function () {
                $("#overlay").fadeOut(300);
            }, 500);
        }

        function setTimeOutPage(url) {
            setTimeout(function () {
                window.location = url;
            }, 2000);
        }

        function redirect(url) {
            window.location = url;
        }

        function logout() {
            var Data = "&id=0";
            $.ajax({
                url: "<?= url('app/logout'); ?>",
                data: Data,
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data[0].type === 'ok') {
                        toastr.success('Deslogado com sucesso, te aguardo em breve!');
                        setTimeOutPage('<?php echo url(); ?>');
                    }
                    loadOut();
                },
                error: function (request, status, error) {
                    loadOut();
                }
            });
            return false;
        }
    </script>
    <link rel="stylesheet" href="<?= asset("_boot/css/custom.css"); ?>">
</head>

<body class="hold-transition sidebar-mini">
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
        <img class="animation__shake" src="<?= asset("/_boot/img/logo_guia_cont.png"); ?>" alt="AdminLTELogo"
             height="60" width="60" style="display: none;">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <?php
        if (!isset($_SESSION['APISIMPLES_DOWNLOAD'])) :
            ?>
            <!--ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('login'); ?>" role="button">
                        <i class="fas fa-user"></i> Login
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('cadastro'); ?>" role="button">
                        <i class="fas fa-id-card"></i> Cadastre-se
                    </a>
                </li>
            </ul-->
        <?php
        endif;
        ?>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="https://guiacontcliente.webbex.com.br/" class="brand-link">
            <img src="<?= asset("/_boot/img/logo_guia_cont.png"); ?>" alt="Logo"
                 class="brand-image" style="filter: contrast(0%) brightness(0%) invert(100%);">
        </a>

        <div class="sidebar">
            <?php
            if (isset($_SESSION['APISIMPLES_DOWNLOAD'])) :
                $explodeName = explode(' ', $_SESSION['APISIMPLES_DOWNLOAD']->user_nome);
                ?>
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo get_gravatar($_SESSION['APISIMPLES_DOWNLOAD']->user_email); ?>"
                             class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $explodeName['0']; ?></a>
                    </div>
                </div>
                </li>
            <?php
            endif;
            ?>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                            <a href="https://deltaway.com.br/testes/xml/views/theme/extensao.zip" class="nav-link" target="_Blanck">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                                </svg>
                                <p style="margin-left: 2%">
                                    Baixar Extensão (obrigatório)
                                </p>
                            </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo url(); ?>" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <style>
                                    svg {
                                        fill: #ffffff
                                    }
                                </style>
                                <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                            </svg>
                            <p style="margin-left: 2%">
                                Download XML
                            </p>
                        </a>
                    </li>
                    
                    <?php
                    if (isset($_SESSION['APISIMPLES_DOWNLOAD'])) :
                        ?>
                        <li class="nav-item">
                            <a href="<?php echo url('lote'); ?>" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512">
                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M24 0C10.7 0 0 10.7 0 24S10.7 48 24 48H69.5c3.8 0 7.1 2.7 7.9 6.5l51.6 271c6.5 34 36.2 58.5 70.7 58.5H488c13.3 0 24-10.7 24-24s-10.7-24-24-24H199.7c-11.5 0-21.4-8.2-23.6-19.5L170.7 288H459.2c32.6 0 61.1-21.8 69.5-53.3l41-152.3C576.6 57 557.4 32 531.1 32H360V134.1l23-23c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-64 64c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l23 23V32H120.1C111 12.8 91.6 0 69.5 0H24zM176 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm336-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z"/>
                                </svg>
                                <p style="margin-left: 2%">
                                    Download XML Lote
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo url('lote-listar'); ?>" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/>
                                </svg>
                                <p style="margin-left: 2%">
                                    Lote XMLs
                                </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?php echo url('logout.php'); ?>" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <style>
                                        svg {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                                </svg>
                                <p style="margin-left: 2%">
                                    Sair
                                </p>
                            </a>
                        </li>
                    <?php
                    endif;
                    ?>
                </ul>
            </nav>
        </div>
    </aside>
    <div class="container-fluid nav-hidden carregar_paginas" id="content">
        <?= $v->section("content"); ?>
    </div>

    <?= $v->section("scripts"); ?>
    <script>
        $('.mask_telefone').mask('(00)000000000');
        $('.mask_cep').mask('00000-000');
        $('.cpf').mask('000.000.000-00');
        $('.referencia').mask('00/0000');

        $(".mask_moeda").maskMoney({
            thousands: '',
            decimal: '.',
            allowZero: true
        });
        $(".mask_moeda_real").maskMoney({
            thousands: '.',
            decimal: ','
        });
        $(".mask_peso").maskMoney({
            thousands: '',
            decimal: '.',
            precision: 3,
            allowZero: true
        });
    </script>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Versão</b> 1.2.6
        </div>
        <strong>Copyright &copy; 2024 - <?php echo date('Y'); ?> <a href="https://guiacontcliente.webbex.com.br/" target="_blank">Guia Cont</a>.</strong>
        Todos os direitos são reservados.
    </footer>
</div>
</body>

</html>
