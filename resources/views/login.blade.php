<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Laravel + Vue.js</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div id="app">
        <login-form></login-form>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
