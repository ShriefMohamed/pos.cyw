<?php if (isset($repair) && $repair && isset($barcode) && $barcode) : ?>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td>&nbsp;</td>
            <td class="container">
                <div class="content">
                    <button class="btn" id="print" onclick="window.print()">Print</button>
                    <table role="presentation" class="main" id="section-to-print">
                        <tr>
                            <td class="wrapper">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 25px 0">
                                            <p>Job ID: <strong><?= $repair->job_id ?></strong></p>
                                            <p>Customer Name: <strong><?= $repair->firstName.' '.$repair->lastName ?></strong></p>
                                            <p>Phone Number: <strong><?= $repair->phone ?></strong></p>
                                            <p>Booking Date: <strong><?= \Framework\lib\Helper::ConvertDateFormat($repair->created) ?></strong></p>
                                        </td>
                                        <td>
                                            <?= $barcode ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print {display: none}
            #section-to-print, #section-to-print * {
                visibility: visible;
            }
            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
        img {border: none;-ms-interpolation-mode: bicubic;max-width: 100%; }
        body {background-color: #f6f6f6;font-family: sans-serif;-webkit-font-smoothing: antialiased;
            font-size: 12pt;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%; }
        table {border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%; }
        table td {font-family: sans-serif;font-size: 12pt;vertical-align: top; color: #444}
        .body {background-color: #f6f6f6;width: 100%; }
        .container {display: block;margin: 0 auto !important;padding: 10px;width: fit-content; }
        .content {box-sizing: border-box;display: block;margin: 0 auto;padding: 10px; }
        .main {background: #ffffff;border-radius: 3px;width: fit-content }
        .wrapper {box-sizing: border-box;padding: 0 20px; }

        a {color: #3498db;text-decoration: underline; }
        .btn {background-color: #5bc0de !important;color: #FFF;font-size: .875rem;display: inline-block;
            font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;user-select: none;
            cursor: pointer;border: 1px solid transparent;padding: .375rem 1.75rem;line-height: 1.5;
            border-radius: .25rem;margin-bottom: 1rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
        .btn:hover {
            -webkit-box-shadow: none;
            box-shadow: none;
            background-color: #31b0d5 !important;
            border-color: #269abc !important;
        }
    </style>
<?php endif; ?>