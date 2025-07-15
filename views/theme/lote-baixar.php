<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 style="border-bottom: 1px solid #CCCCCC">
                        Baixar XMLs - #<?= $_SESSION['uuid_lote']; ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label>CHAVE</label>
                    <input type="text" value="<?= get_chave($_SESSION['uuid_lote']); ?>" id="chave" class="form-control" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-block btn-primary" onclick="Consultar();">Consultar</button>
                </div>
            </div>

            <hr />
            <div class="card-body">
                <p><code>Progresso Download</code></p>
                <h3><?= get_concluido($_SESSION['uuid_lote']); ?> - Baixados</h3>
                <h3><?= get_total($_SESSION['uuid_lote']); ?> - Total</h3>
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
<script>
    $(function() {
        Consultar();
    });

    function Consultar() {

        $("#stringsFSistChave").val('');
        $("#stringsFSistUrl").val('');
        $("#stringsFSistHtml").val('');
        $("#stringsFSistDados").val('');
        $("#stringsFSistLink").val('');
        $("#stringsFSistXml").val('');

        setTimeout(() => {
            var chave = $("#chave").val();
            if (chave == '') {
                setTimeOutPage('<?php echo url(); ?>');
            } else {
                $("#stringsFSistChave").val(chave);

                if (chave.substr(20, 2) == 55) {
                    $("#stringsFSistUrl").val('https://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConsulta=resumo&tipoConteudo=7PhJ+gAVw2g=');
                } else {
                    $("#stringsFSistUrl").val('https://www.cte.fazenda.gov.br/portal/consultaRecaptcha.aspx?tipoConsulta=resumo&amp;tipoConteudo=cktLvUUKqh0=');
                }

                document.getElementById('stringsFSistClick').click();
            }

        }, "2000");
    }

    var xhr = null;

    function VerificaDownload() {
        var arquivo = $("#stringsFSistXml").val();
        var status_enviado = $("#stringsFSistHtml").val();
        if (arquivo != '') {
            if (xhr === null || xhr.readyState === 4) {
                getXML();
            }
        } else {}
    }

    function getXML() {
        var xml = $("#stringsFSistXml").val();
        var chave = $("#stringsFSistChave").val();
        var status_enviado = $("#stringsFSistHtml").val();
        var link = $("#stringsFSistLink").val();
        if (status_enviado != '') {
            var status = '1';
        } else {
            var status = '2';
        }
        var decodedString = atob(link);
        var Data = "&status=" + status + "&xml=" + xml + "&chave=" + chave + "&link=1&ide=<?= $_SESSION['uuid_lote']; ?>";
        if (xhr === null || xhr.readyState === 4) {
            xhr = $.ajax({
                url: "<?= url('app/getXmlLote'); ?>",
                data: Data,
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    loadOut();
                    if (data[0].type === 'error') {
                        alert('Tivemos um erro');
                    } else {
                        location.reload();
                    }
                },
                error: function(request, status, error) {
                    loadOut();
                }
            });
        }
        return false;
    }

    function atualizarPagina() {
        location.reload();
    }

    setInterval(VerificaDownload, 1500);
</script>
<?php $v->end(); ?>