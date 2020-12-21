<?php

use Framework\models\pos\ItemsModel;

?>
<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Email Templates

            <a href="<?= HOST_NAME ?>licenses/template_add" class="btn btn-success" style="float: right">Add Template</a>
        </header>
        <div class="card-body">
            <table cellpadding="0" cellspacing="0" border="0" class="licenses-table table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="20%">Template Name</th>
                    <th>Assigned for</th>
                    <th>Created</th>
                    <th style=""></th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($data) && $data !== false) : ?>
                    <?php foreach ($data as $item) : ?>
                        <tr class="gradeX">
                            <td><a data-template="<?= $item->template_name ?>" class="view-template-btn" href="#"><?= $item->template_name ?></a></td>
                            <td><?= $item->products ?></td>
                            <td><?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?></td>
                            <td style="text-align: center">
                                <a href="<?= HOST_NAME.'licenses/template/'.$item->id ?>" title="Edit Template"><i class="fa fa-edit"></i></a>

                                <?php if ($item->template_name != 'Your-Subscription-is-about-to-expire' && $item->template_name != 'Your-Subscription-has-expired') : ?>
                                    <a href="#" data-id="<?= $item->id ?>" data-classname="licenses\digital_licenses_templates" data-extra-action="digital_licenses_template_delete" class="ajax-delete" title="Delete Template"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="template-preview-modal" style="overflow: auto">
    <div class="modal-dialog" role="document" style="max-width: 65%">
        <div class="modal-content">
            <div class="modal-header bg-info-dark">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <iframe id="template_preview_iframe" name="template_preview_iframe" width="100%" height="400px" style="max-width: 95%%; border: 1px solid #e6e6e6; padding: 0;"></iframe>
            </div>

            <div class="modal-footer" style="margin-top: 0">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.view-template-btn').on('click', function (e) {
            var template = $(this).data('template');
            $('#template_preview_iframe').attr('src', '/licenses/template_preview/'+template);
            $('#template-preview-modal').modal();
        });
    });
</script>

