<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 style="border-bottom: 1px solid #CCCCCC">
                        Inserir Lote Download
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
                        <label>CHAVE</label>
                        <textarea name="texto" class="form-control" cols="5" rows="5"></textarea>
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
<script>
    $('.wc_form').submit(function () {
        var Form = $(this);
        var Data = Form.serialize();

        $.ajax({
            url: "<?= url('app/insertLote'); ?>",
            data: Data,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                loadOut();
                if (data[0].type === 'ok') {
                    toastr.success(data[0].message);
                    setTimeOutPage('<?php echo url('lote-baixar'); ?>');
                } else {
                    toastr.warning(data[0].message);
                }

            },
            error: function (xhr, err) {
                loadOut();
            },
            complete: function () {
                loadOut();
            }
        });
        return false;
    });
</script>
<?php $v->end(); ?>
