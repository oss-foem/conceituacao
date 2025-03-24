@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Gerenciamento de Usuários') }}</span>
                    <button id="add-user-btn" class="btn btn-sm btn-success">Adicionar Usuário</button>
                </div>

                <div class="card-body">
                    <div id="alert-container"></div>

                    <div id="loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="users-container" style="display: block;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfis</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Usuário -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Adicionar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="user-id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3 password-field">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3 password-field">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" required>
                    </div>
                    <div id="perfis" class="mb-3">
                        <label  class="form-label">Perfis</label>
                        <div id="roles-checkboxes">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-user-btn">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script>
    const userService = {
        async getUsers() {
            return await axios.get('/api/users', {
                headers: authService.getAuthHeader()
            });
        },

        async getUser(id) {
            return await axios.get(`/api/users/${id}`, {
                headers: authService.getAuthHeader()
            });
        },

        async createUser(userData) {
            return await axios.post('/api/users', userData, {
                headers: authService.getAuthHeader()
            });
        },

        async updateUser(id, userData) {
            return await axios.put(`/api/users/${id}`, userData, {
                headers: authService.getAuthHeader()
            });
        },

        async deleteUser(id) {
            return await axios.delete(`/api/users/${id}`, {
                headers: authService.getAuthHeader()
            });
        },

        async assignRoles(userId, roleIds) {
            return await axios.post(`/api/users/${userId}/roles`, {
                roles: roleIds
            }, {
                headers: authService.getAuthHeader()
            });
        },

        async removeRole(userId, roleIds) {
            return await axios.delete(`/api/users/${userId}/roles`, {
                headers: authService.getAuthHeader(),
                data: { roles: roleIds }
            });
        },

        async getRoles() {
            return await axios.get('/api/roles', {
                headers: authService.getAuthHeader()
            });
        }
    };

    // Elementos do DOM
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const userForm = document.getElementById('userForm');
    const userIdInput = document.getElementById('user-id');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const rolesCheckboxes = document.getElementById('roles-checkboxes');
    const saveUserBtn = document.getElementById('save-user-btn');
    const addUserBtn = document.getElementById('add-user-btn');
    const usersTableBody = document.getElementById('users-table-body');
    const alertContainer = document.getElementById('alert-container');
    const loadingElement = document.getElementById('loading');
    const usersContainer = document.getElementById('users-container');

    function showAlert(message, type = 'success') {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    async function loadRoles() {
        try {
            const response = await userService.getRoles();
            const roles = response.data;

            rolesCheckboxes.innerHTML = '';
            roles.forEach(role => {
                const div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = `
                    <input class="form-check-input role-checkbox" type="checkbox" value="${role.id}" id="role-${role.id}">
                    <label class="form-check-label" for="role-${role.id}">
                        ${role.name}
                    </label>
                `;
                rolesCheckboxes.appendChild(div);
            });
        } catch (error) {
            console.error('Erro ao carregar perfis:', error);
            showAlert('Erro ao carregar perfis.', 'danger');
        }
    }

    async function loadUsers() {
        try {
            loadingElement.style.display = 'block';
            usersContainer.style.display = 'none';

            const response = await userService.getUsers();
            const users = response.data;

            usersTableBody.innerHTML = '';
            users.forEach(user => {
                const roles = user.roles.map(role => `<span class="badge bg-info">${role.name}</span>`).join(' ');

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${roles}</td>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-user" data-id="${user.id}">Editar</button>
                        <button class="btn btn-sm btn-danger delete-user" data-id="${user.id}">Excluir</button>
                    </td>
                `;
                usersTableBody.appendChild(row);
            });

            document.querySelectorAll('.edit-user').forEach(btn => {
                btn.addEventListener('click', () => editUser(btn.dataset.id));
            });

            document.querySelectorAll('.delete-user').forEach(btn => {
                btn.addEventListener('click', () => deleteUser(btn.dataset.id));
            });

            loadingElement.style.display = 'none';
            usersContainer.style.display = 'block';
        } catch (error) {
            console.error('Erro ao carregar usuários:', error);
            showAlert('Erro ao carregar usuários.', 'danger');
            loadingElement.style.display = 'none';
        }
    }

    function showAddUserModal() {
        userForm.reset();
        userIdInput.value = '';
        document.querySelectorAll('.password-field').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);

        document.getElementById('userModalLabel').textContent = 'Adicionar Usuário';
        document.getElementById('perfis').style.display = 'none';

        userModal.show();
    }

    async function editUser(id) {
        try {
            const response = await userService.getUser(id);
            const user = response.data;

            userIdInput.value = user.id;
            nameInput.value = user.name;
            emailInput.value = user.email;

            document.querySelectorAll('.password-field').forEach(el => el.style.display = 'block');
            passwordInput.required = false;
            passwordConfirmInput.required = false;

            document.getElementById('perfis').style.display = 'block';

            document.querySelectorAll('.role-checkbox').forEach(cb => {
                cb.checked = user.roles.some(role => role.id === parseInt(cb.value));
            });

            document.getElementById('userModalLabel').textContent = 'Editar Usuário';

            userModal.show();
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            showAlert('Erro ao carregar dados do usuário.', 'danger');
        }
    }

    async function deleteUser(id) {
        if (confirm('Tem certeza que deseja excluir este usuário?')) {
            try {
                await userService.deleteUser(id);
                showAlert('Usuário excluído com sucesso!');
                loadUsers();
            } catch (error) {
                console.error('Erro ao excluir usuário:', error);
                showAlert('Erro ao excluir usuário.', 'danger');
            }
        }
    }

    async function saveUser() {
    try {
        const id = userIdInput.value;
        const selectedRoleIds = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => parseInt(cb.value));

        const userData = {
            name: nameInput.value,
            email: emailInput.value,
        };

        if (!id) {
            if (passwordInput.value !== passwordConfirmInput.value) {
                showAlert('As senhas não coincidem.', 'danger');
                return;
            }
            userData.password = passwordInput.value;
            userData.password_confirmation = passwordConfirmInput.value;
            userData.roles = selectedRoleIds;

            await userService.createUser(userData);
            showAlert('Usuário criado com sucesso!');
        } else {
            if (passwordInput.value) {
                if (passwordInput.value !== passwordConfirmInput.value) {
                    showAlert('As senhas não coincidem.', 'danger');
                    return;
                }
                userData.password = passwordInput.value;
                userData.password_confirmation = passwordConfirmInput.value;
            }

            const userResponse = await userService.getUser(id);
            const currentRoleIds = userResponse.data.roles.map(role => role.id);

            await userService.updateUser(id, userData);

            if (JSON.stringify(currentRoleIds.sort()) !== JSON.stringify(selectedRoleIds.sort())) {
                if (currentRoleIds.length > 0) {
                    for (const roleId of currentRoleIds) {
                        await userService.removeRole(id, [roleId]);
                    }
                }

                if (selectedRoleIds.length > 0) {
                    await userService.assignRoles(id, selectedRoleIds);
                }
            }

            showAlert('Usuário atualizado com sucesso!');
        }

        userModal.hide();
        loadUsers();
    } catch (error) {
        console.error('Erro ao salvar usuário:', error);

        let errorMessage = 'Erro ao salvar usuário.';
        if (error.response && error.response.data && error.response.data.errors) {
            const errors = Object.values(error.response.data.errors);
            errorMessage = errors.flat().join('<br>');
        } else if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        }

        showAlert(errorMessage, 'danger');
    }
}

    document.addEventListener('DOMContentLoaded', async function() {
        if (!authService.isAuthenticated()) {
            window.location.href = '/login';
            return;
        }

        await loadRoles();
        await loadUsers();

        addUserBtn.addEventListener('click', showAddUserModal);

        saveUserBtn.addEventListener('click', saveUser);
    });
</script>
@endsection
