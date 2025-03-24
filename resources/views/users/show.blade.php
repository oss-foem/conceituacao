@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detalhes do Usuário') }}</span>
                    <div>
                        <button id="back-btn" class="btn btn-sm btn-outline-secondary me-2">Voltar</button>
                        <button id="edit-user-btn" class="btn btn-sm btn-primary">Editar</button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="alert-container"></div>

                    <div id="loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="user-details" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4>Informações Gerais</h4>
                                <table class="table">
                                    <tr>
                                        <th style="width: 150px;">ID:</th>
                                        <td id="user-id"></td>
                                    </tr>
                                    <tr>
                                        <th>Nome:</th>
                                        <td id="user-name"></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td id="user-email"></td>
                                    </tr>
                                    <tr>
                                        <th>Criado em:</th>
                                        <td id="user-created"></td>
                                    </tr>
                                    <tr>
                                        <th>Atualizado em:</th>
                                        <td id="user-updated"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Perfis Atribuídos</h4>
                                <div id="user-roles" class="mt-3"></div>
                            </div>
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
    const userService = {
        async getUser(id) {
            return await axios.get(`/api/users/${id}`, {
                headers: authService.getAuthHeader()
            });
        }
    };

    const loadingElement = document.getElementById('loading');
    const userDetailsElement = document.getElementById('user-details');
    const alertContainer = document.getElementById('alert-container');
    const backBtn = document.getElementById('back-btn');
    const editUserBtn = document.getElementById('edit-user-btn');

    function showAlert(message, type = 'success') {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    async function loadUserDetails() {
        try {
            loadingElement.style.display = 'block';
            userDetailsElement.style.display = 'none';

            const urlParams = new URLSearchParams(window.location.search);
            const userId = window.location.pathname.split('/').pop();

            const response = await userService.getUser(userId);
            const user = response.data;

            document.getElementById('user-id').textContent = user.id;
            document.getElementById('user-name').textContent = user.name;
            document.getElementById('user-email').textContent = user.email;
            document.getElementById('user-created').textContent = formatDate(user.created_at);
            document.getElementById('user-updated').textContent = formatDate(user.updated_at);

            const userRolesElement = document.getElementById('user-roles');
            userRolesElement.innerHTML = '';

            if (user.roles && user.roles.length > 0) {
                user.roles.forEach(role => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-info me-2 mb-2 p-2';
                    badge.textContent = role.name;
                    userRolesElement.appendChild(badge);
                });
            } else {
                userRolesElement.innerHTML = '<p class="text-muted">Nenhum perfil atribuído</p>';
            }

            editUserBtn.addEventListener('click', () => {
                const token = authService.getToken();
                window.location.href = `/users/${user.id}/edit?token=${token}`;
            });

            loadingElement.style.display = 'none';
            userDetailsElement.style.display = 'block';
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            showAlert('Erro ao carregar dados do usuário.', 'danger');
            loadingElement.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', async function() {
        if (!authService.isAuthenticated()) {
            window.location.href = '/login';
            return;
        }

        await loadUserDetails();

        backBtn.addEventListener('click', () => {
            const token = authService.getToken();
            window.location.href = `/users?token=${token}`;
        });
    });
</script>
@endsection
