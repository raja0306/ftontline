<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>A4 Labels</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9pt;
        }
        .label-sheet {
            margin: 6mm 3mm;
            width: 210mm;
            height: 297mm;
            position: relative;
/*            border: 0.1mm solid #ddd;*/
            page-break-after: always;
        }
        .label-sheet2 {
            page-break-after: auto;
        }
        .label {
            position: absolute;
            width: 99mm;
            height: 46mm;
            border: 0.25mm solid #000000;
            box-sizing: border-box;
            overflow: hidden;
            /*padding-bottom: 3mm;*/
            /*margin: 0.5mm;*/
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .label:nth-child(1)  { top: 0mm;    left: 0mm; padding-left: 8px; }
        .label:nth-child(2)  { top: 0mm;    left: 105mm; padding-right: 8px; }

        .label:nth-child(3)  { top: 51.5mm;   left: 0mm; padding-left: 8px; }
        .label:nth-child(4)  { top: 51.5mm;   left: 105mm; padding-right: 8px; }

        .label:nth-child(5)  { top: 101mm;  left: 0mm; padding-left: 8px; }
        .label:nth-child(6)  { top: 101mm;  left: 105mm; padding-right: 8px; }

        .label:nth-child(7)  { top: 151.5mm;  left: 0mm; padding-left: 8px; }
        .label:nth-child(8)  { top: 151.5mm;  left: 105mm; padding-right: 8px; }

        .label:nth-child(9)  { top: 202mm;  left: 0mm; padding-left: 8px; }
        .label:nth-child(10) { top: 202mm;  left: 105mm; padding-right: 8px; }

        .label:nth-child(11) { top: 252.5mm;  left: 0mm; padding-left: 8px; }
        .label:nth-child(12) { top: 252.5mm;  left: 105mm; padding-right: 8px; }

        .border-right{
            border-right: 1px solid #000000;
            margin-right: 20px;
        }
        .border-top{
            border-top: 1px solid #000000;
        }
        .pb-2{
            padding-bottom: 4px;
        }
        .name-bw{
            font-size: 18px;
            font-weight: 600;
        }
        .text-bw{
            font-weight: 600;
            padding-right: 4px;
        }

    </style>
</head>
<body>
    <div>
    @php
        $itemsPerPage = 12;
        $totalPagess = ceil(12 / $itemsPerPage);
    @endphp
    @for($pageIndex = 0; $pageIndex < $totalPagess; $pageIndex++)
    <div class="label-sheet" style="page-break-after: always;">
    <div class="label-sheet2">
    @php
        $startIndex = $pageIndex * $itemsPerPage;
    @endphp
    @for($pageIndex1 = 0; $pageIndex1 < $itemsPerPage; $pageIndex1++)
        <div class="label">
        <div class="label-p1">
            <table class="header-table">
                <tr>
                    <td class="customer-name" style="text-align: center;"><img src="data:image/png;base64,{{ $frontline }}" style="height:36px;"></td>
                    <td style="text-align: center; padding-top:3px;">
                        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="barcode" style="height:32px;">
                        <p style="font-size:10px;margin: 0;">{{$barcode}}</p>
                    </td>
                    <td style="float: right;"><img src="data:image/png;base64,{{ $kfhbank }}" style="height:36px;"></td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:14px; padding: 2px;width: 30%;">Sr. No: <span class="text-bw">SUN JUN 0942</span></td>  
                    <td style="font-size:14px; padding: 2px;">Br: <span class="text-bw">Mubarak Al Kabeer</span></td> 
                    <td style="font-size:14px; padding: 2px;float: right;">Pickup date: <span class="text-bw">03-06-2025</span></td>
                </tr>
            </table>
            <table>
                <tr class="border-top">
                    <td style="font-size:16px;padding: 0px 2px;">Name:
                    <span class="name-bw" style="padding-bottom: 4px;">Test Customer</span></td>
                </tr>
                <tr class="border-top">
                    <td style="font-size:16px;padding: 0px 2px;">Customer Civil ID:
                    <span class="name-bw" style="padding-bottom: 4px;">290909018820</span></td>
                </tr>
                <tr class="border-top">
                    <td style="font-size:16px;padding: 0px 2px;">Receiver Civil ID:
                    <span class="name-bw" style="padding-bottom: 4px;">290909010567</span></td>
                </tr>
                <tr class="border-top" style="height:24px;padding: 0px 2px;">
                    <td style="font-size:16px;">Guardian Name:
                    <span class="name-bw">Customer guardian</span></td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:14px; padding: 2px;width: 30%;">Type: <span class="text-bw">Renewal card</span>  
                    <td style="font-size:14px; padding: 2px;" colspan="2">Description: <span class="text-bw">Visa Debit Card</span>
                    </td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:16px;padding: 0px 2px;">Phone:
                    <span class="name-bw"> 4532300</span></td>
                    <td style="font-size:16px;">Alt Phone:
                    <span class="name-bw"> 45353423</span></td>
                </tr>
            </table>
        </div>
        </div>
    @endfor
    </div>
    </div>
    @endfor
    </div>
</body>
</html>