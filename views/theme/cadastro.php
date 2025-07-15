<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 style="border-bottom: 1px solid #CCCCCC">
            Cadastre-se
          </h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="container-fluid">
      <form class="wc_form" action="" method="POST">
        <div class="row">
          <div class="form-group col-lg-12">
            <label>Nome</label>
            <input type="text" class="form-control" name="user_nome">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-12">
            <label>Email</label>
            <input type="email" class="form-control" name="user_email">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-12">
            <label>Senha</label>
            <input type="password" class="form-control" name="user_senha">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-12" align="right">
            <button class="btn btn-primary">CADASTRE-SE</button>
          </div>
        </div>
      </form>
    </div>

  </section>
</div>
<?php $v->start("scripts"); ?>
<script>
  $('.wc_form').submit(function() {
    var Form = $(this);
    var Data = Form.serialize();

    $.ajax({
      url: "<?= url('app/insertUser'); ?>",
      data: Data,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        loadOut();
        if (data[0].type === 'ok') {
          toastr.success(data[0].message);
          setTimeOutPage('<?php echo url('login'); ?>');
        } else {
          toastr.warning(data[0].message);
        }

      },
      error: function(xhr, err) {
        loadOut();
      },
      complete: function() {
        loadOut();
      }
    });
    return false;
  });
</script>
<?php $v->end(); ?>
