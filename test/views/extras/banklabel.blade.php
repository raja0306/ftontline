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
        margin: 6mm 4mm;
        padding: 2mm;
        width: 210mm;
        height: 297mm;
        position: relative;
        page-break-after: always;
    }

    .label {
        position: absolute;
        overflow: hidden;
        box-sizing: border-box;
        width: 97mm;   /* half of A4 width */
        height: 48mm;   /* 6 rows (297mm ÷ 6 ≈ 49.5mm, use 48mm for spacing) */
        padding: 2mm;
        background: #fff;
    }
    .label-p1 {
        border: 0.25mm solid #000; /* ✅ outer border for each label */
    }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .label:nth-child(1)  { top: 0mm;    left: 0mm; }
        .label:nth-child(2)  { top: 0mm;    left: 100mm; }

        .label:nth-child(3)  { top: 48mm;   left: 0mm; }
        .label:nth-child(4)  { top: 48mm;   left: 100mm; }

        .label:nth-child(5)  { top: 96mm;  left: 0mm; }
        .label:nth-child(6)  { top: 96mm;  left: 100mm; }

        .label:nth-child(7)  { top: 144mm;  left: 0mm; }
        .label:nth-child(8)  { top: 144mm;  left: 100mm; }

        .label:nth-child(9)  { top: 192mm;  left: 0mm; }
        .label:nth-child(10) { top: 192mm;  left: 100mm; }

        .label:nth-child(11) { top: 240mm;  left: 0mm; }
        .label:nth-child(12) { top: 240mm;  left: 100mm; }

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
        $totalPagess = ceil(count($labels) / $itemsPerPage);
    @endphp
    @for($pageIndex = 0; $pageIndex < $totalPagess; $pageIndex++)
    <div class="label-sheet" style="page-break-after: always;">
    <div class="label-sheet2">
    @php
        $startIndex = $pageIndex * $itemsPerPage;
        $pageItems = array_slice($labels, $startIndex, $itemsPerPage);
    @endphp
    @foreach($pageItems as $label)
        <div class="label">
        <div class="label-p1">
        <div class="label-p2">
            <table class="header-table">
                <tr>
                    <td class="customer-name" style="text-align: center;"><img src="data:image/png;base64,{{ $frontline }}" style="height:48px;padding: 2px;"></td>
                    <td style="text-align: center; padding-top:3px;">
                        <img src="data:image/png;base64,{{ $label['barcodeimg'] }}" alt="barcode" style="height:48px;">
                        <p style="font-size:10px;margin: 0;">{{$label['barcode']}}</p>
                    </td>
                    <td style="float: right;"><img src="data:image/png;base64,{{ $kfhbank }}" style="height:48px;padding: 2px;"></td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:14px; padding: 2px;width: 30%;">Sr. No: <span class="text-bw">{{$label['batch_no']}}</span></td>  
                    <td style="font-size:14px; padding: 2px;">Br: <span class="text-bw">Mubarak Al Kabeer</span></td> 
                    <td style="font-size:14px; padding: 2px;float: right;">Pickup date: <span class="text-bw">03-06-2025</span></td>
                </tr>
            </table>
            <table>
                <tr class="border-top">
                    <td style="font-size:18px;padding: 0px 2px;">Name:
                    <span class="name-bw" style="padding-bottom: 4px;">{{$label['name']}}</span></td>
                </tr>
                <tr class="border-top">
                    <td style="font-size:18px;padding: 0px 2px;">Customer Civil ID:
                    <span class="name-bw" style="padding-bottom: 4px;">{{$label['civil_id']}}</span></td>
                </tr>
                <tr class="border-top">
                    <td style="font-size:18px;padding: 0px 2px;">Receiver Civil ID:
                    <span class="name-bw" style="padding-bottom: 4px;">{{$label['receiver_civil']}}</span></td>
                </tr>
                <tr class="border-top" style="height:24px;padding: 0px 2px;">
                    <td style="font-size:18px;">Guardian Name:
                    <span class="name-bw">{{$label['guardian']}}</span></td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:16px; padding: 2px;width: 35%;">Type: <span class="text-bw">{{$label['commodity']}}</span>  
                    <td style="font-size:16px; padding: 2px;" colspan="2">Description: <span class="text-bw">{{$label['description']}}</span>
                    </td>
                </tr>
            </table>
            <table class="border-top">
                <tr>
                    <td style="font-size:18px;padding: 0px 2px;">Phone:
                    <span class="name-bw"> {{$label['mobile']}}</span></td>
                    <td style="font-size:18px;">Alt Phone:
                    <span class="name-bw"> {{$label['mobile2']}}</span></td>
                </tr>
            </table>
        </div>
        </div>
        </div>
    @endforeach
    </div>
    </div>
    @endfor
    </div>
</body>
</html>