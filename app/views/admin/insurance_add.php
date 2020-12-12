<div class="page-section">
    <div class="row">
        <div class="col-md-8">
            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <fieldset>
                            <legend>Add New Insurance Company</legend>
                            <hr class="my-3">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="custom-form-label">Company Name*</label>
                                        <input type="text" class="form-control form-round" name="name" placeholder="Company Name" required>
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="custom-form-label">Email*</label>
                                        <input type="email" name="emails[]" class="form-control form-round" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success add-email-btn" type="button" style="margin-top: 28px; float: right">Add Email</button>
                                </div>
                            </div>
                            <div class="extra-emails"></div>

                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Add New Company</button>
                            </div>
                        </fieldset>
                    </form>
                </div>

            </section>
        </div>
    </div>
</div>

<script>
    function removeCompany(count) {
        $('.extra-email-row-'+count).remove();
    }
    $(document).ready(function () {
        $(document).on('click', '.add-email-btn', function (e) {
            var count = $('.extra-email-row').length;
            $('.extra-emails').append(
                "<div class=\"extra-email-row extra-email-row-"+count+" row\">\n" +
                "<div class=\"col-md-10\">\n" +
                "    <div class=\"form-group\">\n" +
                "        <label class=\"custom-form-label\">Email "+ (count + 2) +"</label>\n" +
                "        <input type=\"email\" name=\"emails[]\" class=\"form-control form-round\" placeholder=\"Email\">\n" +
                "    </div>\n" +
                "</div>\n" +
                "<div class=\"col-md-2\">\n" +
                "    <button type=\"button\" class=\"btn btn-danger\" style=\"margin-top: 28px; float: right\" onclick='removeCompany("+ count +")'>Remove</button>\n" +
                "</div></div>"
            )
        });
    });
</script>
<style>

</style>