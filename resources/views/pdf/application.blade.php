<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Заявление на поступление</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 10px;
        }
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">ЗАЯВЛЕНИЕ</div>
        <div>о приеме на обучение</div>
    </div>

    <div class="content">
        <div class="field">
            <span class="label">ФИО абитуриента:</span> {{ $application->full_name }}
        </div>
        <div class="field">
            <span class="label">Email:</span> {{ $application->email }}
        </div>
        <div class="field">
            <span class="label">Телефон:</span> {{ $application->phone }}
        </div>
        <div class="field">
            <span class="label">Специальность:</span> {{ $application->specialty->code }} - {{ $application->specialty->name }}
        </div>
        
        <br>
        <div class="title" style="font-size: 14px; text-align: left;">Баллы и достижения</div>
        
        <div class="field">
            <span class="label">Баллы ЕГЭ:</span> {{ $application->ege_score }}
        </div>
        <div class="field">
            <span class="label">Средний балл аттестата:</span> {{ $application->certificate_score }}
        </div>
        <div class="field">
            <span class="label">Индивидуальные достижения:</span> {{ $application->has_achievements ? 'Имеются (+10 баллов)' : 'Нет' }}
        </div>
        <div class="field">
            <span class="label">Итоговый рейтинг:</span> {{ $application->rating }}
        </div>
    </div>

    <div class="qr-code">
        <div>Проверить статус заявления:</div>
        <br>
        {!! $qrCodeSvg !!}
    </div>

    <div class="footer">
        Дата формирования: {{ now()->format('d.m.Y H:i') }} <br>
        Система "АИС Абитуриент"
    </div>
</body>
</html>
