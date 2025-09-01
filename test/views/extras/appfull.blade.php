<!-- resources/views/labels/a4-template.blade.php -->
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
            width: 210mm;
            height: 297mm;
            position: relative;
/*            border: 0.1mm solid #ddd;*/
            page-break-after: always;
        }
        .label-sheet2 {
            /*margin-top: 6mm;
            margin-bottom: 6mm;*/
            page-break-after: auto;
        }
        .label {
            position: absolute;
            width: 105mm;
            height: 57mm;
            border-bottom: 0.1mm solid #ddd;
            box-sizing: border-box;
            overflow: hidden;
            padding-bottom: 3mm;
        }
        .label-p1{
            padding: 6mm 6mm;
        }

        .label:nth-child(1) { top: 0mm; left: 0mm; }
        .label:nth-child(2) { top: 0mm; left: 105mm; }
        .label:nth-child(3) { top: 57mm; left: 0mm; }
        .label:nth-child(4) { top: 57mm; left: 105mm; }
        .label:nth-child(5) { top: 116mm; left: 0mm; }
        .label:nth-child(6) { top: 116mm; left: 105mm; }
        .label:nth-child(7) { top: 177mm; left: 0mm; }
        .label:nth-child(8) { top: 177mm; left: 105mm; }
        .label:nth-child(9) { top: 234mm; left: 0mm; }
        .label:nth-child(10) { top: 234mm; left: 105mm; }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
/*            margin-bottom: 2mm;*/
        }
        .customer-name {
            font-weight: bold;
            font-size: 11pt;
            text-align: left;
        }
        .phone {
            text-align: right;
            font-size: 10pt;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .field-label {
            font-weight: bold;
            font-size: 9pt;
            color: #555;
        }
        .field-value {
            font-size: 9pt;
        }
        .barcode-cell {
            text-align: center;
            padding: 2mm 0;
        }
        .barcode-cell img {
            max-width: 90%;
            height: 10mm;
        }
        .appointment-box {
            background-color: #f5f5f5;
            border: 0.1mm solid #ddd;
            border-radius: 1mm;
            padding: 1mm;
            margin-top: 1mm;
        }
        .appointment-table {
            width: 100%;
            border-collapse: collapse;
        }
        .address-box {
            border: 0.1mm dashed #999;
            border-radius: 1mm;
            padding: 1mm;
            margin-top: 1mm;
            font-size: 9pt;
            height: 18mm;
            overflow: hidden;
        }
        .addr-area{
            font-size: 10pt;
            font-weight: 600;
        }
    </style>
</head>
<body>
    @php
        $itemsPerPage = 10;
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
            <table class="header-table">
                <tr>
                    <td class="customer-name">{{ $label['name'] }}</td>
                    <td class="phone">Ph: {{ $label['mobile'] }}</td>
                </tr>
            </table>
            <table class="content-table">
                <tr>
                    <td width="33%">
                        <span class="field-label">Barcode:</span><br>
                        <span class="field-value">{{ $label['barcode'] }}</span>
                    </td>
                    <td width="33%">
                        <span class="field-label">Tray No:</span><br>
                        <span class="field-value">{{ $label['tray_no'] }}</span>
                    </td>
                    <td width="33%">
                        <span class="field-label">Batch No:</span><br>
                        <span class="field-value">{{ $label['batch_no'] }}</span>
                    </td>
                </tr>
            </table>
            @if($label['appointment'])
            <div class="appointment-box">
                <table class="appointment-table">
                    <tr>
                        <td width="50%">
                            <span class="field-label">Appointment Date:</span><br>
                            <b class="field-value">{{ $label['appointment']['appointment_date'] }}</b>
                        </td>
                        <td width="50%">
                            <span class="field-label">Time:</span><br>
                            <b class="field-value">@if($label['appointment']['slot']){{ $label['appointment']['slot']['name'] }}@endif</b>
                        </td>
                    </tr>
                </table>
            </div>
            @endif
            <div class="address-box">
                @if($label['appointment']['address_type'] != '2')
                <span class="field-label">Address:</span>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            @if(!empty($label['area']))Area: <span class="addr-area">{{ $label['area'] }}</span>, @endif 
                            @if(!empty($label['block']))Block: <b>{{ $label['block'] }}</b>, @endif 
                            @if(!empty($label['street']))Street: <b>{{ $label['street'] }}</b>, @endif 
                            @if(!empty($label['avenue']))Avenue: <b>{{ $label['avenue'] }}</b>, @endif 
                            @if(!empty($label['house_no']))House #: <b>{{ $label['house_no'] }}</b>, @endif 
                            @if(!empty($label['floor_no']))Floor #: <b>{{ $label['floor_no'] }}</b>, @endif 
                            @if(!empty($label['flat_no']))Flat #: <b>{{ $label['flat_no'] }}</b>, @endif 
                            @if(!empty($label['pacii_no']))PACI #: <b>{{ $label['pacii_no'] }}</b>@endif 
                        </td>
                    </tr>
                    @if(!empty($label['landmark']))
                    <tr>
                        <td>
                            Remarks: <b>{{ $label['landmark'] }}</b>
                        </td>
                    </tr>
                    @endif 
                </table>
                @else
                <span class="field-label">Branch:</span>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            Branch: <span class="addr-area">{{ $label['branch']['name'] }}</span>, Time: <b>{{ $label['branch']['morning_branch_time'] }} @if($label['branch']['evening_branch_time'])-{{ $label['branch']['evening_branch_time'] }}@endif</b>
                            @if(!empty($label['preference']))Preference: <span class="addr-area">{{ $label['preference'] }}</span>, @endif 
                            @if(!empty($label['req_from']))Request from: <span class="addr-area">{{ $label['req_from'] }}</span>, @endif 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Remarks: <b>{{ $label['landmark'] }}</b>
                        </td>
                    </tr>
                </table>
                @endif
            </div>
        </div>
        </div>
    @endforeach
    </div>
    </div>
    @endfor
</body>
</html>