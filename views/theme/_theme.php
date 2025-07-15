<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log insss</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= asset("_boot/plugins/fontawesome-free/css/all.min.css"); ?>">
  <link rel="stylesheet" href="<?= asset("_boot/plugins/icheck-bootstrap/icheck-bootstrap.min.css"); ?>">
  <link rel="stylesheet" href="<?= asset("_boot/dist/css/adminlte.min.css"); ?>">
  <link rel="stylesheet" href="<?= asset("_boot/plugins/toastr/toastr.min.css"); ?>">
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
  </style>

  <script src="<?= asset("_boot/plugins/jquery/jquery.min.js"); ?>"></script>
  <script src="<?= asset("_boot/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
  <script src="<?= asset("_boot/dist/js/adminlte.min.js"); ?>"></script>
  <script src="<?= asset("_boot/plugins/toastr/toastr.min.js"); ?>"></script>
  <script type="text/javascript">
    $(document).ajaxSend(function() {
      $("#overlay").fadeIn(300);
    });

    function loadOut() {
      setTimeout(function() {
        $("#overlay").fadeOut(300);
      }, 500);
    }

    function setTimeOutPage(url) {
      setTimeout(function() {
        window.location = url;
      }, 2000);
    }
  </script>
</head>

<body class="hold-transition login-page">
  <?= $v->section("content"); ?>
  <div id="overlay">
    <div class="cv-spinner">
      <span class="spinner"></span>
    </div>
  </div>
  </div>
  <?= $v->section("scripts"); ?>
</body>

</html>
