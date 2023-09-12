<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ "معلومات تسجيل الدخول الخاصة بك - مركز قيم" }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Add your email styling here (optional) */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }
        td {
            text-align: right;
            vertical-align: middle;
        }
        .center-table {
            text-align: center;
        }
        img {
            max-width: 200px;
            display: block;
            margin: 0 auto;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
        }
        .signature {
            font-size: 14px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <td align="center">
            <img src="{{ asset('qiam.jpg') }}" alt="مركز قيم" title="مركز قيم">
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <p class="greeting">مرحبًا {{$first_name}},</p>
            <p class="message">نود تزويدك بمعلومات تسجيل دخولك لمركز قيم:</p>
            <p class="message"><strong>اسم المستخدم:</strong> {{ $username }}</p>
            <p class="message"><strong>كلمة المرور:</strong> {{ $password }}</p>
            <p class="message">يمكنك الآن استخدام هذه المعلومات لتسجيل الدخول والاستمتاع بخدماتنا.</p>
        </td>
    </tr>
    <tr>
        <td align="center">
            <p class="signature">&copy; {{ date('Y') }} مركز قيم. جميع الحقوق محفوظة. </p>
        </td>
    </tr>
</table>
</body>
</html>
