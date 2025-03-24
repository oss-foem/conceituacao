<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .auth-username {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto" id="main-menu">
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item" id="auth-info" style="display: none;">
                            <span class="nav-link auth-username" id="auth-username"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const token = localStorage.getItem('jwt_token');
            if (token) {
                try {
                    const response = await axios.get('/api/user', {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });

                    const user = response.data;

                    document.getElementById('auth-username').textContent = user.name;
                    document.getElementById('auth-info').style.display = 'block';

                    const mainMenu = document.getElementById('main-menu');
                    mainMenu.innerHTML = `
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Dashboard</a>
                        </li>
                    `;

                    if (user.roles && user.roles.some(role => role.name === 'admin')) {
                        mainMenu.innerHTML += `
                            <li class="nav-item">
                                <a class="nav-link" href="/users">Usuários</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/roles">Perfis</a>
                            </li>
                        `;
                    }
                } catch (error) {
                    console.error('Erro ao verificar autenticação:', error);
                    if (window.location.pathname !== '/login') {
                        localStorage.removeItem('jwt_token');
                        window.location.href = '/login';
                    }
                }
            } else if (window.location.pathname !== '/login' && window.location.pathname !== '/register') {
                window.location.href = '/login';
            }
        });
    </script>
</body>
</html>
