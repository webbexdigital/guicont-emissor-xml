<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 style="border-bottom: 1px solid #CCCCCC">
            Configuração
          </h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="container-fluid">
      <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Principal</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Descontos</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Multas</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Juros</a></li>
      </ul>
      <hr />
      <form class="wc_form" action="" method="POST">
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <div class="row">
              <div class="form-group col-lg-12">
                <label>Dias Baixas</label>
                <input type="text" class="form-control" name="configuracao_dia_baixa">
              </div>
            </div>
          </div>

          <div class="tab-pane" id="tab_2">
            <div class="row">
              <div class="form-group col-lg-3">
                <label>Usar</label>
                <select class="form-control" name="configuracao_desconto_tipo">
                  <option value="0">NÃO</option>
                  <option value="1">SIM</option>
                </select>
              </div>
              <div class="form-group col-lg-3">
                <label>Taxa</label>
                <input type="text" class="form-control mask_moeda" name="configuracao_desconto_taxa" autocomplete="off">
              </div>
              <div class="form-group col-lg-3">
                <label>Código</label>
                <select class="form-control" name="configuracao_desconto_codigo">
                  <option value="VALORFIXODATAINFORMADA">Valor fixo até a data informada</option>
                  <option value="PERCENTUALDATAINFORMADA">Percentual até a data informada</option>
                </select>
              </div>
              <div class="form-group col-lg-3">
                <label>Quantidade Dias</label>
                <input type="text" class="form-control" name="configuracao_desconto_dias" autocomplete="off">
              </div>
            </div>
          </div>

          <div class="tab-pane" id="tab_3">
            <div class="row">
              <div class="form-group col-lg-4">
                <label>Usar</label>
                <select class="form-control" name="configuracao_multa_tipo">
                  <option value="0">NÃO</option>
                  <option value="1">SIM</option>
                </select>
              </div>
              <div class="form-group col-lg-4">
                <label>Taxa</label>
                <input type="text" class="form-control mask_moeda" name="configuracao_multa_taxa" autocomplete="off">
              </div>
              <div class="form-group col-lg-4">
                <label>Código</label>
                <select class="form-control" name="configuracao_multa_codigo">
                  <option value="PERCENTUAL">PERCENTUAL</option>
                  <option value="VALORFIXO">VALOR FIXO</option>
                </select>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="tab_4">

            <div class="row">
              <div class="form-group col-lg-4">
                <label>Usar</label>
                <select class="form-control" name="configuracao_juros_tipo">
                  <option value="0">NÃO</option>
                  <option value="1">SIM</option>
                </select>
              </div>
              <div class="form-group col-lg-4">
                <label>Taxa</label>
                <input type="text" class="form-control mask_moeda" name="configuracao_juros_taxa" autocomplete="off">
              </div>
              <div class="form-group col-lg-4">
                <label>Código</label>
                <select class="form-control" name="configuracao_juros_codigo">
                  <option value="TAXAMENSAL">TAXA MENSAL</option>
                  <option value="VALORDIA">VALOR DIA</option>
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-12" align="right">
            <button class="btn btn-primary">GRAVAR</button>
          </div>
        </div>
      </form>
    </div>

  </section>
</div>
<?php $v->start("scripts"); ?>
<script type="text/javascript">
  $('.wc_form').submit(function() {
    var Form = $(this);
    var Param = "&id=<?php echo $param['uri']; ?>";
    var Data = Form.serialize() + Param;

    $.ajax({
      url: "<?= url('configuracao/edit'); ?>",
      data: Data,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        loadOut();
        if (data[0].type === 'ok') {
          toastr.success(data[0].message);
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

  function loadUpdate() {
    var Data = "&id=<?php echo $param['uri']; ?>";
    $.ajax({
      url: "<?= url('configuracao/selectId'); ?>",
      data: Data,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        var Form = $(".wc_form");
        $.each(data, function(key, value) {
          Form.find("input[name='" + key + "'], select[name='" + key + "'], textarea[name='" + key + "']").val(value);
        });
        loadOut();
      },
      error: function(request, status, error) {
        loadOut();
      }
    });
    return false;
  }
  $(function() {
    loadUpdate();
  });
</script>
<?php $v->end(); ?>
