@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Dashboard') }}</span>
                    <button id="logout-btn" class="btn btn-sm btn-outline-danger">Logout</button>
                </div>

                <div class="card-body">
                    <div id="loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="user-info" style="display: none;">
                        <h3>Bem-vindo, <span id="user-name">Carregando...</span></h3>
                        <p>Email: <span id="user-email"></span></p>

                        <div id="admin-section" style="display: none;">
                            <hr>
                            <h4>Administração</h4>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Gerenciar Usuários</h5>
                                            <p class="card-text">Crie, edite ou remova usuários do sistema.</p>
                                            <a href="javascript:void(0)" onclick="window.location.href=createAuthUrl('/users')" class="btn btn-primary">Acessar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Gerenciar Perfis</h5>
                                            <p class="card-text">Gerencie perfis de acesso ao sistema.</p>
                                            <a href="javascript:void(0)" onclick="window.location.href=createAuthUrl('/roles')" class="btn btn-primary">Acessar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="user-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script>
    function createAuthUrl(baseUrl) {
        const token = authService.getToken();
        return `${baseUrl}?token=${token}`;
    }
    document.addEventListener('DOMContentLoaded', async function() {
        if (!authService.isAuthenticated()) {
            window.location.href = '/login';
            return;
        }

        async function loadAllUsers() {
            try {
                const token = authService.getToken();
                const response = await axios.get('http://localhost:8000/api/users', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                const users = response.data;
                console.log('Todos os usuários:', users);
                console.log('Token:', token);

                const userTableBody = document.querySelector('#user-table tbody');

                userTableBody.innerHTML = '';

                users.forEach(user => {
                    const row = document.createElement('tr');
                    const token = authService.getToken();
    const viewUrl = `/users/${user.id}?token=${token}`;
    const editUrl = `/users/${user.id}/edit?token=${token}`;
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                             <a href="${viewUrl}" class="btn btn-sm btn-primary">Ver</a>
            <a href="${editUrl}" class="btn btn-sm btn-secondary">Editar</a>
                        </td>
                    `;
                    userTableBody.appendChild(row);
                });
            } catch (error) {
                console.error('Erro ao carregar todos os usuários:', error);
                alert('Não foi possível carregar a lista de usuários. Verifique o console para mais detalhes.');
            }
        }

        try {
    const userData = await authService.getCurrentUser();

    document.getElementById('user-name').textContent = userData.name;
    document.getElementById('user-email').textContent = userData.email;

    try {
        const token = authService.getToken();
        const isAdminResponse = await axios.get(`http://localhost:8000/api/is-admin/${userData.id}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        console.log('Resposta de verificação admin:', isAdminResponse.data);

        if (isAdminResponse.data.isAdmin === true) {
            document.getElementById('admin-section').style.display = 'block';
            await loadAllUsers();
        }
    } catch (adminError) {
        console.error('Erro ao verificar permissões de administrador:', adminError);
    }

    document.getElementById('loading').style.display = 'none';
    document.getElementById('user-info').style.display = 'block';
} catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            if (error.response && error.response.status === 401) {
                alert('Sua sessão expirou. Por favor, faça login novamente.');
                window.location.href = '/login?expired=true';
            } else {
                alert('Erro ao carregar dados do usuário. Tente novamente mais tarde.');
            }
        }

        document.getElementById('logout-btn').addEventListener('click', async function() {
            try {
                await authService.logout();
                window.location.href = '/login';
            } catch (error) {
                console.error('Erro ao fazer logout:', error);
                authService.removeToken();
                window.location.href = '/login';
            }
        });
    });
</script>
@endsection
