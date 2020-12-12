<div class="page-section">
    <div class="row">
        <div class="col-md-8" style="margin: auto;">
            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <fieldset>
                            <legend>Generate Insurance Report</legend>
                            <hr class="my-3">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="custom-form-label">Reference</label>
                                        <input type="text" class="form-control form-round" name="reference" placeholder="Reference">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="custom-form-label">Report Info</label>
                                        <textarea name="info" class="summernote form-control form-round" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" name="save" class="btn btn-primary" value="Generate & Send">
                                <input type="submit" name="preview" class="btn btn-success" style="float: right" value="Preview">
                            </div>
                        </fieldset>

                        <div class="modal fade" id="select-insurance-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Select Insurance Email</h5>
                                    </div>
                                    <div class="modal-body">
                                        <fieldset>
                                            <div class="form-group">
                                                <label for="companies" class="custom-form-label">Insurance Company</label>
                                                <select class=" form-control" name="company" id="companies">
                                                <?php if (isset($companies) && $companies) : ?>
                                                    <option selected>Select Company</option>
                                                    <?php foreach ($companies as $company) : ?>
                                                        <?php if (isset($selected_company) && $selected_company !== null && strtolower($selected_company) == strtolower($company->name)) : ?>
                                                            <option selected value="<?= $company->id ?>"><?= $company->name ?></option>
                                                        <?php else : ?>
                                                            <option value="<?= $company->id ?>"><?= $company->name ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                </select>
                                            </div>

                                            <hr>

                                            <div class="form-group" id="selected-company-emails"></div>
                                        </fieldset>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= VENDOR_DIR ?>summernote/summernote-bs4.css">
<link rel="stylesheet" href="<?= VENDOR_DIR ?>select2/css/select2.min.css"/>

<script src="<?= VENDOR_DIR ?>summernote/summernote-bs4.min.js"></script>
<script src="<?= VENDOR_DIR ?>select2/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $("#companies").select2();

        $('.summernote').summernote({
            placeholder: 'Write info here...',
            height: 200,
            callbacks: {
                onInit: function onInit(e) {
                    var editor = $(e.editor);
                    editor.find('.custom-control-description').addClass('custom-control-label d-block').parent().removeAttr('for');
                }
            }
        });

        $(document).on('change', '#companies', function (e) {
            let $selected = $('#companies').val();
            $.ajax({
                url: "<?= HOST_NAME ?>admin/get_company_emails/" + $selected,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    if (data) {
                        var options = '';
                        for (var i = 0; i < data.length; i++) {
                            options += "<div class='custom-control custom-control-inline custom-checkbox'>\n" +
                                        "    <input type='checkbox' class='custom-control-input' name='selected-emails[]' value='"+data[i].email+"' id='company-email-"+data[i].id+"'>\n" +
                                        "    <label class='custom-control-label' for='company-email-"+data[i].id+"'>"+data[i].email+"</label>\n" +
                                        "</div>";
                        }
                        $('#selected-company-emails').html(options);
                    } else {
                        $('#selected-company-emails').html("<span>No saved emails for selected company!</span>");
                    }
                }
            });
        });

        $("form").submit(function(e) {
            var clicked = $(document.activeElement).attr('name');
            if (clicked == 'preview') {
                $('form').attr("target","_blank");
            } else if (clicked == 'save') {
                e.preventDefault();
                $('#select-insurance-modal').modal();
                $('form').attr("target","");
            }
        });

        $("#companies").trigger('change');
    });
</script>
<style>
    .select2-container {width: 100% !important;}
</style>