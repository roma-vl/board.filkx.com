<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            height: 60px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .footer .date {
            font-size: 13px;
            margin-bottom: 20px;
        }

        .footer .signature {
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/logo.png') }}" alt="Logo">
    <div class="title">Квитанція про оплату №{{ $order->id }}</div>
</div>

<table>
    <tr>
        <th>ID оголошення</th>
        <td>{{ $order->advert_id }}</td>
    </tr>
    <tr>
        <th>Тип послуги</th>
        <td>{{ $order->service_type }}</td>
    </tr>
    <tr>
        <th>Ціна</th>
        <td>{{ number_format($order->price, 2) }} грн</td>
    </tr>
    <tr>
        <th>Метод оплати</th>
        <td>{{ $order->payment_method }}</td>
    </tr>
    <tr>
        <th>Дата замовлення</th>
        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
    </tr>
</table>

<div class="footer">
    <div class="date">Дата: {{ now()->format('d.m.Y') }}</div>
    <div class="signature">Підпис: _______________________</div>
</div>

</body>
</html>
