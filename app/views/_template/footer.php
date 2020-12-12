</div>
</div>

<!-- copyright -->
<!--<footer class="auth-footer"> © 2018 All Rights Reserved.-->
<!--    <a href="#">Privacy</a> and-->
<!--    <a href="#">Terms</a>-->
<!--</footer>-->

</main>
<!-- /.app-main -->
</div>
<!-- /.app -->

<!-- BEGIN BASE JS -->
<script src="<?= VENDOR_DIR ?>bootstrap/js/popper.min.js"></script>
<script src="<?= VENDOR_DIR ?>bootstrap/js/bootstrap.min.js"></script>
<!-- END BASE JS -->

<?php if ($this->controller == 'pos') : ?>
<script src="<?= JS_DIR ?>pos.js"></script>
<?php endif; ?>

<?php if ($this->controller == 'quotes') : ?>
    <script src="<?= JS_DIR ?>quotes.js"></script>
<?php endif; ?>

<?php if ($this->controller == 'licenses') : ?>
    <script src="<?= JS_DIR ?>licenses.js"></script>
<?php endif; ?>


<!-- BEGIN PLUGINS JS -->
<script src="<?= VENDOR_DIR ?>stacked-menu/stacked-menu.min.js"></script>
<script src="<?= VENDOR_DIR ?>perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= VENDOR_DIR ?>flatpickr/flatpickr.min.js"></script>
<script src="<?= VENDOR_DIR ?>particles.js/particles.min.js"></script>
<script src="<?= VENDOR_DIR ?>select2/js/select2.min.js"></script>

<!-- BEGIN THEME JS -->
<script src="<?= JS_DIR ?>main.min.js"></script>
<!-- END THEME JS -->

<!-- BEGIN DATATABLES JS -->
<script src="<?= VENDOR_DIR ?>DataTables/datatables.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/Buttons-1.6.3/js/dataTables.buttons.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/Buttons-1.6.3/js/buttons.html5.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/Buttons-1.6.3/js/buttons.print.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/Buttons-1.6.3/js/buttons.colVis.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="<?= VENDOR_DIR ?>DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>

<script src="<?= VENDOR_DIR ?>DataTables/Responsive-2.2.5/js/dataTables.responsive.min.js"></script>

<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>-->
<!-- END DATATABLES JS -->



<script>
    function InitDatatable(className = 'g-datatable', options = '') {
        if ($('.' + className).length !== 0) {
            if ($.fn.dataTable.isDataTable('.' + className)) {
                $('.' + className).dataTable().fnDestroy();
            }

            var table = $('.' + className).DataTable({
                options,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print', 'colvis'
                ]
            });

            $('.' + className).each(function() {
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search');
                search_input.addClass('form-control input-sm');
                // LENGTH - Inline-Form control
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.addClass('form-control input-sm');
            });

            return table;
        }
    }

    $(document).ready(function() {
        // $.fn.dataTable.ext.errMode = 'none';
        InitDatatable();

        $(document).on('click', ".toggle-password", function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $this.data('id');
            var action = $this.data('function');

            $.ajax({
                type: "POST",
                url: "/ajax/"+action+"_delete/" + id,
                data: '',
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                success: function (data) {
                    if (data.status == '1') {
                        $this.closest('.gradeX').remove();
                    } else {
                        showFeedback('error', data.msg);
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        });

        $(document).on('click', '.ajax-delete', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $this.data('id');
            var action = $this.data('classname');

            $.ajax({
                type: "POST",
                url: "/ajax/delete/"+id+"?target="+action,
                data: '',
                dataType: 'json',
                beforeSend: function () {
                    Pace.restart();
                },
                success: function (data) {
                    if (data.status == '1') {
                        $this.closest('.gradeX').remove();
                    } else {
                        showFeedback('error', data.msg);
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        });
    });
</script>


</body>
</html>