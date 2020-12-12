<div class="page-section">
    <section class="card card-fluid">
        <div id="listing" class="is_pannel" style="display: block;">
            <div class="listing">
                <header style="margin-bottom: 1rem">
                    <form class="search no-print" id="listing_search" method="get">
                        <ul class="search-fields search-basic">
                            <li>
                                <input type="text" autocomplete="off" size="14" maxlength="255" name="search_name" class=" listing_search string data_control" placeholder="Recipient name" value="<?= \Framework\lib\Request::Check('search_name', 'get') ? \Framework\lib\Request::Get('search_name') : '' ?>">
                            </li>
                            <li>
                                <input type="text" autocomplete="off" size="14" maxlength="255" name="search_address" class=" listing_search string data_control" placeholder="Address" value="<?= \Framework\lib\Request::Check('search_address', 'get') ? \Framework\lib\Request::Get('search_address') : '' ?>">
                            </li>
                            <li>
                                <label class="checkbox" for="listing_shipped">
                                    <input type="checkbox" name="shipped" id="listing_shipped" class=" listing_search boolean data_control" <?= \Framework\lib\Request::Check('shipped', 'get') ? 'checked' : '' ?>> Shipped
                                </label>
                            </li>
                        </ul>
                        <div class="submit">
                            <button type="submit" id="searchButton" title="Search" class="gui-def-button ">Search</button>
                        </div>
                    </form>
                </header>

                <div id="work_listing">
                    <div id="listing_single">
                        <div class="container" style="max-width: 100%">
                            <table cellpadding="0" cellspacing="0" border="0" class="g-datatable datatable table table-striped table-bordered table-status-colors">
                                <thead>
                                <tr>
                                    <th class="th-date">Date</th>
                                    <th class="th-string">Name</th>
                                    <th class="th-string">Status</th>
                                    <th class="th-boolean">Shipped</th>
                                    <th class="th-string">Shipping Date</th>
                                    <th class="th-boolean">Paid</th>
                                    <th class="th-string">Address</th>
                                    <th class="th-string">Instructions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data) && $data !== false) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <?php $item_status = $item->shipped == '1' ? 'status-grey' : 'status-orange'; ?>

                                        <tr class="gradeX <?= $item_status ?>">
                                            <td class="date ">
                                                <time datetime="<?= $item->created ?>" title="<?= \Framework\lib\Helper::ConvertDateFormat($item->created, true) ?>"><?= \Framework\lib\Helper::ConvertDateFormat($item->created) ?></time>
                                            </td>
                                            <td class="string nowrap">
                                                <a title="Edit Record" href="<?= HOST_NAME.'pos/shipment/'.$item->id ?>"><span><?= $item->firstName.' '.$item->lastName.', '.$item->companyName ?></span></a>
                                            </td>
                                            <td class="string"><span class="status-label"><?= $item->shipped == '1' ? 'Shipped' : 'Not Shipped' ?></span></td>
                                            <td class="boolean">
                                                <input type="checkbox" name="shipped" id="listing_shipped" class="boolean data_control shipment_shipped" data-id="<?= $item->id ?>" <?= $item->shipped ? 'checked' : '' ?>>
                                            </td>
                                            <td class="string"><?= $item->shipped == '1' && $item->shipped_at ? \Framework\lib\Helper::ConvertDateFormat($item->shipped_at) : 'Not Shipped' ?></td>
                                            <td class="boolean"><?= $item->sale_status == 'paid' ? 'Yes' : 'No' ?></td>
                                            <td class="string">
                                                <span><?= $item->address.', '.$item->city.' '.$item->suburb.', '.$item->zip ?></span>
                                            </td>
                                            <td class="string"><?= $item->shipping_instructions ? substr($item->shipping_instructions, 0, 80) : '' ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>