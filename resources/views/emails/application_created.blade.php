<!DOCTYPE html>
<html>
<head>
    <title>Заявление принято</title>
</head>
<body>
    <h1>Здравствуйте, {{ $application->full_name }}!</h1>
    <p>Ваше заявление на специальность "{{ $application->specialty->name }}" успешно принято.</p>
    <p>Ваш текущий рейтинг: <strong>{{ $application->rating }}</strong></p>
    <p>Во вложении вы найдете официальное заявление с QR-кодом для отслеживания статуса.</p>
    <br>
    <p>С уважением,<br>Приемная комиссия</p>
</body>
</html>
