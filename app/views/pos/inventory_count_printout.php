<html>
<head>
    <title>Inventory Item List</title>
    <style type="text/css">
        td {
            border-bottom: dashed 1px #000;
        }
        th.section {
            text-align: left;
        }
        table {
            style="page-break-after: always;
        }
    </style>
<body>
<table>
    <tbody><tr>
        <th colspan="3" class="section">
            <h2>
            </h2>
        </th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Count</th>
        <th>Description</th>
    </tr>
    <?php if (isset($inventory_count_printout_items) && $inventory_count_printout_items) : ?>
    <?php foreach ($inventory_count_printout_items as $inventory_count_printout_item) : ?>
        <tr>
            <td align="right"><?= $inventory_count_printout_item->second_id ?></td>
            <td><div style="position: relative; width: 40px; height: 25px; border: solid 1px #000;"></div></td>
            <td><?= $inventory_count_printout_item->item ?></td>
        </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>