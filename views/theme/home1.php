<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 style="border-bottom: 1px solid #CCCCCC">
            Baixar XML de NFe/CTe
          </h1>
          <small>Download de xml e DANFe com a chave e certificado digital. Gratuito para uma chave por vez.</small>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="container-fluid">
      <form class="wc_form" action="" method="POST">
        <div class="row">
          <div class="form-group col-lg-12">
            <label>CHAVE</label>
            <input type="text" value="35231217642282000123550010010569891142110714" id="chave" class="form-control" autocomplete="off">
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <button type="button" class="btn btn-block btn-primary" onclick="Consultar();">Consultar</button>
          </div>
        </div>
        <hr />
      </form>
      <div class="row" style="display: none;" id="consultado">
        <div class="col-lg-6">
          <button type="button" class="btn btn-block btn-info" onclick="getPDF();"><img src="<?= asset("icon/impressora.png"); ?>" width="64" /><br />Imprimir</button>
        </div>
        <div class="col-lg-6">
          <button type="button" class="btn btn-block btn-primary" onclick="getXML();"><img src="<?= asset("icon/download-direto.png"); ?>" width="64" /><br />Download XML</button>
        </div>
      </div>



      <input type="hidden" id="stringsFSistChave" />
      <input type="hidden" id="stringsFSistUrl" />
      <input type="hidden" id="stringsFSistClick" />
      <input type="hidden" id="stringsFSistHtml" />
      <input type="hidden" id="stringsFSistDados" />
      <input type="hidden" id="stringsFSistLink" />
      <input type="hidden" id="stringsFSistXml" />
      <input type="hidden" id="stringsFSistVersao" />

      <hr />
      <input id="strings1" type="hidden" />
      <input id="strings2" type="hidden" />
      <input id="strings3" type="hidden" />
    </div>

  </section>
</div>
<?php $v->start("scripts"); ?>
<script type="text/javascript">
  function getXML() {
    var xml = $("#stringsFSistXml").val();
    var chave = $("#stringsFSistChave").val();

    var link = $("#stringsFSistLink").val();
    var Data = "&xml=" + xml + "&chave=" + chave + "&link=" + link;
    $.ajax({
      url: "<?= url('app/getXml'); ?>",
      data: Data,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        loadOut();
        if (data[0].type === 'error') {
          alert('Tivemos um erro');
        } else {
          window.open(data.link, '_blank');
        }
      },
      error: function(request, status, error) {
        loadOut();
      }
    });
    return false;
  }

  function getPDF() {
    var xml = $("#stringsFSistXml").val();
    var chave = $("#stringsFSistChave").val();

    var Data = "&xml=" + xml + "&chave=" + chave;
    $.ajax({
      url: "<?= url('app/getPdf'); ?>",
      data: Data,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        loadOut();
        if (data[0].type === 'error') {
          alert('Tivemos um erro');
        } else {
          window.open(data.link, '_blank');
        }
      },
      error: function(request, status, error) {
        loadOut();
      }
    });
    return false;
  }

  function Consultar() {

    $("#stringsFSistChave").val('');
    $("#stringsFSistUrl").val('');
    $("#stringsFSistHtml").val('');
    $("#stringsFSistDados").val('');
    $("#stringsFSistLink").val('');
    $("#stringsFSistXml").val('');

    setTimeout(() => {
      var chave = $("#chave").val();
      $("#stringsFSistChave").val(chave);

      if (chave.substr(20, 2) == 55) {
        $("#stringsFSistUrl").val('https://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConsulta=resumo&tipoConteudo=7PhJ+gAVw2g=');
      } else {
        $("#stringsFSistUrl").val('https://www.cte.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConsulta=resumo&amp;tipoConteudo=cktLvUUKqh0=');
      }

      document.getElementById('stringsFSistClick').click();
    }, "700");
  }

  function VerificaDownload() {
    var arquivo = $("#stringsFSistXml").val();
    if (arquivo != '') {
      $("#consultado").show();
    } else {
      $("#consultado").hide();
    }
  }
  setInterval(VerificaDownload, 3500);
</script>
<?php $v->end(); ?>
