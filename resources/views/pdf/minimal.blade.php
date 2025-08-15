<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Minimal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Test PDF Minimal</h1>
    </div>

    <div class="content">
        <p><strong>Message:</strong> {{ $message }}</p>
        <p><strong>Date:</strong> {{ $date }}</p>
        <p>Si vous voyez ce PDF, DomPDF fonctionne correctement !</p>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #666;">
        <p>Test réussi - Driv'n Cook</p>
    </div>
</body>
</html>
