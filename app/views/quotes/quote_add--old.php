<tr class="tr__product" id="cooler-tr" data-class="cooler-tr" data-component="CPU Cooler">
    <?php $cooler = preg_grep('~' . preg_quote('Fan & Cooling', '~') . '~', $categories) ?>
    <?php $cooler_val = array_shift($cooler); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $cooler_val ?>">CPU Cooler</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $cooler_val ?>">
            <i class="fa fa-plus"></i>
            Choose A CPU Cooler
        </button>
    </td>
</tr>
<tr class="tr__product" id="Motherboard-tr" data-class="Motherboard-tr" data-component="Motherboard">
    <?php $Motherboard = preg_grep('~' . preg_quote('Motherboards', '~') . '~', $categories) ?>
    <?php $Motherboard_val = array_shift($Motherboard); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Motherboard_val ?>">Motherboard</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Motherboard_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Motherboard
        </button>
    </td>
</tr>
<tr class="tr__product" id="Memory-tr" data-class="Memory-tr" data-component="Memory">
    <?php $Memory = preg_grep('~' . preg_quote('Memory', '~') . '~', $categories) ?>
    <?php $Memory_val = array_shift($Memory); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Memory_val ?>">Memory</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Memory_val ?>">
            <i class="fa fa-plus"></i>
            Choose Memory
        </button>
    </td>
</tr>
<tr class="tr__product" id="Storage-tr" data-class="Storage-tr" data-component="Storage">
    <?php $Storage = preg_grep('~' . preg_quote('Hard Drives', '~') . '~', $categories) ?>
    <?php $Storage_val = array_shift($Storage); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Storage_val ?>">Storage</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Storage_val ?>">
            <i class="fa fa-plus"></i>
            Choose Storage
        </button>
    </td>
</tr>
<tr class="tr__product" id="Card-tr" data-class="Card-tr" data-component="Video Card">
    <?php $Card = preg_grep('~' . preg_quote('Video/Graphics Cards', '~') . '~', $categories) ?>
    <?php $Card_val = array_shift($Card); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Card_val ?>">Video Card</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Card_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Video Card
        </button>
    </td>
</tr>
<tr class="tr__product" id="Case-tr" data-class="Case-tr" data-component="Case">
    <?php $Case = preg_grep('~' . preg_quote('Cases & Accessories', '~') . '~', $categories) ?>
    <?php $Case_val = array_shift($Case); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Case_val ?>">Case</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Card_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Case
        </button>
    </td>
</tr>
<tr class="tr__product" id="Supply-tr" data-class="Supply-tr" data-component="Power Supply">
    <?php $Supply = preg_grep('~' . preg_quote('Power Supplies', '~') . '~', $categories) ?>
    <?php $Supply_val = array_shift($Supply); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Supply_val ?>">Power Supply</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Supply_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Power Supply
        </button>
    </td>
</tr>
<tr class="tr__product" id="Drive-tr" data-class="Drive-tr" data-component="Optical Drive">
    <?php $Drive = preg_grep('~' . preg_quote('CDR/RW & DVDR/RW', '~') . '~', $categories) ?>
    <?php $Drive_val = array_shift($Drive); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Drive_val ?>">Optical Drive</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Drive_val ?>">
            <i class="fa fa-plus"></i>
            Choose An Optical Drive
        </button>
    </td>
</tr>
<tr class="tr__product" id="System-tr" data-class="System-tr" data-component="Operating System">
    <?php $System = preg_grep('~' . preg_quote('Software', '~') . '~', $categories) ?>
    <?php $System_val = array_shift($System); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $System_val ?>">Operating System</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $System_val ?>">
            <i class="fa fa-plus"></i>
            Choose An Operating System
        </button>
    </td>
</tr>
<tr class="tr__product" id="Software-tr" data-class="Software-tr" data-component="Software">
    <?php $Software = preg_grep('~' . preg_quote('Software', '~') . '~', $categories) ?>
    <?php $Software_val = array_shift($Software); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Software_val ?>">Software</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Software_val ?>">
            <i class="fa fa-plus"></i>
            Choose Software
        </button>
    </td>
</tr>
<tr class="tr__product" id="Monitor-tr" data-class="Monitor-tr" data-component="Monitor">
    <?php $Monitor = preg_grep('~' . preg_quote('Monitors & Projectors', '~') . '~', $categories) ?>
    <?php $Monitor_val = array_shift($Monitor); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Monitor_val ?>">Monitor</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Monitor_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Monitor
        </button>
    </td>
</tr>
<tr class="tr__product" id="Storage-tr" data-class="Storage-tr" data-component="External Storage">
    <?php $Storage = preg_grep('~' . preg_quote('Hard Drives - External', '~') . '~', $categories) ?>
    <?php $Storage_val = array_shift($Storage); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Storage_val ?>">External Storage</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Storage_val ?>">
            <i class="fa fa-plus"></i>
            Choose External Storage
        </button>
    </td>
</tr>
<tr class="tr__product" id="Laptop-tr" data-class="Laptop-tr" data-component="Laptop">
    <?php $Laptop = preg_grep('~' . preg_quote('Notebooks', '~') . '~', $categories) ?>
    <?php $Laptop_val = array_shift($Laptop); ?>
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="<?= $Laptop_val ?>">Laptop</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="<?= $Laptop_val ?>">
            <i class="fa fa-plus"></i>
            Choose A Laptop
        </button>
    </td>
</tr>
<tr class="tr__product" id="Accessories-tr" data-class="Accessories-tr" data-component="Accessories / Other">
    <td class="td__component">
        <a href="#" class="select-part-btn" data-category="">Accessories / Other</a>
    </td>
    <td class="td__addComponent" colspan="6">
        <button class="btn btn-primary select-part-btn" data-category="">
            <i class="fa fa-plus"></i>
            Accessories / Other
        </button>
    </td>
</tr>

"    <td>"+data[i].AT+" - "+data[i].AA+" - "+data[i].AQ+" - "+data[i].AN+" - "+data[i].AV+" - "+data[i].AW+" - "+data[i].ETAA+" - "+data[i].ETAQ+" - "+data[i].ETAN+" - "+data[i].ETAV+" - "+data[i].ETAW+"</td>\n" +


<script>
    $('.items-table tbody').on('click', 'td .details-control', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row(tr);
        var $item_id = tr.data('item-id');

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            $.ajax({
                type: "POST",
                url: "/ajax/get_leaderItem",
                data: {id: $item_id},
                dataType: 'json',
                success: function (data) {
                    if (data !== false) {
                        var child_html = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                            '<tr><td>Category: </td><td>'+data.CategoryName+'</td></tr>'+
                            '<tr><td>Sub-Category: </td><td>'+data.SubcategoryName+'</td></tr>'+
                            '<tr><td>Description: </td><td>'+data.Description+'</td></tr>'+

                            '<tr><td>ADL: </td><td>';
                        if (data.AA == 'CALL') {
                            child_html += data.ETAA !== null ? data.ETAA : 'CALL';
                        } else {
                            child_html += data.AA;
                        }
                        child_html += '</td></tr>\n' +
                            '<tr><td>SYD: </td><td>';
                        if (data.AQ == 'CALL') {
                            child_html += data.ETAQ !== null ? data.ETAQ : 'CALL';
                        } else {
                            child_html += data.AQ;
                        }
                        child_html += '</td></tr>\n' +
                            '<tr><td>BRS: </td><td>';
                        if (data.AN == 'CALL') {
                            child_html += data.ETAN !== null ? data.ETAN : 'CALL';
                        } else {
                            child_html += data.AN;
                        }
                        child_html += '</td></tr>\n' +
                            '<tr><td>MEL: </td><td>';
                        if (data.AV == 'CALL') {
                            child_html += data.ETAV !== null ? data.ETAV : 'CALL';
                        } else {
                            child_html += data.AV;
                        }
                        child_html += '</td></tr>\n' +
                            '<tr><td>WA: </td><td>';
                        if (data.AW == 'CALL') {
                            child_html += data.ETAW !== null ? data.ETAW : 'CALL';
                        } else {
                            child_html += data.AW;
                        }
                        child_html += '</td></tr>\n' +
                            '<tr><td>Warranty Length: </td><td>'+data.WarrantyLength+'</td></tr>\n' +
                            '</table>';

                        // Open this row
                        row.child(child_html).show();
                        tr.addClass('shown');
                    } else {
                        showFeedback('error', "Sorry something wrong happened!");
                    }
                },
                fail: function (err) {
                    showFeedback('error', err.responseText);
                }
            });
        }
    });

    $.ajax({
        type: "POST",
        url: "/ajax/search_leaderItems",
        data: {category: category, subcategory: subcategory},
        dataType: 'json',
        beforeSend: function () {
            Pace.restart();
        },
        success: function (data) {
            var $html = '';
            for (var i = 0; i < data.length; i++) {
                $html += "<tr data-item-id='"+data[i].id+"'>\n" +
                    "    <td>";
                $html += data[i].IMAGE ? "<a href='#' class='preview-img'><img src='"+data[i].IMAGE+"'></a>"+data[i].ProductName+"" : data[i].ProductName;
                $html +=
                    ""+data[i].ProductName+"</td>\n" +
                    "    <td>"+data[i].StockCode+"</td>\n" +
                    "    <td>"+data[i].Manufacturer+"</td>\n" +
                    "    <td>$"+formatMoney(parseFloat(data[i].DBP))+"</td>\n" +
                    "    <td>$"+formatMoney(parseFloat(data[i].RRP))+"</td>\n" +
                    "    <td>" +
                    "        <input type=\"number\" class=\"display_quantity number xx-small\" value=\"1\">\n" +
                    "    </td>\n" +
                    "    <td>" +
                    "       <button type='button' title='Expand' class='details-control component-control'><i class='fa fa-expand'></i> </button>" +
                    "       <button title=\"Add\" type='button' class='add-item-to-quote component-control' data-item-id='"+data[i].id+"'><i class=\"fa fa-plus \"></i> </button>\n" +
                    "    </td>\n" +
                    "</tr>";
            }

            if ($.fn.dataTable.isDataTable('.items-table')) {
                $('.items-table').dataTable().fnDestroy();
            }
            $('.items-table tbody').html($html);
            datatable = InitDatatable('items-table');

        },
        fail: function (err) {
            showFeedback('error', err.responseText);
        }
    });
</script>