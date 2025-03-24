document.addEventListener('DOMContentLoaded', function() {
    const rolesContainer = document.getElementById('roles-container');
    const loadingElement = document.getElementById('loading');
    const tableBody = document.getElementById('roles-table-body');
    const alertContainer = document.getElementById('alert-container');
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    const roleForm = document.getElementById('roleForm');
    const roleIdInput = document.getElementById('role-id');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const addRoleBtn = document.getElementById('add-role-btn');
    const saveRoleBtn = document.getElementById('save-role-btn');
    const modalTitle = document.getElementById('roleModalLabel');

    loadRoles();

    addRoleBtn.addEventListener('click', openAddRoleModal);
    saveRoleBtn.addEventListener('click', saveRole);

    function loadRoles() {
        showLoading();

        if (!authService.isAuthenticated()) {
            showAlert('Você precisa estar autenticado para acessar esta página.', 'danger');
            hideLoading();
            return;
        }

        axios.get('/api/roles', {
            headers: authService.getAuthHeader()
        })
        .then(response => {
            const roles = response.data.data || [];
            renderRolesTable(roles);
        })
        .catch(error => {
            console.error('Erro ao carregar perfis:', error);
            if (error.response && error.response.status === 401) {
                showAlert('Sessão expirada. Por favor, faça login novamente.', 'danger');
                redirectToLogin();
            } else {
                showAlert('Erro ao carregar perfis. Tente novamente mais tarde.', 'danger');
            }
        })
        .finally(() => {
            hideLoading();
        });
    }

    function renderRolesTable(roles) {
        tableBody.innerHTML = '';

        if (roles.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="5" class="text-center">Nenhum perfil encontrado</td>
            `;
            tableBody.appendChild(emptyRow);
        } else {
            roles.forEach(role => {
                const row = document.createElement('tr');
                const createdAt = new Date(role.created_at).toLocaleString('pt-BR');

                row.innerHTML = `
                    <td>${role.id}</td>
                    <td>${role.name}</td>
                    <td>${role.description || '-'}</td>
                    <td>${createdAt}</td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-role" data-id="${role.id}">Editar</button>
                        <button class="btn btn-sm btn-danger delete-role" data-id="${role.id}">Excluir</button>
                    </td>
                `;
                tableBody.appendChild(row);

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
        descriptionInput.value = role.description || '';
        modalTitle.textContent = 'Editar Perfil';
        roleModal.show();
    }

    function saveRole() {
        const roleId = roleIdInput.value;
        const roleData = {
            name: nameInput.value.trim(),
            description: descriptionInput.value.trim()
        };

        if (!roleData.name) {
            showAlert('O nome do perfil é obrigatório.', 'danger', true);
            return;
        }

        showLoading();

        const method = roleId ? 'put' : 'post';
        const url = roleId ? `/api/roles/${roleId}` : '/api/roles';

        axios({
            method: method,
            url: url,
            data: roleData,
            headers: authService.getAuthHeader()
        })
        .then(response => {
            const message = roleId ? 'Perfil atualizado com sucesso!' : 'Perfil criado com sucesso!';
            showAlert(message, 'success');
            roleModal.hide();
            loadRoles();
        })
        .catch(error => {
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
        })
        .finally(() => {
            hideLoading();
        });
    }

    function confirmDeleteRole(role) {
        if (confirm(`Você tem certeza que deseja excluir o perfil "${role.name}"?`)) {
            deleteRole(role.id);
        }
    }

    function deleteRole(roleId) {
        showLoading();

        axios.delete(`/api/roles/${roleId}`, {
            headers: authService.getAuthHeader()
        })
        .then(response => {
            showAlert('Perfil excluído com sucesso!', 'success');
            loadRoles();
        })
        .catch(error => {
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
        })
        .finally(() => {
            hideLoading();
        });
    }

    function showLoading() {
        loadingElement.style.display = 'block';
    }

    function hideLoading() {
        loadingElement.style.display = 'none';
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
