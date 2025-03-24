@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Gerenciamento de Perfis') }}</span>
                    <button id="add-role-btn" class="btn btn-sm btn-success">Adicionar Perfil</button>
                </div>

                <div class="card-body">
                    <div id="alert-container"></div>

                    <div id="loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="roles-container" style="display: none;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Criado em</th>
                                    <th>Atualizado em</th>
                                    <th>Usuários Atribuídos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="roles-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Perfil -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Adicionar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    <input type="hidden" id="role-id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-role-btn">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/auth.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rolesContainer = document.getElementById('roles-container');
    const loadingElement = document.getElementById('loading');
    const tableBody = document.getElementById('roles-table-body');
    const alertContainer = document.getElementById('alert-container');
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    const roleForm = document.getElementById('roleForm');
    const roleIdInput = document.getElementById('role-id');
    const nameInput = document.getElementById('name');
    const addRoleBtn = document.getElementById('add-role-btn');
    const saveRoleBtn = document.getElementById('save-role-btn');
    const modalTitle = document.getElementById('roleModalLabel');

    loadRoles();

    addRoleBtn.addEventListener('click', openAddRoleModal);
    saveRoleBtn.addEventListener('click', saveRole);

    async function loadRoles() {
        showLoading();

        if (!authService.isAuthenticated()) {
            showAlert('Você precisa estar autenticado para acessar esta página.', 'danger');
            hideLoading();
            return;
        }

        try {
            const response = await axios.get('/api/roles', {
                headers: authService.getAuthHeader()
            });

            const roles = response.data || [];
            renderRolesTable(roles);
        } catch (error) {
            console.error('Erro ao carregar perfis:', error);
            if (error.response && error.response.status === 401) {
                showAlert('Sessão expirada. Por favor, faça login novamente.', 'danger');
                redirectToLogin();
            } else {
                showAlert('Erro ao carregar perfis. Tente novamente mais tarde.', 'danger');
            }
        } finally {
            hideLoading();
        }
    }

    function renderRolesTable(roles) {
        tableBody.innerHTML = '';

        if (roles.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="6" class="text-center">Nenhum perfil encontrado</td>
            `;
            tableBody.appendChild(emptyRow);
        } else {
            roles.forEach(role => {
                const row = document.createElement('tr');
                const createdAt = formatDate(role.created_at);
                const updatedAt = formatDate(role.updated_at);

                row.innerHTML = `
                    <td>${role.id}</td>
                    <td>${role.name}</td>
                    <td>${createdAt}</td>
                    <td>${updatedAt}</td>
                    <td id="users-for-role-${role.id}"></td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-role" data-id="${role.id}">Editar</button>
                        <button class="btn btn-sm btn-danger delete-role" data-id="${role.id}">Excluir</button>
                    </td>
                `;
                tableBody.appendChild(row);

                const usersCell = row.querySelector(`#users-for-role-${role.id}`);
                if (role.user && role.user.length > 0) {
                    role.user.forEach(user => {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-info me-2 mb-2 p-2';
                        badge.textContent = user.name;
                        usersCell.appendChild(badge);
                    });
                } else {
                    usersCell.innerHTML = '<p class="text-muted">Nenhum usuário atribuído</p>';
                }

                row.querySelector('.edit-role').addEventListener('click', () => openEditRoleModal(role));
                row.querySelector('.delete-role').addEventListener('click', () => confirmDeleteRole(role));
            });
        }

        rolesContainer.style.display = 'block';
    }

    function openAddRoleModal() {
        roleIdInput.value = '';
        roleForm.reset();
        modalTitle.textContent = 'Adicionar Perfil';
        roleModal.show();
    }

    function openEditRoleModal(role) {
        roleIdInput.value = role.id;
        nameInput.value = role.name;
        modalTitle.textContent = 'Editar Perfil';
        roleModal.show();
    }

    async function saveRole() {
        const roleId = roleIdInput.value;
        const roleData = {
            name: nameInput.value.trim()
        };

        if (!roleData.name) {
            showAlert('O nome do perfil é obrigatório.', 'danger', true);
            return;
        }

        showLoading();

        try {
            const method = roleId ? 'put' : 'post';
            const url = roleId ? `/api/roles/${roleId}` : '/api/roles';

            const response = await axios({
                method: method,
                url: url,
                data: roleData,
                headers: authService.getAuthHeader()
            });

            const message = roleId ? 'Perfil atualizado com sucesso!' : 'Perfil criado com sucesso!';
            showAlert(message, 'success');
            roleModal.hide();
            loadRoles();
        } catch (error) {
            console.error('Erro ao salvar perfil:', error);
            if (error.response) {
                if (error.response.status === 401) {
                    showAlert('Sessão expirada. Por favor, faça login novamente.', 'danger');
                    redirectToLogin();
                } else if (error.response.status === 422 && error.response.data.errors) {
                    const errorMessages = Object.values(error.response.data.errors).flat().join('<br>');
                    showAlert(errorMessages, 'danger', true);
                } else {
                    showAlert('Erro ao salvar perfil. Tente novamente mais tarde.', 'danger', true);
                }
            } else {
                showAlert('Erro ao conectar ao servidor. Verifique sua conexão.', 'danger', true);
            }
        } finally {
            hideLoading();
        }
    }

    function confirmDeleteRole(role) {
        if (confirm(`Você tem certeza que deseja excluir o perfil "${role.name}"?`)) {
            deleteRole(role.id);
        }
    }

    async function deleteRole(roleId) {
        showLoading();

        try {
            await axios.delete(`/api/roles/${roleId}`, {
                headers: authService.getAuthHeader()
            });

            showAlert('Perfil excluído com sucesso!', 'success');
            loadRoles();
        } catch (error) {
            console.error('Erro ao excluir perfil:', error);
            if (error.response) {
                if (error.response.status === 401) {
                    showAlert('Sessão expirada. Por favor, faça login novamente.', 'danger');
                    redirectToLogin();
                } else if (error.response.status === 409) {
                    showAlert('Este perfil não pode ser excluído porque está em uso.', 'danger');
                } else {
                    showAlert('Erro ao excluir perfil. Tente novamente mais tarde.', 'danger');
                }
            } else {
                showAlert('Erro ao conectar ao servidor. Verifique sua conexão.', 'danger');
            }
        } finally {
            hideLoading();
        }
    }

    function showLoading() {
        loadingElement.style.display = 'block';
    }

    function hideLoading() {
        loadingElement.style.display = 'none';
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

    function showAlert(message, type = 'info', inModal = false) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        if (inModal) {
            const modalBody = document.querySelector('.modal-body');
            modalBody.insertBefore(alertDiv, modalBody.firstChild);
        } else {
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);
        }

        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }

    function redirectToLogin() {
        window.location.href = '/login';
    }
});
</script>
@endsection
