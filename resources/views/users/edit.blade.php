@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Editar Usuário') }}</span>
                    <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
                </div>

                <div class="card-body">
                    <div id="loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <form id="update-form" style="display: none;">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        if (!authService.isAuthenticated()) {
            window.location.href = '/login';
            return;
        }

        const urlParts = window.location.pathname.split('/');
        const userId = urlParts[urlParts.indexOf('users') + 1];
        const token = localStorage.getItem('jwt_token');

        try {
            const token = authService.getToken();
            const response = await axios.get(`http://localhost:8000/api/users/${userId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            const user = response.data;

            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;

            document.getElementById('loading').style.display = 'none';
            document.getElementById('update-form').style.display = 'block';
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            alert('Erro ao carregar dados do usuário. Verifique o console para mais detalhes.');
            window.location.href = '/dashboard';
        }

        document.getElementById('update-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const userData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value
            };

            const password = document.getElementById('password').value;
            if (password) {
                userData.password = password;
                userData.password_confirmation = document.getElementById('password_confirmation').value;
            }

            try {
                const token = authService.getToken();
                await axios.put(`http://localhost:8000/api/users/${userId}`, userData, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                alert('Usuário atualizado com sucesso!');
                window.location.href = '/dashboard';
            } catch (error) {
                console.error('Erro ao atualizar usuário:', error);

                if (error.response && error.response.data.errors) {
                    const errorMessages = Object.values(error.response.data.errors).flat();
                    alert('Erro: ' + errorMessages.join('\n'));
                } else {
                    alert('Erro ao atualizar usuário. Verifique o console para mais detalhes.');
                }
            }
        });
    });
</script>
@endsection
