<?php
$v->layout("_themesis");
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Extrair Chaves do SPED
      <small>Basta fazer o upload do arquivo SPED e extraia todas as chaves</small>
    </h1>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-body">
        <form action="" method="post" enctype="multipart/form-data" id="uploadimage">
          <div class="row">
            <div class="form-group col-lg-12">
              <label>ARQUIVO SPED</label>
              <input type="file" class="form-control" name="arquivo" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <input type="submit" value="Enviar" class="submit btn btn-primary form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>

  </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        <div class="retornoModal"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<?php $v->start("scripts"); ?>
<script type="text/javascript">
  $(document).ready(function(e) {
    $("#uploadimage").on('submit', (function(e) {
      e.preventDefault();
      $.ajax({
        url: "<?= url('app/tratarSped'); ?>",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          loadOut();
        }
      });
    }));


  });
</script>
<?php $v->end(); ?>
