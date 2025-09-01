<!-- resources/views/labels/a4-template.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>A4 Labels</title>
    <style>
        @page {
            size: A4;
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
        }
        .label {
            position: absolute;
            width: 105mm;
            height: 57mm;
            border: 0.1mm solid #ccc;
            box-sizing: border-box;
            padding: 3mm;
            overflow: hidden;
        }
        
        /* Label positions - 2 columns, 5 rows */
        /* Row 1 */
        .label:nth-child(1) { top: 0mm; left: 0mm; }
        .label:nth-child(2) { top: 0mm; left: 105mm; }
        /* Row 2 */
        .label:nth-child(3) { top: 57mm; left: 0mm; }
        .label:nth-child(4) { top: 57mm; left: 105mm; }
        /* Row 3 */
        .label:nth-child(5) { top: 114mm; left: 0mm; }
        .label:nth-child(6) { top: 114mm; left: 105mm; }
        /* Row 4 */
        .label:nth-child(7) { top: 171mm; left: 0mm; }
        .label:nth-child(8) { top: 171mm; left: 105mm; }
        /* Row 5 */
        .label:nth-child(9) { top: 228mm; left: 0mm; }
        .label:nth-child(10) { top: 228mm; left: 105mm; }
        
        .label-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .label-header {
            border-bottom: 0.1mm solid #999;
            padding-bottom: 1mm;
            margin-bottom: 2mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .customer-name {
            font-weight: bold;
            font-size: 11pt;
            margin: 0;
        }
        .label-body {
            display: flex;
            flex-direction: column;
            gap: 1mm;
            flex-grow: 1;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
        }
        .field {
            margin-bottom: 1mm;
        }
        .field-label {
            font-weight: bold;
            font-size: 8pt;
            color: #555;
        }
        .field-value {
            font-size: 9pt;
        }
        .barcode {
            text-align: center;
            margin: 2mm 0;
        }
        .barcode img {
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
        .address-box {
            border: 0.1mm dashed #999;
            border-radius: 1mm;
            padding: 1mm;
            margin-top: 1mm;
            font-size: 8pt;
            flex-grow: 1;
            max-height: 15mm;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="label-sheet">
        @foreach($labels as $label)
        <div class="label">
            <div class="label-content">
                <div class="label-header">
                    <span class="customer-name">{{ $label['name'] }}</span>
                    <div><img src="data:image/png;base64,{{ $label['barcode'] }}" alt="Barcode" style="height: 37px;"></div>
                </div>
                
                <div class="label-body">
                    <div class="info-row">
                        <div class="field">
                            <div class="field-label">Mobile:</div>
                            <div class="field-value">{{ $label['mobile'] }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Tray No:</div>
                            <div class="field-value">{{ $label['tray_no'] }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Batch No:</div>
                            <div class="field-value">{{ $label['batch_no'] }}</div>
                        </div>
                    </div>
                    
                    <div class="barcode">
                        
                    </div>
                    
                    <div class="appointment-box">
                        <div class="info-row">
                            <div class="field">
                                <div class="field-label">Appointment Date:</div>
                                <div class="field-value">{{ $label['appointment_date'] }}</div>
                            </div>
                            <div class="field">
                                <div class="field-label">Time:</div>
                                <div class="field-value">{{ $label['appointment_time'] }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="address-box">
                        <div class="field-label">Address:</div>
                        {{ $label['address'] }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>