<div class="page-section">
    <section class="card card-fluid">
        <header class="card-header">
            Quotes Template

            <a href="<?= PUBLIC_DIR.'Quote.docx' ?>" class="btn btn-info" style="float: right">Preview Current Quotes Template</a>
        </header>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Upload Quotes Template</legend>
                    <hr class="my-3">

                    <div class="form-group">
                        <label class="custom-form-label">Template (Doc, Docx)</label>
                        <input type="file" name="template" class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </section>
</div>
