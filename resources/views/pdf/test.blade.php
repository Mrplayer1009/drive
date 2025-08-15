<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Généré le {{ $date }}</p>
    </div>

    <div class="content">
        <p>{{ $content }}</p>
        <p>Si vous voyez ce PDF, cela signifie que DomPDF fonctionne correctement !</p>
    </div>

    <div class="footer">
        <p>Test de génération PDF - Driv'n Cook</p>
    </div>
</body>
</html>
