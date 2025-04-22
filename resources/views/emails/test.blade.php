<!DOCTYPE html>
<html>
<head>
    <title>Текстовое письмо</title>
</head>
<body>
<h1>Привет {{ $data['name'] }}!</h1>
<p>Подверждена ваша почта.это текстовое письмо от Laravel.</p>
<p>Ваш Email: {{ $data['email'] }}</p>
</body>
</html>
