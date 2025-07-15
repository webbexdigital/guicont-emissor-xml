<?php
$v->layout("_themesis");
?>
<div class="content-wrapper" style="background: #FFF">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 style="border-bottom: 1px solid #CCCCCC">
                        Lotes Downloads
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Identificador</th>
                    <th>Status</th>
                    <th>Quantidade</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $db = new Source\Conn\DataLayer();
                $result = $db->db()->from('lote')
                    ->where('lote_id_user')->is($_SESSION['APISIMPLES_DOWNLOAD']->user_id)
                    ->orderBy('lote_data_hora', 'desc')
                    ->select()
                    ->all();
                if ($result) {
                    foreach ($result as $r) {
                        if ($r->lote_status == '0') {
                            $status_lote = 'Pendente';
                            $resultLote = $db->db()->from('item')
                                ->where('item_id_lote')->is($r->lote_id)
                                ->andWhere('item_status')->is(0)
                                ->select(['item_id'])
                                ->all();
                            if(!$resultLote){
                                $updateLote['lote_status'] = '1';
                                $db->db()->update('lote')->where('lote_id')->is($r->lote_id)->set($updateLote);
                            }
                        } else {
                            $status_lote = 'Finalizado';
                        }
                        ?>
                        <tr>
                            <td><?= format_datetime_br($r->lote_data_hora); ?></td>
                            <td><?= $r->lote_uuid; ?></td>
                            <td><?= $status_lote; ?></td>
                            <td><?= $r->lote_quantidade; ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="download('<?= $r->lote_uuid; ?>')">Baixar
                                </button>
								<?php
									if($r->lote_status == '0'){?>
								<button class="btn btn-info btn-sm" onclick="retomar_download('<?= $r->lote_uuid; ?>')">Retomar Download</button>
								<?php	}
								?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Data/Hora</th>
                    <th>Identificador</th>
                    <th>Status</th>
                    <th>Quantidade</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>
<div id="modal-download-zip" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <hr/>
                <div class="row">
                    <input type="hidden" class="ide_nfs_download_zip"/>
                    <span class="load_info_new" style="display: none;"><img src="<?= asset("/load.gif"); ?>"/> - Aguarde fazendo o download</span>
                    <div class="col-lg-12">
                        <div class="load_retorno_download_zip"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">FECHAR</button>
            </div>
        </div>
    </div>
</div>
<?php $v->start("scripts"); ?>
<script>
    $('.wc_form').submit(function () {
        var Form = $(this);
        var Data = Form.serialize();

        $.ajax({
            url: "<?= url('app/login'); ?>",
            data: Data,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                loadOut();
                if (data[0].type === 'ok') {
                    toastr.success(data[0].message);
                    setTimeOutPage('<?php echo url(); ?>');
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

    function download(ide) {
        var Data = "&action=search_doc_nfe&uuid=" + ide;
        $.ajax({
            url: "<?= url('app/downloadZip'); ?>",
            data: Data,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $("#modal-download-zip").modal('show');
                if (data[0].type === 'ok') {
                    $(".load_retorno_download_zip").html(data.button);
                } else {
                    $(".load_retorno_download_zip").html(data[0].message);
                }
                loadOut();
            },
            error: function (request, status, error) {
                loadOut();
            }
        });
        return false;
    }
	function retomar_download(ide) {
        var Data = "&action=search_doc_nfe&uuid=" + ide;
        $.ajax({
            url: "<?= url('app/retomarDownload'); ?>",
            data: Data,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data[0].type === 'ok') {
                    setTimeOutPage('<?php echo url('lote-baixar'); ?>');
                } else {
                    toastr.warning(data[0].message);
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
<?php $v->end(); ?>
