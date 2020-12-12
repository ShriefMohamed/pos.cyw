<link rel="stylesheet" href="<?= CSS_DIR ?>pos-vendor-return.css">
<?php if (isset($order) && $order !== false) : ?>
<div class="react-page cirrus" style="margin-bottom: 6rem;">
    <div class="cr-layout cr-sans-serif">
        <form method="post">
            <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $order->vendor_id ?>">

            <div class="css-1dtfg85 e1cfhp5b0">
                <div class="cr-group cr-justify-between cr-width-full">
                    <div class="cr-group__item">
                        <div class="cr-group">
                            <div class="cr-group__item">
                                <button type="submit" name="save" class="cr-button cr-button-functions-save">Save changes</button>
                            </div>
                        </div>
                    </div>
                    <div class="cr-group__item">
                        <div class="cr-group">
                            <div class="cr-group__item">
                                <?php if ($order->status == 'sent') : ?>
                                <button type="submit" name="closed" class="cr-button cr-button-functions-send" style="margin-right: 15px;">Mark as closed</button>
                                <?php endif; ?>

                                <?php if ($order->status == 'open') : ?>
                                <button type="button" data-toggle="modal" data-target="#send-to-vendor-modal" class="cr-button cr-button-functions-send">Send to vendor</button>
                                <?php else : ?>
                                <button type="submit" name="open" class="cr-button cr-button-functions-send">Re-open</button>
                                <?php endif; ?>
                            </div>

                            <?php if ($order->status !== 'archived') : ?>
                            <div class="cr-group__item">
                                <button type="button" title="Archive" class="cr-button cr-button-functions-archive" onclick="window.location.href='<?= HOST_NAME.'ajax/archive/'.$order->id.'?target=pos\vendor_return&returnURL=pos/vendor_returns' ?>'">Archive</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cr-layout__wrapper">
                <div class="cr-layout__content css-dcfdoa cr-max-width-content">
                    <div class="cr-width-full">
                        <div class="cr-flex cr-mb-3">
                            <p class="cr-text-base cr-text--heading2 cr-text--heading-color">Vendor return <var>#<?= $order->id ?></var></p>
                            <a href="#" title="Edit vendor" class="cr-text-link cr-ph-2 cr-pv-1" data-toggle="modal" data-target="#add-vendor-modal">Edit</a>
                        </div>

                        <div class="css-flnyne ex3sa8n0">
                            <div class="css-1bfs6mz ex3sa8n1">
                                <div class="cr-mr-3">
                                    <p class="cr-text-base cr-text--heading4 cr-text--heading-color cr-pb-3">From shop</p>
                                    <div class="cr-text-base cr-text--body css-542wex"><var>Compute your world</var></div>
                                </div>
                                <svg class="cr-icon cr-icon-arrow-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" style="width: 1rem; height: 1rem;">
                                    <path class="cr-icon__base cr-icon__base-1" d="M9.001 12.5a.5.5 0 0 0 .834.372l4.972-4.69a.25.25 0 0 0 0-.364l-4.972-4.69A.5.5 0 0 0 9 3.5v2a.5.5 0 0 1-.5.5H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6.5a.5.5 0 0 1 .5.5l.001 2z"></path>
                                </svg>
                                <div class="cr-ml-3">
                                    <p class="cr-text-base cr-text--heading4 cr-text--heading-color cr-pb-3">To vendor</p>
                                    <div class="cr-group">
                                        <div class="cr-group__item">
                                            <div class="cr-text-base cr-text--body css-542wex" id="vendor_name"><?= isset($order->vendor_name) && $order->vendor_name ? $order->vendor_name : "No vendor selected" ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="css-lenssw ex3sa8n2">
                                <div>
                                    <p class="cr-text-base cr-text--heading4 cr-text--heading-color cr-pb-3">Status</p>

                                    <?php $status_class = 'cr-bg-snow-300 cr-night-300'; ?>
                                    <?php if ($order->status == 'sent') : $status_class = 'cr-badge--warning'; ?>
                                    <?php elseif ($order->status == 'closed') : $status_class = 'cr-badge--success'; ?>
                                    <?php elseif ($order->status == 'archived') : $status_class = 'cr-badge--danger'; ?>
                                    <?php endif; ?>
                                    <span class="cr-badge <?= $status_class ?>"><?= $order->status ?></span>
                                </div>
                                <div class="cr-ml-3 cr-pl-3 cr-border-gray-100 cr-border-left-1">
                                    <p class="cr-text-base cr-text--heading4 cr-text--heading-color cr-pb-3">Created by</p>
                                    <div class="cr-text-base cr-text--body css-542wex"><var><?= $order->admin_name ?></var></div>
                                </div>
                            </div>
                        </div>

                        <div class="cr-card cr-mb-3">
                            <div class="css-1xhj18k elaxqou0">
                                <div class="css-kebguf elaxqou1">
                                    <div class="cr-form-field cr-mb-2">
                                        <label>
                                            <div class="cr-text-base cr-text--body-small cr-text--body-color cr-text--bold cr-form-field__text-label">Reference number</div>
                                            <div class="cr-input__container">
                                                <label class="cr-input__wrapper">
                                                    <input name="refNum" maxlength="255" class="cr-input" value="<?= $order->reference ?>">
                                                    <div class="cr-input__backdrop"></div>
                                                </label>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="cr-form-field">
                                        <label>
                                            <div class="cr-text-base cr-text--body-small cr-text--body-color cr-text--bold cr-form-field__text-label">Notes</div>
                                            <div class="cr-textarea__container">
                                                <label class="cr-textarea__wrapper">
                                                    <textarea rows="4" name="notes" class="cr-textarea"><?= $order->notes ?></textarea>
                                                    <div class="cr-textarea__backdrop"></div>
                                                </label>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="css-1c41n0j elaxqou2">
                                    <p class="cr-text-base cr-text--heading4 cr-text--heading-color cr-text-center" style="margin-bottom: 12px;">Cost summary</p>
                                    <div class="cr-flex cr-items-center cr-justify-between cr-mb-2">
                                        <p class="cr-text-base">Return value</p>
                                        <p class="cr-text-base cr-text--body" id="returnValue"><var>$<?= number_format($order->return_value, 2) ?></var></p>
                                    </div>
                                    <div class="cr-flex cr-items-center cr-justify-between cr-mb-2">
                                        <p class="cr-text-base">Shipping</p>
                                        <div class="cr-ml-2">
                                            <div class="cr-form-field">
                                                <label for="shipCost">
                                                    <div class="cr-input__container">
                                                        <label for="shipCost" class="cr-input__wrapper">
                                                            <div class="cr-input__prefix">$</div>
                                                            <input id="shipCost" name="shipCost" type="text" class="cr-input cr-input--has-prefix" value="<?= number_format($order->shipping, 2) ?>" style="text-align: right;">
                                                            <div class="cr-input__backdrop"></div>
                                                        </label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cr-flex cr-items-center cr-justify-between cr-mb-2">
                                        <p class="cr-text-base">Other</p>
                                        <div class="cr-ml-2">
                                            <div class="cr-form-field">
                                                <label for="otherCost">
                                                    <div class="cr-input__container">
                                                        <label for="otherCost" class="cr-input__wrapper">
                                                            <div class="cr-input__prefix">$</div>
                                                            <input id="otherCost" name="otherCost" type="text" class="cr-input cr-input--has-prefix" value="<?= number_format($order->other, 2) ?>" style="text-align: right;">
                                                            <div class="cr-input__backdrop"></div>
                                                        </label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cr-mb-2 css-12xxi3w-Divider e1os1nwj0"></div>
                                    <div class="cr-flex cr-items-center cr-justify-between">
                                        <p class="cr-text-base cr-text--body-large cr-text--body-color cr-text--bold">Total</p>
                                        <p class="cr-text-base cr-text--body-large cr-text--body-color cr-text--bold" id="totalReturnValue">$<?= number_format($order->total, 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <p class="cr-text-base cr-text--heading3 cr-text--heading-color cr-pb-2">Add items</p>
                        <div class="cr-group cr-group--no-spacing cr-mb-4">
                            <div class="cr-group__item cr-group__item--input-block">
                                <div class="cr-input__container">
                                    <label class="cr-input__wrapper">
                                        <input type="text" placeholder="Item search" class="cr-input itembarcode" id="add_search_item_text">
                                        <div class="cr-input__backdrop"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="cr-group__item">
                                <button class="barcodesubmit ExtendedBaseButton css-m6v84y-ExtendedBaseButton e1eugw0d1" type="button" id="registerItemSearch" data-item-action="vendor-return">
                                    <span class="css-lv59si-ChildWrapper-ChildWrapper e1eugw0d4">
                                        <span class="css-kw31c7-ButtonContent e1eugw0d3">Add item</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div></div>


                        <div class="css-bwd6rl ertzig73">
                            <p class="cr-text-base cr-text--heading3 cr-text--heading-color css-1rr4qq7 ertzig74">Items</p>
                        </div>


                        <div class="cr-card">
                            <div class="">
                                <fieldset>
                                    <table class="cr-table cr-table--normal">
                                        <thead>
                                        <tr class="cr-table__head-row">
                                            <th style="width: 3rem" class="cr-table__header-cell cr-text-left">&nbsp;</th>
                                            <th style="width: 3rem" class="cr-table__header-cell cr-text-left">&nbsp;</th>
                                            <th class="cr-table__header-cell cr-text-left">ITEM</th>
                                            <th class="cr-table__header-cell cr-text-left" style="width: 8rem">PO</th>
                                            <th class="cr-table__header-cell cr-text-left" style="width: 8rem">REASON</th>
                                            <th class="cr-table__header-cell cr-text-center" style="width: 6.5rem">STOCK</th>
                                            <th class="cr-table__header-cell cr-text-center" style="width: 7.5rem">RETURN QTY.</th>
                                            <th class="cr-table__header-cell cr-text-right" style="width: 7rem">COST</th>
                                            <th class="cr-table__header-cell cr-text-right" style="width: 8rem">SUBTOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody id="register_transaction" class="register_transaction">
                                        <?php if (isset($order_items) && $order_items !== false) : ?>
                                            <?php foreach ($order_items as $order_item) : ?>
                                                <tr class="cr-table__body-row vendor-return-item vendor-return-item-<?= $order_item->item_id ?> ">
                                                    <td class="cr-table__body-cell cr-text-left cr-table__body-cell--normal cr-table__body-cell--first lf" style="width: 3rem;"></td>
                                                    <td class="cr-table__body-cell cr-text-left cr-table__body-cell--normal" style="width: 3rem; vertical-align: middle;">#<?= $order_item->id ?></td>
                                                    <td class="cr-table__body-cell cr-text-left notranslate cr-table__body-cell--first cr-table__body-cell--normal" style="vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem; line-height: 0.75rem; text-overflow: ellipsis; overflow: hidden;">
                                                        <span class="cr-text-base cr-text--body-small cr-text--body-color cr-text--bold notranslate">
                                                            <a href="/pos/item/<?= $order_item->item_id ?>" target="_blank" title="Edit Record" class="cr-text-link"><?= $order_item->item ?></a>
                                                        </span>
                                                    </td>

                                                    <td class="cr-table__body-cell cr-text-left cr-table__body-cell--normal display_purchase_order" style="width: 8rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;">
                                                        <?php if ($order_item->purchase_order_item_id && $order_item->purchase_order_item_id !== 0) : ?>
                                                            <div class="dropright">
                                                                <div role="button" class="css-153ki3p ertzig70 dropdown-toggle" id="dropdown-btn-<?= $order_item->item_id ?>" data-toggle="dropdown" aria-haspopup="true">24
                                                                    <input type="hidden" name="vr-items[<?= $order_item->item_id ?>][purchase_order_item_id]" value="7">
                                                                </div>
                                                                <div class="dropdown-menu" aria-labelledby="dropdown-btn-<?= $order_item->item_id ?>">
                                                                    <a class="dropdown-item" target="_blank" href="/pos/purchase_order/<?= $order_item->purchase_order_item_id ?>"><i class="fa fa-edit"></i> View PO</a>
                                                                    <a class="dropdown-item associate-purchase-order-btn" href="#" data-item-id="<?= $order_item->item_id ?>"><i class="fa fa-redo-alt"></i> Select other PO</a>
                                                                    <a class="dropdown-item disassociate-purchase-order-btn" href="#" data-item-id="<?= $order_item->item_id ?>"><i class="fa fa-trash"></i> Remove PO</a>
                                                                </div>
                                                            </div>
                                                        <?php else : ?>
                                                        <button class="css-t86oos-ExtendedBaseButton ExtendedBaseButton associate-purchase-order-btn" type="button" title="Select a purchase order to associate with this line." data-item-id="<?= $order_item->item_id ?>">
                                                            <span class="css-kw31c7-ButtonContent e1eugw0d3">Select PO</span>
                                                        </button>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="cr-table__body-cell cr-text-left cr-table__body-cell--first cr-table__body-cell--normal" style="width: 8rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;">
                                                        <select class="cr-select" name="vr-items[<?= $order_item->item_id ?>][return_reason]">
                                                            <option value="1">None</option>
                                                            <?php if (isset($return_reasons) && $return_reasons) : ?>
                                                            <?php foreach ($return_reasons as $return_reason) : ?>
                                                            <option <?= $return_reason->id == $order_item->return_reason_id ? 'selected' : '' ?> value="<?= $return_reason->id ?>"><?= $return_reason->reason ?></option>
                                                            <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </td>
                                                    <td class="cr-table__body-cell cr-text-center cr-text-center cr-table__body-cell--first cr-table__body-cell--normal" style="width: 6.5rem; vertical-align: middle;">
                                                        <span class="available_quantity"><?= $order_item->available_stock ?></span>
                                                    </td>
                                                    <td class="cr-table__body-cell cr-text-center cr-table__body-cell--first cr-table__body-cell--normal" style="width: 7.5rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;">
                                                       <div class="cr-input__container">
                                                          <label class="cr-input__wrapper">
                                                              <input type="hidden" value="<?= $order_item->quantity ?>" name="vr-items[<?= $order_item->item_id ?>][original-quantity]">
                                                             <input type="number" class="cr-input display_quantity" value="<?= $order_item->quantity ?>" name="vr-items[<?= $order_item->item_id ?>][quantity]" style="text-align: center;">
                                                             <div class="cr-input__backdrop"></div>
                                                          </label>
                                                       </div>
                                                    </td>
                                                    <td class="cr-table__body-cell cr-text-right cr-table__body-cell--first cr-table__body-cell--normal" style="width: 7rem; vertical-align: middle; padding-top: 0.375rem; padding-bottom: 0.375rem;">
                                                        <div class="cr-input__container">
                                                            <label class="cr-input__wrapper">
                                                                <div class="cr-input__prefix"><span class="notranslate">$</span></div>
                                                                <input type="text" class="cr-input cr-input--has-prefix display_buy_price" value="<?= number_format($order_item->cost, 2) ?>" maxlength="15" name="vr-items[<?= $order_item->item_id ?>][cost]" style="text-align: right;">
                                                                <div class="cr-input__backdrop"></div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="cr-table__body-cell cr-text-right cr-text-right notranslate cr-table__body-cell--first cr-table__body-cell--normal" style="width: 8rem; vertical-align: middle;">
                                                        <span class="notranslate display_line_subtotal">$<?= number_format($order_item->subtotal, 2) ?></span>
                                                    </td>

                                                    <input type="hidden" name="vr-items[<?= $order_item->item_id ?>][id]" value="<?= $order_item->id ?>">
                                                    <input type="hidden" name="vr-items[<?= $order_item->item_id ?>][inventory_id]" value="<?= $order_item->inventory_id ?>">
                                                    <input type="hidden" name="vr-items[<?= $order_item->item_id ?>][inventory_qoh]" value="<?= $order_item->qoh ?>">
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <tr class="cr-table__body-row cr-table__body-row--loading">
                                            <td class="cr-table__body-cell cr-table__body-cell--loading" colspan="8">
                                                <div class="cr-table__loading cr-table__loading--hidden">
                                                    <div class="cr-table__loading-progress cr-table__loading-progress--complete"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($order->status !== 'sent') : ?>
            <div class="modal fade" id="send-to-vendor-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger-pos">
                            <h4 class="modal-title">Send to Vendor</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body clearfix" style="padding: 20px;">
                            <div class="modal-container">
                                <ul id="void-sales-warning-list">
                                    <li>Sending a vendor return removes inventory.</li>
                                    <li>Any corrections will require you to undo the entire return by clicking on reopen.</li>
                                    <li>Any unsaved changes will be saved automatically upon sending.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="send" class="btn btn-danger-pos">Send</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </form>


        <div class="modal fade" id="search-results-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body clearfix" style="padding: 20px;">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-vendor-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="<?= HOST_NAME ?>pos/vendor_return_add">
                        <div class="modal-header bg-info-dark">
                            <h5 class="modal-title"> New Vendor Return </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <fieldset>
                                <div class="form-group">
                                    <label class="custom-form-label">Vendor</label>
                                    <select class="form-control form-round vendor-select">
                                        <?php if (isset($vendors) && $vendors !== false) : ?>
                                            <?php foreach ($vendors as $vendor) : ?>
                                                <option value="<?= $vendor->id ?>"><?= $vendor->name ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success2 select-vendor-btn">Select Vendor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>