<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARAHE{{ $bill->summary->registration->form->session->year }} Payment Receipt</title>
    <style>
        .center {
            display: block;
            /* margin-left: auto;
            margin-right: auto; */
            width: 40%;
            margin-left: 30%;
        }

        .imgDiv {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .tableDiv {
            margin-left: 5%;
        }

        .table {
            text-align: center
        }

        table th,
        table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <div class='imgDiv'>
        <img src="{{ $imageBase64 }}" alt="ARAHE Logo" class="center" width="300" height="200">
    </div>

    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:5.4pt;font-size:15px;font-family:"URW Gothic";text-align:center;line-height:11.0pt;'>
        <strong><span
                style='font-size:16px;font-family:"Verdana",sans-serif;'>Malaysia&nbsp;Association&nbsp;of&nbsp;Home&nbsp;Economics
                (MAHE)</span></strong>
    </p>
    <p
        style='margin-top:.2pt;margin-right:.2pt;margin-bottom:.0001pt;margin-left:0cm;font-size:15px;font-family:"URW Gothic";text-align:center;line-height:95%;'>
        <span style="font-size:16px;line-height:95%;">Department of Science and Technical Education, Faculty of
            Educational Studies</span>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:5.4pt;font-size:15px;font-family:"URW Gothic";text-align:center;line-height:10.9pt;'>
        <span style="font-size:16px;">Universiti Putra Malaysia, 43400 Serdang, Selangor, MALAYSIA</span>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        Email: arahe2023@gmail.com.</p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        <strong><span style='font-size:32px;font-family:"Arial",sans-serif;'>RECEIPT</span></strong>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
        <strong><span style="font-size:19px;">21st ARAHE BIENNIAL INTERNATIONAL CONGRESS 2023&nbsp;</span></strong>
    </p>
    <p
        style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
        <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
    </p>
    <div align='center' width="100%" cellpadding="2" cellspacing="0" border="1" class="tableDiv"
        style='line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
        <table style="width:459.15pt;border-collapse:collapse;border:none;" class="table">
            <tbody>
                <tr>
                    <td colspan="2"
                        style="width:238.25pt;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:34.15pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Receipt
                                    No: {{ $bill->summary->registration->code }}:{{ $bill->id }}</span></strong>
                        </p>
                    </td>
                    <td
                        style="width:220.5pt;border:solid windowtext 1.0pt;border-left:  none;padding:0cm 5.4pt 0cm 5.4pt;height:34.15pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Date
                                    Transaction: {{ $bill->getTransactionDate() }}</span></strong>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Receipt
                                    Date:</span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span
                                style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;{{ $bill->getReceiptDate() }}</span>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Receipt
                                    To: </span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span
                                style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;{{ $bill->summary->registration->participant->getTitle() }}
                                {{ $bill->summary->registration->participant->name }}</span>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.35pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Payment
                                    For: </span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.35pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span
                                style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;ARAHE{{ $bill->summary->registration->form->session->year }}
                                Registration</span>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:29.65pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Reference
                                    ID: </span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:29.65pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span
                                style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;{{ $bill->summary->registration->code }}</span>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Payment
                                    Mode:</span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;
                                {{ $bill->getPaymentMethod() }}</span>
                        </p>
                    </td>
                    <td style="border:none;padding:0cm 0cm 0cm 0cm;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width:113.15pt;border:solid windowtext 1.0pt;border-top:  none;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <strong><span style='font-size:16px;font-family:"Arial",sans-serif;'>Amount:</span></strong>
                        </p>
                    </td>
                    <td colspan="2"
                        style="width:345.6pt;border-top:none;border-left:  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:31.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;
                                {{ $bill->summary->getFormalOutputTotal() }}</span>
                        </p>
                    </td>
                    <td style="border:none;border-bottom:solid windowtext 1.0pt;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                            &nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style="width: 459.15pt;border-right: 1pt solid windowtext;border-bottom: 1pt solid windowtext;border-left: 1pt solid windowtext;border-image: initial;border-top: none;padding: 0cm 5.4pt;vertical-align: top;">
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
                            <span style='font-size:16px;font-family:  "Arial",sans-serif;'>&ldquo;This is computer
                                generated receipt no signature required.&rdquo;</span>
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
                            <span style='font-size:16px;font-family:  "Arial",sans-serif;'>&nbsp;</span>
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
                            <span style='font-size:13px;font-family:  "Arial",sans-serif;'>Sent/Printed on:
                                {{ $bill->getReceiptGenerateDate() }}
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
                            <span style='font-size:16px;font-family:  "Arial",sans-serif;'>&nbsp;</span>
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'>
                            <span style='font-size:16px;font-family:  "Arial",sans-serif;'>&copy;2023 MAHE All rights
                                reserved.</span>
                        </p>
                        <p
                            style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                            <span style='font-size:16px;font-family:"Arial",sans-serif;'>&nbsp;</span>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
