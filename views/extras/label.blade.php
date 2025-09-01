<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A1 Barcode Template</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <style>
        @page {
            size: A1; /* A1 size: 594mm × 841mm */
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            height: 841mm;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 415mm;
            height: 20%;
/*            margin: 40mm auto;*/
            padding: 10mm;
            border: 1px solid #000;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .info-container {
            display: flex;
            flex-direction: column;
            gap: 5mm;
        }
        .info-row {
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
            font-size: 14pt;
        }
        .info-value {
            width: 60%;
            font-size: 14pt;
        }
        .barcode-container {
            text-align: center;
            margin: 10mm 0;
        }
        svg {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<div>
    <div class="container">
        <div class="header">
            <h1>Patient Information</h1>
        </div>
        <div class="info-container">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value" id="name">John Doe</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile No:</div>
                <div class="info-value" id="mobile">+1 (555) 123-4567</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tray No:</div>
                <div class="info-value" id="tray">T-12345</div>
            </div>
            <div class="info-row">
                <div class="info-label">Batch No:</div>
                <div class="info-value" id="batch">B-789012</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Date:</div>
                <div class="info-value" id="date">May 15, 2025</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Time:</div>
                <div class="info-value" id="time">10:30 AM</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value" id="address">123 Medical Center Drive, Suite 456, Healthcare City, HC 12345</div>
            </div>
        </div>
        <div class="barcode-container">
            <svg id="barcode"></svg>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <h1>Patient Information</h1>
        </div>
        <div class="info-container">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value" id="name">John Doe</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile No:</div>
                <div class="info-value" id="mobile">+1 (555) 123-4567</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tray No:</div>
                <div class="info-value" id="tray">T-12345</div>
            </div>
            <div class="info-row">
                <div class="info-label">Batch No:</div>
                <div class="info-value" id="batch">B-789012</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Date:</div>
                <div class="info-value" id="date">May 15, 2025</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Time:</div>
                <div class="info-value" id="time">10:30 AM</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value" id="address">123 Medical Center Drive, Suite 456, Healthcare City, HC 12345</div>
            </div>
        </div>
        <div class="barcode-container">
            <svg id="barcode"></svg>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <h1>Patient Information</h1>
        </div>
        <div class="info-container">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value" id="name">John Doe</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile No:</div>
                <div class="info-value" id="mobile">+1 (555) 123-4567</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tray No:</div>
                <div class="info-value" id="tray">T-12345</div>
            </div>
            <div class="info-row">
                <div class="info-label">Batch No:</div>
                <div class="info-value" id="batch">B-789012</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Date:</div>
                <div class="info-value" id="date">May 15, 2025</div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment Time:</div>
                <div class="info-value" id="time">10:30 AM</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value" id="address">123 Medical Center Drive, Suite 456, Healthcare City, HC 12345</div>
            </div>
        </div>
        <div class="barcode-container">
            <svg id="barcode"></svg>
        </div>
    </div>
</div>

<script>
    // Generate barcode - using a sample value
    // In a real scenario, this would be replaced with actual patient ID
    JsBarcode("#barcode", "PAT-123456789", {
        format: "CODE128",
        width: 3,
        height: 70,
        displayValue: true,
        fontSize: 18,
        text: "Patient ID: PAT-123456789"
    });
    
    // You would use JavaScript to populate fields with real data
    // Example:
    // document.getElementById("name").textContent = patientData.name;
    // document.getElementById("mobile").textContent = patientData.mobile;
    // etc.
</script>
</body>
</html>